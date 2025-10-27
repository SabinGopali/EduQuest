<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\College;
use App\Models\Inquiry;
use App\Models\Students;

class HybridRecommendationController extends Controller
{
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
		$dotProduct = 0;
		$normA = 0;
		$normB = 0;
		foreach ($vectorA as $term => $tfidfA) {
			if (isset($vectorB[$term])) {
				$dotProduct += $tfidfA * $vectorB[$term];
			}
			$normA += $tfidfA * $tfidfA;
		}
		foreach ($vectorB as $tfidfB) {
			$normB += $tfidfB * $tfidfB;
		}
		if ($normA == 0 || $normB == 0) {
			return 0.0;
		}
		return $dotProduct / (sqrt($normA) * sqrt($normB));
	}

	private function haversineDistance($lat1, $lon1, $lat2, $lon2)
	{
		$earthRadius = 6371; // km
		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lon2 - $lon1);
		$lat1 = deg2rad($lat1);
		$lat2 = deg2rad($lat2);
		$a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return $earthRadius * $c;
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

	public function index(Request $request)
	{
		$lat = $request->input('latitude');
		$lon = $request->input('longitude');
		$hasLocation = is_numeric($lat) && is_numeric($lon);

		$student = Auth::guard('student')->check() ? Auth::guard('student')->user() : null;
		if ($student instanceof Students) {
			$student = Students::find($student->id);
		} else {
			$student = null;
		}
		$studentVector = $this->buildStudentVector($student);

		$colleges = College::where('status', 'APPROVED')
			->with(['courseDetails.course'])
			->get();

		$inquiryCounts = Inquiry::select('college_id', DB::raw('COUNT(*) as cnt'))
			->groupBy('college_id')
			->pluck('cnt', 'college_id');

		$rawResults = [];
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
			$contentSim = $this->cosineSimilarity($studentVector, $collegeVector);

			$popCount = (int) ($inquiryCounts[$college->id] ?? 0);

			$distance = null;
			// Robust coordinate validation (allow 0.0 values; ignore malformed 0,0 placeholders)
			if ($hasLocation && $college->latitude !== null && $college->longitude !== null && is_numeric($college->latitude) && is_numeric($college->longitude)) {
				$collegeLat = (float) $college->latitude;
				$collegeLon = (float) $college->longitude;
				if (!($collegeLat === 0.0 && $collegeLon === 0.0) && $collegeLat >= -90 && $collegeLat <= 90 && $collegeLon >= -180 && $collegeLon <= 180) {
					$distance = $this->haversineDistance(
						(float) $lat,
						(float) $lon,
						$collegeLat,
						$collegeLon
					);
				}
			}

			$rawResults[] = [
				'college' => $college,
				'content' => max(0.0, min(1.0, $contentSim)),
				'pop' => $popCount,
				'distance' => $distance,
			];
		}

		$maxPop = 0;
		$minPop = 0;
		if (count($rawResults) > 0) {
			$popValues = array_map(function ($r) { return $r['pop']; }, $rawResults);
			$maxPop = max($popValues);
			$minPop = min($popValues);
		}

		$distanceValues = array_values(array_filter(array_map(function ($r) { return $r['distance']; }, $rawResults), function ($v) {
			return $v !== null;
		}));
		$maxDistance = count($distanceValues) ? max($distanceValues) : null;

		$wContent = 0.5;
		$wPop = 0.3;
		$wProx = $hasLocation ? 0.2 : 0.0;
		$wSum = $wContent + $wPop + $wProx;
		if ($wSum > 0) {
			$wContent /= $wSum;
			$wPop /= $wSum;
			$wProx /= $wSum;
		}

		$recommendations = [];
		foreach ($rawResults as $r) {
			$popNorm = 0.0;
			if ($maxPop > $minPop) {
				$popNorm = ($r['pop'] - $minPop) / ($maxPop - $minPop);
			}
			$proxScore = 0.0;
			if ($wProx > 0 && $r['distance'] !== null && $maxDistance && $maxDistance > 0) {
				$ratio = min(1.0, max(0.0, $r['distance'] / $maxDistance));
				$proxScore = 1.0 - $ratio;
			}
			$score = ($wContent * $r['content']) + ($wPop * $popNorm) + ($wProx * $proxScore);
			$recommendations[] = [
				'college' => $r['college'],
				'score' => $score,
				'content' => $r['content'],
				'popularity' => $popNorm,
				'proximity' => $proxScore,
			];
		}

		usort($recommendations, function ($a, $b) {
			if ($a['score'] === $b['score']) {
				return 0;
			}
			return ($a['score'] < $b['score']) ? 1 : -1;
		});

		return view('home.hybrid', [
			'recommendations' => $recommendations,
			'hasLocation' => $hasLocation,
		]);
	}
}