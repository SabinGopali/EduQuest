<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\College;
use App\Models\Inquiry;
use App\Models\Students;

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

    public function studentIndex(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            return redirect('/student/login');
        }

        $requestedK = (int) ($request->input('k', 4));
        if ($requestedK < 1) { $requestedK = 1; }

        $weights = [
            'content' => (float) ($request->input('w_content', 1.0)),
            'eligibility' => (float) ($request->input('w_eligibility', 1.0)),
            'popularity' => (float) ($request->input('w_popularity', 1.0)),
            'proximity' => (float) ($request->input('w_proximity', 1.0)),
        ];

        $lat = $request->input('latitude');
        $lon = $request->input('longitude');
        $hasLocation = is_numeric($lat) && is_numeric($lon);
        if (!$hasLocation) {
            $lat = null; $lon = null;
            $weights['proximity'] = 0.0;
        } else {
            $lat = (float) $lat; $lon = (float) $lon;
        }

        $student = Auth::guard('student')->user();
        $student = Students::find($student->id);
        $studentVector = $this->buildStudentVector($student);
        $studentGpa = null;
        if ($student && $student->gpa !== null) {
            $studentGpa = (float) $student->gpa;
        }

        $colleges = College::with(['courseDetails.course'])
            ->where('status', 'APPROVED')
            ->get();

        $inquiryCounts = Inquiry::select('college_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('college_id')
            ->pluck('cnt', 'college_id');

        $rawItems = [];
        foreach ($colleges as $college) {
            $combinedText = '';
            foreach ($college->courseDetails as $detail) {
                if ($detail->course && !empty($detail->course->description)) {
                    $combinedText .= ' ' . (string) $detail->course->description;
                }
            }
            $terms = $this->tokenizeAndPreprocess($combinedText);
            $tf = $this->calculateTF($terms);
            $idf = array_fill_keys(array_keys($tf), 1);
            $collegeVector = $this->calculateTFIDF($tf, $idf);
            $content = $this->cosineSimilarity($studentVector, $collegeVector);
            if (!is_finite($content) || $content < 0) { $content = 0.0; }
            if ($content > 1) { $content = 1.0; }

            $eligibility = 0.0;
            if ($studentGpa !== null) {
                $eligible = 0; $considered = 0;
                foreach ($college->courseDetails as $cd) {
                    $limit = $cd->course?->gpa_limit;
                    if ($limit !== null) {
                        $considered++;
                        if ((float) $limit <= $studentGpa) { $eligible++; }
                    }
                }
                $eligibility = $considered > 0 ? $eligible / $considered : 0.0;
            }

            $pop = (int) ($inquiryCounts[$college->id] ?? 0);

            $distance = null;
            if ($hasLocation && !empty($college->latitude) && !empty($college->longitude)) {
                $distance = $this->haversineDistance(
                    (float) $lat,
                    (float) $lon,
                    (float) $college->latitude,
                    (float) $college->longitude
                );
            }

            $rawItems[] = [
                'college' => $college,
                'content' => max(0.0, min(1.0, $content)),
                'eligibility' => max(0.0, min(1.0, $eligibility)),
                'pop' => $pop,
                'distance' => $distance,
            ];
        }

        $popValues = array_map(function ($r) { return $r['pop']; }, $rawItems);
        $minPop = count($popValues) ? min($popValues) : 0;
        $maxPop = count($popValues) ? max($popValues) : 0;

        $distanceValues = array_values(array_filter(array_map(function ($r) { return $r['distance']; }, $rawItems), function ($v) {
            return $v !== null;
        }));
        $maxDistance = count($distanceValues) ? max($distanceValues) : null;

        $points = [];
        foreach ($rawItems as $r) {
            $popNorm = 0.0;
            if ($maxPop > $minPop) {
                $popNorm = ($r['pop'] - $minPop) / ($maxPop - $minPop);
            }
            $proxScore = 0.0;
            if ($weights['proximity'] > 0 && $r['distance'] !== null && $maxDistance && $maxDistance > 0) {
                $ratio = min(1.0, max(0.0, $r['distance'] / $maxDistance));
                $proxScore = 1.0 - $ratio;
            }

            $points[] = [
                'id' => $r['college']->id,
                'name' => $r['college']->name,
                'address' => $r['college']->address,
                'logo' => $r['college']->logo,
                'lat' => $r['college']->latitude,
                'lon' => $r['college']->longitude,
                'raw' => [
                    'content' => $r['content'],
                    'eligibility' => $r['eligibility'],
                    'popularity' => $popNorm,
                    'proximity' => $proxScore,
                ],
                'features' => [
                    $weights['content'] * $r['content'],
                    $weights['eligibility'] * $r['eligibility'],
                    $weights['popularity'] * $popNorm,
                    $weights['proximity'] * $proxScore,
                ],
            ];
        }

        $numPoints = count($points);
        $effectiveK = min(max(1, $requestedK), max(1, $numPoints));

        $result = [
            'centroids' => [],
            'assignments' => [],
            'iterations' => 0,
            'sse' => 0.0,
        ];
        if ($numPoints > 0) {
            $result = $this->runKMeansND($points, $effectiveK, 100);
        }

        $clustersView = [];
        for ($i = 0; $i < $effectiveK; $i++) {
            $clustersView[$i] = [
                'centroidFeatures' => $result['centroids'][$i] ?? [],
                'centroidGeo' => [ 'lat' => null, 'lon' => null ],
                'colleges' => [],
            ];
        }

        $clusterCounts = array_fill(0, $effectiveK, 0);
        $clusterGeoSums = array_fill(0, $effectiveK, ['lat' => 0.0, 'lon' => 0.0, 'cnt' => 0]);
        foreach ($result['assignments'] as $idx => $clusterId) {
            $pt = $points[$idx];
            $clustersView[$clusterId]['colleges'][] = (object) [
                'id' => $pt['id'],
                'name' => $pt['name'],
                'address' => $pt['address'],
                'logo' => $pt['logo'],
                'latitude' => $pt['lat'],
                'longitude' => $pt['lon'],
                'content' => $pt['raw']['content'],
                'eligibility' => $pt['raw']['eligibility'],
                'popularity' => $pt['raw']['popularity'],
                'proximity' => $pt['raw']['proximity'],
            ];
            $clusterCounts[$clusterId]++;
            if (is_numeric($pt['lat']) && is_numeric($pt['lon'])) {
                $clusterGeoSums[$clusterId]['lat'] += (float) $pt['lat'];
                $clusterGeoSums[$clusterId]['lon'] += (float) $pt['lon'];
                $clusterGeoSums[$clusterId]['cnt']++;
            }
        }

        for ($c = 0; $c < $effectiveK; $c++) {
            if ($clusterGeoSums[$c]['cnt'] > 0) {
                $clustersView[$c]['centroidGeo']['lat'] = $clusterGeoSums[$c]['lat'] / $clusterGeoSums[$c]['cnt'];
                $clustersView[$c]['centroidGeo']['lon'] = $clusterGeoSums[$c]['lon'] / $clusterGeoSums[$c]['cnt'];
            }
        }

        return view('home.kmeans_student', [
            'k' => $effectiveK,
            'requestedK' => $requestedK,
            'clusters' => $clustersView,
            'iterations' => $result['iterations'],
            'sse' => $result['sse'],
            'totalColleges' => $numPoints,
            'weights' => $weights,
            'latitude' => $lat,
            'longitude' => $lon,
        ]);
    }

    private function runKMeansND(array $points, int $k, int $maxIterations = 100): array
    {
        $n = count($points);
        $dim = count($points[0]['features']);
        $indices = range(0, $n - 1);
        shuffle($indices);
        $centroids = [];
        for ($i = 0; $i < $k; $i++) {
            $centroids[$i] = $points[$indices[$i]]['features'];
        }

        $assignments = array_fill(0, $n, -1);
        $iterations = 0;

        for ($iter = 0; $iter < $maxIterations; $iter++) {
            $changed = false;

            for ($i = 0; $i < $n; $i++) {
                $bestCluster = 0;
                $bestDist = PHP_FLOAT_MAX;
                for ($c = 0; $c < $k; $c++) {
                    $d = $this->squaredDistanceND($points[$i]['features'], $centroids[$c]);
                    if ($d < $bestDist) { $bestDist = $d; $bestCluster = $c; }
                }
                if ($assignments[$i] !== $bestCluster) {
                    $assignments[$i] = $bestCluster;
                    $changed = true;
                }
            }

            $sums = [];
            $counts = [];
            for ($c = 0; $c < $k; $c++) {
                $sums[$c] = array_fill(0, $dim, 0.0);
                $counts[$c] = 0;
            }
            for ($i = 0; $i < $n; $i++) {
                $cid = $assignments[$i];
                for ($d = 0; $d < $dim; $d++) {
                    $sums[$cid][$d] += $points[$i]['features'][$d];
                }
                $counts[$cid]++;
            }
            for ($c = 0; $c < $k; $c++) {
                if ($counts[$c] > 0) {
                    for ($d = 0; $d < $dim; $d++) {
                        $centroids[$c][$d] = $sums[$c][$d] / $counts[$c];
                    }
                } else {
                    $ri = rand(0, $n - 1);
                    $centroids[$c] = $points[$ri]['features'];
                }
            }

            $iterations = $iter + 1;
            if (!$changed) { break; }
        }

        $sse = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $cid = $assignments[$i];
            $sse += $this->squaredDistanceND($points[$i]['features'], $centroids[$cid]);
        }

        return [
            'centroids' => $centroids,
            'assignments' => $assignments,
            'iterations' => $iterations,
            'sse' => $sse,
        ];
    }

    private function squaredDistanceND(array $a, array $b): float
    {
        $sum = 0.0;
        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $d = $a[$i] - $b[$i];
            $sum += $d * $d;
        }
        return $sum;
    }

    private function tokenizeAndPreprocess($text)
    {
        $text = (string) ($text ?? '');
        $words = str_word_count($text, 1);
        $terms = [];
        foreach ($words as $word) {
            $term = strtolower($word);
            $term = preg_replace("/[^a-zA-Z]+/", "", $term);
            if (!empty($term)) {
                $terms[] = $term;
            }
        }
        $stopwords = [
            "a", "an", "and", "are", "as", "at", "be", "by", "for", "from", "has", "in",
            "is", "it", "its", "of", "on", "that", "the", "to", "was", "were", "will",
            "with", "want", "become", "am", "those", "these"
        ];
        return array_values(array_filter($terms, function ($term) use ($stopwords) {
            return !in_array($term, $stopwords);
        }));
    }

    private function calculateTF(array $terms)
    {
        $termFrequency = [];
        $totalTerms = count($terms);
        foreach ($terms as $term) {
            if (isset($termFrequency[$term])) {
                $termFrequency[$term]++;
            } else {
                $termFrequency[$term] = 1;
            }
        }
        foreach ($termFrequency as &$tf) {
            $tf /= max(1, $totalTerms);
        }
        return $termFrequency;
    }

    private function calculateTFIDF(array $tf, array $idf)
    {
        $tfidf = [];
        foreach ($tf as $term => $tfScore) {
            $tfidf[$term] = $tfScore * ($idf[$term] ?? 1);
        }
        return $tfidf;
    }

    private function cosineSimilarity(array $vectorA, array $vectorB)
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        foreach ($vectorA as $term => $tfidfA) {
            if (isset($vectorB[$term])) {
                $dotProduct += $tfidfA * $vectorB[$term];
            }
            $normA += $tfidfA * $tfidfA;
        }
        foreach ($vectorB as $tfidfB) {
            $normB += $tfidfB * $tfidfB;
        }
        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }
        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    private function buildStudentVector(?Students $student)
    {
        if ($student === null) {
            return [];
        }
        $interestTerms = $this->tokenizeAndPreprocess($student->interest ?? '');
        $goalTerms = $this->tokenizeAndPreprocess($student->goal ?? '');
        $tfProfile = $this->calculateTF($interestTerms);
        $tfGoal = $this->calculateTF($goalTerms);
        $combinedTF = array_merge($tfProfile, $tfGoal);
        $combinedIDF = array_fill_keys(array_keys($combinedTF), 1);
        return $this->calculateTFIDF($combinedTF, $combinedIDF);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1r = deg2rad($lat1);
        $lat2r = deg2rad($lat2);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1r) * cos($lat2r) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}