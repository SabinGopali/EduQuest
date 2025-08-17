<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextRankController extends Controller
{
    public function index()
    {
        return view('home.summarize', [
            'input' => '',
            'sentences' => 3,
            'summary' => [],
        ]);
    }

    public function summarize(Request $request)
    {
        $text = (string) $request->input('text', '');
        $numSentences = (int) $request->input('sentences', 3);
        $numSentences = max(1, min(8, $numSentences));

        $summary = $this->runTextRankSummarization($text, $numSentences);

        return view('home.summarize', [
            'input' => $text,
            'sentences' => $numSentences,
            'summary' => $summary,
        ]);
    }

    private function runTextRankSummarization(string $text, int $k): array
    {
        $sentences = $this->splitIntoSentences($text);
        $n = count($sentences);
        if ($n === 0) {
            return [];
        }
        if ($k >= $n) {
            return $sentences; // nothing to rank
        }

        $tokens = [];
        for ($i = 0; $i < $n; $i++) {
            $tokens[$i] = $this->tokenize($sentences[$i]);
        }

        $weights = array_fill(0, $n, array_fill(0, $n, 0.0));
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) { continue; }
                $sim = $this->sentenceSimilarity($tokens[$i], $tokens[$j]);
                $weights[$i][$j] = $sim;
            }
        }

        $scores = $this->pageRank($weights, 0.85, 40, 1e-6);

        $indices = range(0, $n - 1);
        usort($indices, function ($a, $b) use ($scores) {
            if ($scores[$a] === $scores[$b]) return 0;
            return ($scores[$a] < $scores[$b]) ? 1 : -1;
        });
        $top = array_slice($indices, 0, $k);
        sort($top); // keep original order for readability

        $summary = [];
        foreach ($top as $idx) {
            $summary[] = $sentences[$idx];
        }
        return $summary;
    }

    private function splitIntoSentences(string $text): array
    {
        $text = trim($text);
        if ($text === '') { return []; }

        $parts = preg_split('/(?<=[.!?])\s+/u', $text);
        $sentences = [];
        foreach ($parts as $s) {
            $s = trim($s);
            if ($s === '') { continue; }
            if (mb_strlen($s) < 4) { continue; }
            $sentences[] = $s;
        }
        return $sentences;
    }

    private function tokenize(string $sentence): array
    {
        $sentence = mb_strtolower($sentence, 'UTF-8');
        $sentence = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $sentence);
        $words = preg_split('/\s+/u', $sentence, -1, PREG_SPLIT_NO_EMPTY);

        $stop = $this->getStopwords();
        $tokens = [];
        foreach ($words as $w) {
            if (isset($stop[$w])) { continue; }
            $tokens[] = $w;
        }
        return $tokens;
    }

    private function sentenceSimilarity(array $a, array $b): float
    {
        if (empty($a) || empty($b)) { return 0.0; }

        $freqA = array_count_values($a);
        $freqB = array_count_values($b);
        $vocab = array_values(array_unique(array_merge(array_keys($freqA), array_keys($freqB))));

        $dot = 0.0; $normA = 0.0; $normB = 0.0;
        foreach ($vocab as $t) {
            $va = (float) ($freqA[$t] ?? 0);
            $vb = (float) ($freqB[$t] ?? 0);
            $dot += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }
        if ($normA <= 0.0 || $normB <= 0.0) { return 0.0; }
        return $dot / (sqrt($normA) * sqrt($normB));
    }

    private function pageRank(array $weights, float $damping = 0.85, int $maxIter = 40, float $tol = 1e-6): array
    {
        $n = count($weights);
        if ($n === 0) { return []; }

        $score = array_fill(0, $n, 1.0 / $n);
        $outSums = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            $sum = 0.0;
            for ($j = 0; $j < $n; $j++) { $sum += $weights[$i][$j]; }
            $outSums[$i] = $sum;
        }

        for ($iter = 0; $iter < $maxIter; $iter++) {
            $new = array_fill(0, $n, (1.0 - $damping) / $n);

            $dangling = 0.0;
            for ($i = 0; $i < $n; $i++) { if ($outSums[$i] <= 0.0) { $dangling += $score[$i]; } }
            $danglingContribution = $damping * $dangling / $n;

            for ($j = 0; $j < $n; $j++) {
                if ($outSums[$j] > 0.0) {
                    $contrib = $damping * $score[$j] / $outSums[$j];
                    for ($i = 0; $i < $n; $i++) {
                        if ($weights[$j][$i] > 0.0) {
                            $new[$i] += $contrib * $weights[$j][$i];
                        }
                    }
                }
            }

            for ($i = 0; $i < $n; $i++) { $new[$i] += $danglingContribution; }

            $delta = 0.0;
            for ($i = 0; $i < $n; $i++) { $delta += abs($new[$i] - $score[$i]); }
            $score = $new;
            if ($delta < $tol) { break; }
        }

        return $score;
    }

    private function getStopwords(): array
    {
        $list = [
            'a','an','and','are','as','at','be','by','for','from','has','in','is','it','its','of','on','that','the','to','was','were','will','with','want','become','am','those','these','i','you','he','she','they','we','me','my','our','your','yours','their','them','or','but','if','then','so','because','about','into','over','after','before','up','down','out','off','here','there'
        ];
        $map = [];
        foreach ($list as $w) { $map[$w] = true; }
        return $map;
    }
}