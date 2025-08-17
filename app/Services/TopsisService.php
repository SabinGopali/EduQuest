<?php

namespace App\Services;

class TopsisService
{
    /**
     * Rank alternatives using the TOPSIS algorithm.
     *
     * @param array $alternativeIds Identifiers of each alternative in the same order as rows in $decisionMatrix
     * @param array $decisionMatrix 2D array [alternativeIndex][criterionIndex] of numeric values (null allowed)
     * @param array $weights Weights per criterion (non-negative). Will be normalized across included criteria
     * @param array $criteriaTypes Array of 'benefit' or 'cost' per criterion
     * @return array Returns an associative array with 'results' (sorted by score desc) and 'meta' (weights, includedCriteria, ideals)
     */
    public function rankAlternatives(array $alternativeIds, array $decisionMatrix, array $weights, array $criteriaTypes): array
    {
        $numAlternatives = count($decisionMatrix);
        if ($numAlternatives === 0) {
            return ['results' => [], 'meta' => ['includedCriteria' => [], 'weights' => [], 'idealBest' => [], 'idealWorst' => []]];
        }

        $numCriteria = count($weights);
        if ($numCriteria === 0 || $numCriteria !== count($criteriaTypes)) {
            throw new \InvalidArgumentException('Weights and criteriaTypes must be non-empty and of equal length.');
        }

        // Ensure matrix is well-formed
        foreach ($decisionMatrix as $row) {
            if (!is_array($row) || count($row) !== $numCriteria) {
                throw new \InvalidArgumentException('Each row in the decision matrix must have the same number of elements as the number of criteria.');
            }
        }

        // Determine criteria to include: non-zero weight and at least one non-null across alternatives
        $included = [];
        for ($j = 0; $j < $numCriteria; $j++) {
            $hasValue = false;
            for ($i = 0; $i < $numAlternatives; $i++) {
                if ($decisionMatrix[$i][$j] !== null) {
                    $hasValue = true;
                    break;
                }
            }
            if (($weights[$j] ?? 0) > 0 && $hasValue) {
                $included[] = $j;
            }
        }

        if (empty($included)) {
            // Fallback: include all criteria with values and assign equal weights
            for ($j = 0; $j < $numCriteria; $j++) {
                $hasValue = false;
                for ($i = 0; $i < $numAlternatives; $i++) {
                    if ($decisionMatrix[$i][$j] !== null) {
                        $hasValue = true;
                        break;
                    }
                }
                if ($hasValue) {
                    $included[] = $j;
                }
            }
            if (empty($included)) {
                // No usable criteria
                return ['results' => [], 'meta' => ['includedCriteria' => [], 'weights' => [], 'idealBest' => [], 'idealWorst' => []]];
            }
            $weights = array_fill(0, $numCriteria, 1);
        }

        // Build column-wise arrays and impute missing values with column mean of non-null
        $columns = [];
        foreach ($included as $j) {
            $values = [];
            $sum = 0.0; $count = 0;
            for ($i = 0; $i < $numAlternatives; $i++) {
                $val = $decisionMatrix[$i][$j];
                if ($val !== null && is_numeric($val)) {
                    $sum += (float) $val;
                    $count++;
                }
            }
            $mean = $count > 0 ? $sum / $count : 0.0;
            for ($i = 0; $i < $numAlternatives; $i++) {
                $val = $decisionMatrix[$i][$j];
                $values[$i] = ($val === null || !is_numeric($val)) ? $mean : (float) $val;
            }
            $columns[$j] = $values;
        }

        // Normalize columns using vector normalization
        $normalized = array_fill(0, $numAlternatives, array_fill(0, count($included), 0.0));
        $colIndex = 0;
        $denoms = [];
        foreach ($included as $j) {
            $squareSum = 0.0;
            foreach ($columns[$j] as $val) {
                $squareSum += $val * $val;
            }
            $denom = $squareSum > 0.0 ? sqrt($squareSum) : 1.0; // avoid divide by zero
            $denoms[$colIndex] = $denom;
            for ($i = 0; $i < $numAlternatives; $i++) {
                $normalized[$i][$colIndex] = $columns[$j][$i] / $denom;
            }
            $colIndex++;
        }

        // Normalize weights for included criteria
        $includedWeights = [];
        $sumW = 0.0;
        foreach ($included as $j) {
            $w = max(0.0, (float) $weights[$j]);
            $includedWeights[] = $w;
            $sumW += $w;
        }
        if ($sumW <= 0.0) {
            // Equal weights
            $includedWeights = array_fill(0, count($included), 1.0);
            $sumW = (float) count($included);
        }
        foreach ($includedWeights as &$w) {
            $w = $w / $sumW;
        }
        unset($w);

        // Weighted normalized matrix
        $weighted = $normalized;
        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($k = 0; $k < count($includedWeights); $k++) {
                $weighted[$i][$k] = $weighted[$i][$k] * $includedWeights[$k];
            }
        }

        // Determine ideal best and worst
        $idealBest = array_fill(0, count($included), 0.0);
        $idealWorst = array_fill(0, count($included), 0.0);
        for ($k = 0; $k < count($included); $k++) {
            $critIdx = $included[$k];
            $type = strtolower((string) $criteriaTypes[$critIdx]);
            $colVals = array_column($weighted, $k);
            $maxVal = max($colVals);
            $minVal = min($colVals);
            if ($type === 'cost') {
                // Cost criterion: best is min, worst is max
                $idealBest[$k] = $minVal;
                $idealWorst[$k] = $maxVal;
            } else {
                // Benefit criterion: best is max, worst is min
                $idealBest[$k] = $maxVal;
                $idealWorst[$k] = $minVal;
            }
        }

        // Separation measures and closeness coefficient
        $results = [];
        for ($i = 0; $i < $numAlternatives; $i++) {
            $sPlusSq = 0.0;
            $sMinusSq = 0.0;
            for ($k = 0; $k < count($included); $k++) {
                $diffPlus = $weighted[$i][$k] - $idealBest[$k];
                $diffMinus = $weighted[$i][$k] - $idealWorst[$k];
                $sPlusSq += $diffPlus * $diffPlus;
                $sMinusSq += $diffMinus * $diffMinus;
            }
            $sPlus = sqrt($sPlusSq);
            $sMinus = sqrt($sMinusSq);
            $den = $sPlus + $sMinus;
            $cc = $den > 0.0 ? $sMinus / $den : 0.0;

            $results[] = [
                'id' => $alternativeIds[$i] ?? $i,
                'score' => $cc,
                'sPlus' => $sPlus,
                'sMinus' => $sMinus,
                'weighted' => $weighted[$i],
            ];
        }

        // Sort by score descending
        usort($results, function ($a, $b) {
            if ($a['score'] === $b['score']) return 0;
            return ($a['score'] < $b['score']) ? 1 : -1;
        });

        // Attach rank
        $rank = 1;
        foreach ($results as &$res) {
            $res['rank'] = $rank++;
        }
        unset($res);

        // Build meta
        $meta = [
            'includedCriteria' => $included,
            'weights' => $includedWeights,
            'idealBest' => $idealBest,
            'idealWorst' => $idealWorst,
        ];

        return ['results' => $results, 'meta' => $meta];
    }
}