<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\College;
use App\Models\Inquiry;
use App\Models\Students;
use App\Services\TopsisService;

class TopsisController extends Controller
{
    private TopsisService $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function index(Request $request)
    {
        // Default weights and no location -> automatic ranking on first load
        $defaults = [
            'w_popularity' => 1.0,
            'w_variety' => 1.0,
            'w_eligibility' => 1.0,
            'w_distance' => 1.0,
        ];

        $lat = null;
        $lon = null;

        $data = $this->generateRanking([
            'popularity' => (float) $defaults['w_popularity'],
            'variety' => (float) $defaults['w_variety'],
            'eligibility' => (float) $defaults['w_eligibility'],
            'distance' => (float) $defaults['w_distance'],
        ], $lat, $lon);

        return view('home.topsis', [
            'weights' => $defaults,
            'results' => $data['results'],
            'colleges' => $data['colleges'],
            'latitude' => $lat,
            'longitude' => $lon,
            'includedCriteria' => $data['includedCriteria'],
        ]);
    }

    public function rank(Request $request)
    {
        $validated = $request->validate([
            'w_popularity' => 'nullable|numeric|min:0',
            'w_variety' => 'nullable|numeric|min:0',
            'w_eligibility' => 'nullable|numeric|min:0',
            'w_distance' => 'nullable|numeric|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $weights = [
            'popularity' => (float) ($validated['w_popularity'] ?? 1),
            'variety' => (float) ($validated['w_variety'] ?? 1),
            'eligibility' => (float) ($validated['w_eligibility'] ?? 1),
            'distance' => (float) ($validated['w_distance'] ?? 1),
        ];

        $lat = isset($validated['latitude']) ? (float) $validated['latitude'] : null;
        $lon = isset($validated['longitude']) ? (float) $validated['longitude'] : null;

        $data = $this->generateRanking($weights, $lat, $lon);

        return view('home.topsis', [
            'weights' => [
                'w_popularity' => $weights['popularity'],
                'w_variety' => $weights['variety'],
                'w_eligibility' => $weights['eligibility'],
                'w_distance' => $weights['distance'],
            ],
            'results' => $data['results'],
            'colleges' => $data['colleges'],
            'latitude' => $lat,
            'longitude' => $lon,
            'includedCriteria' => $data['includedCriteria'],
        ]);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371.0; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1r = deg2rad($lat1);
        $lat2r = deg2rad($lat2);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1r) * cos($lat2r) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function generateRanking(array $weights, ?float $lat, ?float $lon): array
    {
        $student = null;
        $studentGpa = null;
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $studentRecord = Students::find($student->id);
            $studentGpa = $studentRecord?->gpa;
            if ($studentGpa !== null) {
                $studentGpa = (float) $studentGpa;
            }
        }

        $colleges = College::with(['courseDetails.course'])
            ->where('status', 'APPROVED')
            ->get();

        $inquiryCounts = Inquiry::select('college_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('college_id')
            ->pluck('cnt', 'college_id');

        $alternativeIds = [];
        $decisionMatrix = [];
        $rawValues = [];

        foreach ($colleges as $college) {
            $alternativeIds[] = $college->id;

            $popularity = (int) ($inquiryCounts[$college->id] ?? 0);
            $variety = (int) $college->courseDetails->count();

            $eligibility = 0.0;
            if ($studentGpa !== null) {
                $eligible = 0; $considered = 0;
                foreach ($college->courseDetails as $cd) {
                    $limit = $cd->course?->gpa_limit;
                    if ($limit !== null) {
                        $considered++;
                        if ((float) $limit <= $studentGpa) {
                            $eligible++;
                        }
                    }
                }
                $eligibility = $considered > 0 ? $eligible / $considered : 0.0;
            } else {
                $eligibility = 0.0;
            }

            $distance = null;
            if ($lat !== null && $lon !== null && $college->latitude !== null && $college->longitude !== null) {
                $distance = $this->haversineDistance((float)$lat, (float)$lon, (float)$college->latitude, (float)$college->longitude);
            }

            $decisionMatrix[] = [
                $popularity,   // benefit
                $variety,      // benefit
                $eligibility,  // benefit
                $distance,     // cost (may be null)
            ];

            $rawValues[] = [
                'popularity' => $popularity,
                'variety' => $variety,
                'eligibility' => $eligibility,
                'distance' => $distance,
            ];
        }

        $weightsVector = [
            $weights['popularity'],
            $weights['variety'],
            $weights['eligibility'],
            $weights['distance'],
        ];
        $criteriaTypes = ['benefit', 'benefit', 'benefit', 'cost'];

        $ranked = $this->topsisService->rankAlternatives($alternativeIds, $decisionMatrix, $weightsVector, $criteriaTypes);

        $idToCollege = [];
        foreach ($colleges as $c) { $idToCollege[$c->id] = $c; }

        $resultsDecorated = [];
        foreach ($ranked['results'] as $row) {
            $college = $idToCollege[$row['id']] ?? null;
            if ($college) {
                $resultsDecorated[] = [
                    'college' => $college,
                    'score' => $row['score'],
                    'rank' => $row['rank'],
                    'sPlus' => $row['sPlus'],
                    'sMinus' => $row['sMinus'],
                    'values' => $rawValues[array_search($row['id'], $alternativeIds, true)] ?? null,
                ];
            }
        }

        return [
            'results' => $resultsDecorated,
            'colleges' => $colleges,
            'includedCriteria' => $ranked['meta']['includedCriteria'] ?? [],
        ];
    }
}