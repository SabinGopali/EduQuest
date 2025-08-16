<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\College;
use App\Models\Course;

class SearchController extends Controller
{
    private function tokenizeAndPreprocess(?string $text): array
    {
        $text = (string)($text ?? '');
        $words = str_word_count($text, 1);
        $terms = [];
        foreach ($words as $word) {
            $term = strtolower($word);
            $term = preg_replace("/[^a-zA-Z0-9]+/", "", $term);
            if ($term !== '') {
                $terms[] = $term;
            }
        }
        $stopwords = [
            'a','an','and','are','as','at','be','by','for','from','has','in','is','it','its','of','on','that','the','to','was','were','will','with','want','become','am','those','these'
        ];
        return array_values(array_filter($terms, function ($t) use ($stopwords) {
            return !in_array($t, $stopwords, true);
        }));
    }

    private function buildCollegeDocumentTokens(College $college, ?string $stream, ?string $subStream, ?float $minGpa, ?string $duration): array
    {
        $parts = [];
        $parts[] = (string)$college->name;
        $parts[] = (string)$college->address;
        $parts[] = (string)$college->description;

        if ($college->courseDetails) {
            foreach ($college->courseDetails as $detail) {
                if (isset($detail->status) && $detail->status !== 'APPROVED') {
                    continue;
                }
                if (!$detail->course) {
                    continue;
                }
                $course = $detail->course;
                if ($stream && $course->stream !== $stream) {
                    continue;
                }
                if ($subStream && $course->subStream !== $subStream) {
                    continue;
                }
                if ($duration && $course->duration !== $duration) {
                    continue;
                }
                if ($minGpa !== null && $minGpa !== 0.0) {
                    $courseGpa = (float)preg_replace('/[^0-9.]/', '', (string)($course->gpa_limit ?? '0'));
                    if (!is_finite($courseGpa) || $courseGpa > $minGpa) {
                        continue;
                    }
                }

                $parts[] = (string)$course->name;
                $parts[] = (string)$course->shortName;
                $parts[] = (string)$course->stream;
                $parts[] = (string)$course->subStream;
                $parts[] = (string)$course->description;
                $parts[] = (string)$detail->description;
            }
        }

        return $this->tokenizeAndPreprocess(implode(' ', array_filter($parts)));
    }

    private function termFrequencies(array $tokens): array
    {
        $tf = [];
        foreach ($tokens as $t) {
            if (!isset($tf[$t])) {
                $tf[$t] = 1;
            } else {
                $tf[$t] += 1;
            }
        }
        return $tf;
    }

    private function documentFrequencies(array $docsTokens): array
    {
        $df = [];
        foreach ($docsTokens as $tokens) {
            $seen = [];
            foreach ($tokens as $t) {
                $seen[$t] = true;
            }
            foreach (array_keys($seen) as $t) {
                if (!isset($df[$t])) {
                    $df[$t] = 1;
                } else {
                    $df[$t] += 1;
                }
            }
        }
        return $df;
    }

    private function averageDocLength(array $docsTokens): float
    {
        $n = count($docsTokens);
        if ($n === 0) {
            return 0.0;
        }
        $sum = 0;
        foreach ($docsTokens as $tokens) {
            $sum += count($tokens);
        }
        return $sum / $n;
    }

    private function bm25Scores(array $docsTokens, array $queryTokens): array
    {
        $N = count($docsTokens);
        if ($N === 0) {
            return [];
        }
        $dfs = $this->documentFrequencies($docsTokens);
        $avgdl = $this->averageDocLength($docsTokens);
        $k1 = 1.5;
        $b = 0.75;

        $queryTerms = array_values(array_unique($queryTokens));
        $scores = [];
        foreach ($docsTokens as $i => $tokens) {
            $tf = $this->termFrequencies($tokens);
            $dl = count($tokens);
            $score = 0.0;
            foreach ($queryTerms as $term) {
                $df = (float)($dfs[$term] ?? 0);
                if (!isset($tf[$term])) {
                    continue;
                }
                $idf = log((($N - $df + 0.5) / ($df + 0.5)) + 1.0);
                $numerator = $tf[$term] * ($k1 + 1.0);
                $denominator = $tf[$term] + $k1 * (1.0 - $b + $b * ($dl / max(1.0, $avgdl)));
                $score += $idf * ($numerator / $denominator);
            }
            $scores[$i] = $score;
        }
        return $scores;
    }

    private function countMatchingCourses(College $college, ?string $stream, ?string $subStream, ?float $minGpa, ?string $duration): int
    {
        $count = 0;
        if (!$college->courseDetails) {
            return 0;
        }
        foreach ($college->courseDetails as $detail) {
            if (isset($detail->status) && $detail->status !== 'APPROVED') {
                continue;
            }
            if (!$detail->course) {
                continue;
            }
            $course = $detail->course;
            if ($stream && $course->stream !== $stream) {
                continue;
            }
            if ($subStream && $course->subStream !== $subStream) {
                continue;
            }
            if ($duration && $course->duration !== $duration) {
                continue;
            }
            if ($minGpa !== null && $minGpa !== 0.0) {
                $courseGpa = (float)preg_replace('/[^0-9.]/', '', (string)($course->gpa_limit ?? '0'));
                if (!is_finite($courseGpa) || $courseGpa > $minGpa) {
                    continue;
                }
            }
            $count++;
        }
        return $count;
    }

    public function index(Request $request)
    {
        $q = (string)$request->input('q', '');
        $stream = $request->input('stream');
        $subStream = $request->input('subStream');
        $minGpaInput = $request->input('min_gpa');
        $duration = $request->input('duration');
        $collegeName = $request->input('college');

        $minGpa = null;
        if ($minGpaInput !== null && $minGpaInput !== '') {
            $minGpa = (float)$minGpaInput;
        }

        $query = College::where('status', 'APPROVED')
            ->with(['courseDetails.course']);

        if ($collegeName) {
            $query->where('name', 'like', '%' . $collegeName . '%');
        }

        $query->whereHas('courseDetails', function ($cdq) use ($stream, $subStream, $minGpa, $duration) {
            $cdq->where(function ($cdq2) {
                $cdq2->whereNull('status')->orWhere('status', 'APPROVED');
            });
            $cdq->whereHas('course', function ($cq) use ($stream, $subStream, $minGpa, $duration) {
                if ($stream) {
                    $cq->where('stream', $stream);
                }
                if ($subStream) {
                    $cq->where('subStream', $subStream);
                }
                if ($duration) {
                    $cq->where('duration', $duration);
                }
                if ($minGpa !== null) {
                    $cq->whereRaw('CAST(gpa_limit AS DECIMAL(5,2)) <= ?', [$minGpa]);
                }
            });
        });

        $colleges = $query->get();

        $docsTokens = [];
        $mapping = [];
        foreach ($colleges as $idx => $college) {
            $tokens = $this->buildCollegeDocumentTokens($college, $stream, $subStream, $minGpa, $duration);
            $docsTokens[] = $tokens;
            $mapping[] = $college;
        }

        $results = [];
        if (trim($q) !== '') {
            $queryTokens = $this->tokenizeAndPreprocess($q);
            $scores = $this->bm25Scores($docsTokens, $queryTokens);
            foreach ($mapping as $i => $college) {
                $results[] = [
                    'college' => $college,
                    'score' => $scores[$i] ?? 0.0,
                ];
            }
            usort($results, function ($a, $b) {
                if ($a['score'] === $b['score']) {
                    return 0;
                }
                return ($a['score'] < $b['score']) ? 1 : -1;
            });
        } else {
            foreach ($mapping as $i => $college) {
                $matchCount = $this->countMatchingCourses($college, $stream, $subStream, $minGpa, $duration);
                $results[] = [
                    'college' => $college,
                    'score' => (float)$matchCount,
                ];
            }
            usort($results, function ($a, $b) {
                if ($a['score'] === $b['score']) {
                    return 0;
                }
                return ($a['score'] < $b['score']) ? 1 : -1;
            });
        }

        $streams = Course::select('stream')->distinct()->orderBy('stream')->pluck('stream');
        $subStreams = Course::select('subStream')->distinct()->orderBy('subStream')->pluck('subStream');
        $durations = Course::select('duration')->distinct()->orderBy('duration')->pluck('duration');

        return view('home.search', [
            'results' => $results,
            'q' => $q,
            'filters' => [
                'stream' => $stream,
                'subStream' => $subStream,
                'min_gpa' => $minGpa,
                'duration' => $duration,
                'college' => $collegeName,
            ],
            'streams' => $streams,
            'subStreams' => $subStreams,
            'durations' => $durations,
        ]);
    }
}