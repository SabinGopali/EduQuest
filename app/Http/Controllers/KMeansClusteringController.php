<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\College;

class KMeansClusteringController extends Controller
{
    /**
     * GET /kmeans
     * Clusters approved colleges by latitude/longitude using K-Means.
     */
    public function index(Request $request)
    {
        $requestedK = (int) ($request->input('k', 4));
        if ($requestedK < 1) { $requestedK = 1; }

        // Fetch candidates
        $colleges = College::query()
            ->select('id','name','address','logo','latitude','longitude','status')
            ->where('status', 'APPROVED')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($c) {
                if (!is_numeric($c->latitude) || !is_numeric($c->longitude)) return false;
                $lat = (float) $c->latitude; $lon = (float) $c->longitude;
                if ($lat === 0.0 && $lon === 0.0) return false;
                return $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180;
            })
            ->values();

        $points = [];
        foreach ($colleges as $c) {
            $points[] = [
                'id' => $c->id,
                'name' => $c->name,
                'address' => $c->address,
                'logo' => $c->logo,
                'lat' => (float) $c->latitude,
                'lon' => (float) $c->longitude,
            ];
        }

        $numPoints = count($points);
        $effectiveK = min(max(1, $requestedK), max(1, $numPoints));

        $result = [
            'clusters' => [],
            'centroids' => [],
            'assignments' => [],
            'iterations' => 0,
            'sse' => 0.0,
        ];

        if ($numPoints > 0) {
            $result = $this->runKMeans($points, $effectiveK, 100);
        }

        // Build clusters with college objects for the view
        $clustersView = [];
        for ($i = 0; $i < $effectiveK; $i++) {
            $clustersView[$i] = [
                'centroid' => $result['centroids'][$i] ?? ['lat' => null, 'lon' => null],
                'colleges' => [],
            ];
        }

        foreach ($result['assignments'] as $idx => $clusterId) {
            $pt = $points[$idx];
            $clustersView[$clusterId]['colleges'][] = (object) [
                'id' => $pt['id'],
                'name' => $pt['name'],
                'address' => $pt['address'],
                'logo' => $pt['logo'],
                'latitude' => $pt['lat'],
                'longitude' => $pt['lon'],
            ];
        }

        return view('home.kmeans', [
            'k' => $effectiveK,
            'requestedK' => $requestedK,
            'clusters' => $clustersView,
            'iterations' => $result['iterations'],
            'sse' => $result['sse'],
            'totalColleges' => $numPoints,
        ]);
    }

    private function runKMeans(array $points, int $k, int $maxIterations = 100): array
    {
        // Initialize centroids by sampling k distinct points
        $n = count($points);
        $indices = range(0, $n - 1);
        shuffle($indices);
        $centroids = [];
        for ($i = 0; $i < $k; $i++) {
            $p = $points[$indices[$i]];
            $centroids[$i] = ['lat' => $p['lat'], 'lon' => $p['lon']];
        }

        $assignments = array_fill(0, $n, -1);
        $iterations = 0;

        for ($iter = 0; $iter < $maxIterations; $iter++) {
            $changed = false;

            // Assignment step
            for ($i = 0; $i < $n; $i++) {
                $p = $points[$i];
                $bestCluster = 0;
                $bestDist = PHP_FLOAT_MAX;
                for ($c = 0; $c < $k; $c++) {
                    $d = $this->squaredDistance($p['lat'], $p['lon'], $centroids[$c]['lat'], $centroids[$c]['lon']);
                    if ($d < $bestDist) { $bestDist = $d; $bestCluster = $c; }
                }
                if ($assignments[$i] !== $bestCluster) {
                    $assignments[$i] = $bestCluster;
                    $changed = true;
                }
            }

            // Update step
            $sums = [];
            $counts = [];
            for ($c = 0; $c < $k; $c++) { $sums[$c] = ['lat' => 0.0, 'lon' => 0.0]; $counts[$c] = 0; }
            for ($i = 0; $i < $n; $i++) {
                $cid = $assignments[$i];
                $sums[$cid]['lat'] += $points[$i]['lat'];
                $sums[$cid]['lon'] += $points[$i]['lon'];
                $counts[$cid]++;
            }
            for ($c = 0; $c < $k; $c++) {
                if ($counts[$c] > 0) {
                    $centroids[$c]['lat'] = $sums[$c]['lat'] / $counts[$c];
                    $centroids[$c]['lon'] = $sums[$c]['lon'] / $counts[$c];
                } else {
                    // Reinitialize empty cluster to a random point
                    $ri = rand(0, $n - 1);
                    $centroids[$c]['lat'] = $points[$ri]['lat'];
                    $centroids[$c]['lon'] = $points[$ri]['lon'];
                }
            }

            $iterations = $iter + 1;
            if (!$changed) { break; }
        }

        // Compute SSE
        $sse = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $cid = $assignments[$i];
            $sse += $this->squaredDistance(
                $points[$i]['lat'], $points[$i]['lon'],
                $centroids[$cid]['lat'], $centroids[$cid]['lon']
            );
        }

        return [
            'centroids' => $centroids,
            'assignments' => $assignments,
            'iterations' => $iterations,
            'sse' => $sse,
        ];
    }

    private function squaredDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $dLat = $lat1 - $lat2;
        $dLon = $lon1 - $lon2;
        return $dLat * $dLat + $dLon * $dLon;
    }
}