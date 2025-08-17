<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\College;
use App\Models\Students;

class StableMatchingController extends Controller
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

    private function buildCollegeVectors($colleges)
    {
        $vectors = [];
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
            $vectors[$college->id] = $this->calculateTFIDF($tf, $idf);
        }
        return $vectors;
    }

    private function runStableMatching(array $studentIds, array $collegeIds, array $studentPrefs, array $collegePrefs, array $collegeCapacities)
    {
        $freeStudents = $studentIds;
        $nextProposalIndex = array_fill_keys($studentIds, 0);

        $assignments = [];
        foreach ($collegeIds as $cid) {
            $assignments[$cid] = [];
        }

        $collegeRank = [];
        foreach ($collegeIds as $cid) {
            $collegeRank[$cid] = [];
            if (isset($collegePrefs[$cid])) {
                foreach ($collegePrefs[$cid] as $rank => $sid) {
                    $collegeRank[$cid][$sid] = $rank; // lower is better
                }
            }
        }

        while (!empty($freeStudents)) {
            $studentId = array_shift($freeStudents);
            $prefs = $studentPrefs[$studentId] ?? [];
            $proposalIndex = $nextProposalIndex[$studentId];

            if ($proposalIndex >= count($prefs)) {
                continue; // exhausted preferences
            }

            $collegeId = $prefs[$proposalIndex];
            $nextProposalIndex[$studentId] = $proposalIndex + 1;

            if (!isset($assignments[$collegeId])) {
                $assignments[$collegeId] = [];
            }

            $current = $assignments[$collegeId];
            $capacity = (int) ($collegeCapacities[$collegeId] ?? 0);

            if (count($current) < $capacity) {
                $assignments[$collegeId][] = $studentId;
            } else {
                // Find the least preferred among current + new student according to college's ranking
                $cRanking = $collegeRank[$collegeId] ?? [];
                $candidates = $current;
                $candidates[] = $studentId;

                usort($candidates, function ($a, $b) use ($cRanking) {
                    $ra = $cRanking[$a] ?? PHP_INT_MAX;
                    $rb = $cRanking[$b] ?? PHP_INT_MAX;
                    if ($ra === $rb) {
                        return 0;
                    }
                    return ($ra < $rb) ? -1 : 1; // lower rank is better
                });

                $kept = array_slice($candidates, 0, $capacity);
                $evicted = array_values(array_diff($candidates, $kept));

                $assignments[$collegeId] = $kept;

                foreach ($evicted as $e) {
                    $freeStudents[] = $e;
                }
            }

            // If student still has colleges to propose and not assigned anywhere, they will be processed again
            if ($nextProposalIndex[$studentId] < count($prefs) && !in_array($studentId, $assignments[$collegeId], true)) {
                $freeStudents[] = $studentId;
            }
        }

        return $assignments;
    }

    public function index(Request $request)
    {
        $defaultCapacity = (int) $request->input('capacity', 3);
        $minContentThreshold = (float) $request->input('min_content_similarity', 0.0); // optional threshold

        $students = Students::all();
        $colleges = College::where('status', 'APPROVED')
            ->with(['courseDetails.course'])
            ->get();

        if ($students->count() === 0 || $colleges->count() === 0) {
            return view('home.matching', [
                'assignments' => [],
                'collegesById' => [],
                'studentsById' => [],
                'capacityMap' => [],
                'unmatched' => $students,
                'defaultCapacity' => $defaultCapacity,
                'meta' => [ 'note' => 'Insufficient data to run allocation' ],
            ]);
        }

        $collegeVectors = $this->buildCollegeVectors($colleges);

        $studentsById = [];
        $studentVectors = [];
        foreach ($students as $s) {
            $studentsById[$s->id] = $s;
            $studentVectors[$s->id] = $this->buildStudentVector($s);
        }

        $collegesById = [];
        foreach ($colleges as $c) {
            $collegesById[$c->id] = $c;
        }

        // Derive preferences based on content similarity
        $studentPrefs = [];
        foreach ($students as $s) {
            $scores = [];
            foreach ($colleges as $c) {
                $sim = $this->cosineSimilarity($studentVectors[$s->id] ?? [], $collegeVectors[$c->id] ?? []);
                if ($sim >= $minContentThreshold) {
                    $scores[$c->id] = $sim;
                } else {
                    $scores[$c->id] = 0.0;
                }
            }
            arsort($scores, SORT_NUMERIC);
            $studentPrefs[$s->id] = array_keys($scores);
        }

        $collegePrefs = [];
        foreach ($colleges as $c) {
            $scores = [];
            foreach ($students as $s) {
                $sim = $this->cosineSimilarity($studentVectors[$s->id] ?? [], $collegeVectors[$c->id] ?? []);
                $scores[$s->id] = $sim;
            }
            arsort($scores, SORT_NUMERIC);
            $collegePrefs[$c->id] = array_keys($scores);
        }

        // Capacities (uniform or derived). Using uniform default capacity for clarity.
        $capacityMap = [];
        foreach ($colleges as $c) {
            $capacityMap[$c->id] = max(0, $defaultCapacity);
        }

        $studentIds = array_map(function ($s) { return $s->id; }, $students->all());
        $collegeIds = array_map(function ($c) { return $c->id; }, $colleges->all());

        $assignments = $this->runStableMatching($studentIds, $collegeIds, $studentPrefs, $collegePrefs, $capacityMap);

        $assignedStudentIds = [];
        foreach ($assignments as $cid => $sids) {
            foreach ($sids as $sid) { $assignedStudentIds[$sid] = true; }
        }
        $unmatched = [];
        foreach ($students as $s) {
            if (!isset($assignedStudentIds[$s->id])) {
                $unmatched[] = $s;
            }
        }

        return view('home.matching', [
            'assignments' => $assignments,
            'collegesById' => $collegesById,
            'studentsById' => $studentsById,
            'capacityMap' => $capacityMap,
            'unmatched' => $unmatched,
            'defaultCapacity' => $defaultCapacity,
            'meta' => [ 'note' => 'Stable matching (Gale–Shapley) using content-based preferences' ],
        ]);
    }
}