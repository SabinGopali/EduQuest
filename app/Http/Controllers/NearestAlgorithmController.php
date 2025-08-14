<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NearestAlgorithmController extends Controller
{
    public function index(){
        return view('home.nearest', ['colleges' => collect()]);
    }
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }

    // Main function to find the nearest college
    public function findNearestCollege(Request $request)
    {
        // Validate the input
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $addressInput = $request->addressInput;
        $latitude = (float) $request->input('latitude');
        $longitude = (float) $request->input('longitude');
        $radius = 50; // Radius in kilometers

        // Use robust Haversine formula with clamped acos to avoid domain errors
        $colleges = DB::table('colleges')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw(
                "*, (6371 * acos(least(1, greatest(-1, cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))))) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->limit(6)
            ->get();

        // Pass the results to the view
        return view('home.nearest', [
            'colleges' => $colleges,
        ]);
    }


}