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
        $earthRadius = 6371; // km

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
                $distanceKm = $this->haversineDistance($userLat, $userLon, $collegeLat, $collegeLon);
                $college->distance = (int) round($distanceKm * 1000); // meters
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