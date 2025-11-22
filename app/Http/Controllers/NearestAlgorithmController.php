<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NearestAlgorithmController extends Controller
{
    public function index(Request $request)
    {
        // If the page is opened without coordinates, show empty results;
        // the Blade will auto-detect location and resubmit.
        return view('home.nearest', [
            'nearestColleges'  => collect(),
            'shouldAutoLocate' => true,
        ]);
    }

    /**
     * Haversine distance (km)
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // Use IUGG mean Earth radius for better accuracy (km)
        $earthRadius = 6371.0088;

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
     * Vincenty inverse formula for distance on WGS84 ellipsoid.
     * Returns distance in meters. Fallback to haversine if not converged.
     */
    private function vincentyDistanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // Short-circuit identical points
        if (abs($lat1 - $lat2) < 1e-12 && abs($lon1 - $lon2) < 1e-12) {
            return 0.0;
        }

        // WGS84 ellipsoid constants
        $a = 6378137.0;           // semi-major axis (m)
        $f = 1 / 298.257223563;   // flattening
        $b = (1 - $f) * $a;       // semi-minor axis

        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $L = deg2rad($lon2 - $lon1);

        $U1 = atan((1 - $f) * tan($phi1));
        $U2 = atan((1 - $f) * tan($phi2));
        $sinU1 = sin($U1); $cosU1 = cos($U1);
        $sinU2 = sin($U2); $cosU2 = cos($U2);

        $lambda = $L;
        $lambdaPrev = 0;
        $iterLimit = 200;

        while ($iterLimit-- > 0) {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);
            $sinSigma = sqrt(
                ($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
                ( ($cosU1 * $sinU2) - ($sinU1 * $cosU2 * $cosLambda) ) *
                ( ($cosU1 * $sinU2) - ($sinU1 * $cosU2 * $cosLambda) )
            );
            if ($sinSigma == 0) return 0.0; // co-incident
            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - $sinAlpha * $sinAlpha;
            $cos2SigmaM = $cosSqAlpha == 0 ? 0 : ($cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha);
            $C = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
            $lambdaPrev = $lambda;
            $lambda = $L + (1 - $C) * $f * $sinAlpha * (
                $sigma + $C * $sinSigma * (
                    $cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM)
                )
            );
            if (abs($lambda - $lambdaPrev) < 1e-12) {
                break;
            }
        }

        // If not converged, fallback to haversine
        if ($iterLimit <= 0) {
            return $this->haversineDistance($lat1, $lon1, $lat2, $lon2) * 1000.0;
        }

        $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
        $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
        $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
        $deltaSigma = $B * $sinSigma * (
            $cos2SigmaM + $B / 4 * (
                $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -
                $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)
            )
        );
        $s = $b * $A * ($sigma - $deltaSigma); // meters

        return $s;
    }
    public function findNearestCollege(Request $request)
    {
        // Strict validation to block junk coordinates
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $userLat = (float) $validated['latitude'];
        $userLon = (float) $validated['longitude'];
        $algo = strtolower(trim((string) $request->input('algo', ''))); // 'haversine' to force haversine
        if (!$this->isWithinNepal($userLat, $userLon)) {
            // Outside Nepal: return empty set (enforces Nepal-only behavior)
            return view('home.nearest', [
                'nearestColleges'  => collect(),
                'shouldAutoLocate' => false,
            ]);
        }

        $colleges = DB::table('colleges')
            ->select('id','name','address','logo','latitude','longitude','status')
            ->where('status', 'APPROVED')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()

            // Filter out malformed or placeholder coords like 0,0
            ->filter(function ($c) {
                if (!is_numeric($c->latitude) || !is_numeric($c->longitude)) return false;
                $lat = (float) $c->latitude;
                $lon = (float) $c->longitude;
                if ($lat === 0.0 && $lon === 0.0) return false;
                return $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180;
            })

            // Compute distance for each college (allow forcing Haversine)
            ->map(function ($college) use ($userLat, $userLon, $algo) {
                $collegeLat = (float) $college->latitude;
                $collegeLon = (float) $college->longitude;

                if ($algo === 'haversine' || $algo === 'h') {
                    $distanceMeters = $this->haversineDistance($userLat, $userLon, $collegeLat, $collegeLon) * 1000.0;
                } else {
                    $distanceMeters = $this->vincentyDistanceMeters($userLat, $userLon, $collegeLat, $collegeLon);
                }
                // ensure a clean integer value for display while keeping accuracy
                $college->distance = (int) round($distanceMeters);

                return $college;
            })

            ->sortBy('distance')
            ->values();

        return view('home.nearest', [
            'nearestColleges'  => $colleges,
            'shouldAutoLocate' => false,
        ]);
    }

    private function isWithinNepal(float $lat, float $lon): bool
    {
        $minLat = 26.347;  // south
        $maxLat = 30.447;  // north
        $minLon = 80.0586; // west
        $maxLon = 88.2015; // east
        return $lat >= $minLat && $lat <= $maxLat && $lon >= $minLon && $lon <= $maxLon;
    }
}
