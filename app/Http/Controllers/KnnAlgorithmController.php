<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KnnAlgorithmController extends Controller
{
    /**
     * Show the KNN page with optional results.
     */
    public function index(Request $request)
    {
        return view('home.knn', [
            'colleges' => collect(),
            'k' => 5,
        ]);
    }

    /**
     * Haversine distance (km)
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371.0088; // km (IUGG mean Earth radius)

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // distance in km
    }

    /**
     * Vincenty inverse formula distance (meters) on WGS84; fallback to haversine on non-convergence.
     */
    private function vincentyDistanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        if (abs($lat1 - $lat2) < 1e-12 && abs($lon1 - $lon2) < 1e-12) {
            return 0.0;
        }
        $a = 6378137.0;
        $f = 1 / 298.257223563;
        $b = (1 - $f) * $a;

        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $L = deg2rad($lon2 - $lon1);

        $U1 = atan((1 - $f) * tan($phi1));
        $U2 = atan((1 - $f) * tan($phi2));
        $sinU1 = sin($U1); $cosU1 = cos($U1);
        $sinU2 = sin($U2); $cosU2 = cos($U2);

        $lambda = $L;
        $lambdaPrev = 0.0;
        $iterLimit = 200;
        $sinSigma = 0.0; $cosSigma = 0.0; $sigma = 0.0; $sinAlpha = 0.0; $cosSqAlpha = 0.0; $cos2SigmaM = 0.0;

        while ($iterLimit-- > 0) {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);
            $sinSigma = sqrt(
                ($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
                (($cosU1 * $sinU2) - ($sinU1 * $cosU2 * $cosLambda)) *
                (($cosU1 * $sinU2) - ($sinU1 * $cosU2 * $cosLambda))
            );
            if ($sinSigma == 0.0) return 0.0;
            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cosSqAlpha = 1.0 - $sinAlpha * $sinAlpha;
            $cos2SigmaM = $cosSqAlpha == 0.0 ? 0.0 : ($cosSigma - 2.0 * $sinU1 * $sinU2 / $cosSqAlpha);
            $C = $f / 16.0 * $cosSqAlpha * (4.0 + $f * (4.0 - 3.0 * $cosSqAlpha));
            $lambdaPrev = $lambda;
            $lambda = $L + (1.0 - $C) * $f * $sinAlpha * (
                $sigma + $C * $sinSigma * (
                    $cos2SigmaM + $C * $cosSigma * (-1.0 + 2.0 * $cos2SigmaM * $cos2SigmaM)
                )
            );
            if (abs($lambda - $lambdaPrev) < 1e-12) break;
        }
        if ($iterLimit <= 0) {
            return $this->haversineDistance($lat1, $lon1, $lat2, $lon2) * 1000.0;
        }
        $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
        $A = 1.0 + $uSq / 16384.0 * (4096.0 + $uSq * (-768.0 + $uSq * (320.0 - 175.0 * $uSq)));
        $B = $uSq / 1024.0 * (256.0 + $uSq * (-128.0 + $uSq * (74.0 - 47.0 * $uSq)));
        $deltaSigma = $B * $sinSigma * (
            $cos2SigmaM + $B / 4.0 * (
                $cosSigma * (-1.0 + 2.0 * $cos2SigmaM * $cos2SigmaM) -
                $B / 6.0 * $cos2SigmaM * (-3.0 + 4.0 * $sinSigma * $sinSigma) * (-3.0 + 4.0 * $cos2SigmaM * $cos2SigmaM)
            )
        );
        $s = $b * $A * ($sigma - $deltaSigma);
        return $s;
    }

    /**
     * Compute K nearest colleges by geospatial distance.
     */
    public function find(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'k' => 'nullable|integer|min:1|max:50',
        ]);

        $userLat = (float) $validated['latitude'];
        $userLon = (float) $validated['longitude'];
        $k = isset($validated['k']) ? (int) $validated['k'] : 5;
        if ($k < 1) { $k = 1; }
        if ($k > 50) { $k = 50; }

        $colleges = DB::table('colleges')
            ->select('id','name','address','logo','latitude','longitude','status')
            ->where('status', 'APPROVED')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($c) {
                if (!is_numeric($c->latitude) || !is_numeric($c->longitude)) return false;
                $lat = (float) $c->latitude;
                $lon = (float) $c->longitude;
                if ($lat === 0.0 && $lon === 0.0) return false;
                return $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180;
            })
            ->map(function ($college) use ($userLat, $userLon) {
                $collegeLat = (float) $college->latitude;
                $collegeLon = (float) $college->longitude;
                // Prefer Vincenty in meters
                $distanceMeters = $this->vincentyDistanceMeters($userLat, $userLon, $collegeLat, $collegeLon);
                $college->distance = (int) round($distanceMeters);
                return $college;
            })
            ->sortBy('distance')
            ->take($k)
            ->values();

        return view('home.knn', [
            'colleges' => $colleges,
            'k' => $k,
        ]);
    }
}