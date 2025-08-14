<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NearestAlgorithmController extends Controller
{
    public function index()
    {
        return view('home.nearest', ['colleges' => collect()]);
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
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

    public function findNearestCollege(Request $request)
{
    $request->validate([
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $userLat = $request->input('latitude');
    $userLon = $request->input('longitude');

    // Fetch all colleges with valid coordinates
    $colleges = DB::table('colleges')
        ->where('status', 'APPROVED')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get()
        ->map(function ($college) use ($userLat, $userLon) {
            // Ensure latitude and longitude are numbers
            $collegeLat = floatval($college->latitude);
            $collegeLon = floatval($college->longitude);

            $college->distance = $this->haversineDistance($userLat, $userLon, $collegeLat, $collegeLon);

            return $college;
        })
        ->sortBy('distance')
        ->values();

    return view('home.nearest', ['colleges' => $colleges]);
}

}
