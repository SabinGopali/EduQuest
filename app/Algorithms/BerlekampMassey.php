<?php

namespace App\Algorithms;

class BerlekampMassey
{
    /**
     * Compute the minimal linear recurrence coefficients for a given sequence using the Berlekamp–Massey algorithm.
     *
     * The returned coefficients represent the recurrence:
     *   s[n] = sum_{i=1..L} coeffs[i-1] * s[n-i] (mod mod)
     *
     * @param array<int,int> $sequence Input sequence terms (integers modulo $mod)
     * @param int $mod Modulus for arithmetic; prefer a prime (e.g., 1_000_000_007)
     * @return array<int,int> Coefficients of the minimal recurrence (length L)
     */
    public static function minimalRecurrence(array $sequence, int $mod): array
    {
        $n = count($sequence);
        if ($n === 0) {
            return [];
        }

        // Connection polynomials C(x) and B(x), represented as arrays of coefficients with C[0] = 1
        $C = [1];
        $B = [1];
        $L = 0;       // Current recurrence length
        $m = 1;       // Steps since last update of B
        $b = 1;       // Last non-zero discrepancy

        for ($idx = 0; $idx < $n; $idx++) {
            // Compute discrepancy d = s[idx] + sum_{i=1..L} C[i] * s[idx - i]
            $d = $sequence[$idx] % $mod;
            for ($i = 1; $i <= $L; $i++) {
                $ci = $C[$i] ?? 0;
                $si = $sequence[$idx - $i] ?? 0;
                $d = ($d + $ci * $si) % $mod;
            }
            $d = self::normalizeMod($d, $mod);

            if ($d === 0) {
                $m++;
                continue;
            }

            $T = $C; // Copy of C
            $coef = ($d * self::modInverse($b, $mod)) % $mod;

            // Ensure C has enough length for updates
            $neededLength = count($B) + $m;
            if (count($C) < $neededLength) {
                $C = array_pad($C, $neededLength, 0);
            }

            // C = C - coef * x^m * B
            for ($j = 0; $j < count($B); $j++) {
                $C[$j + $m] = self::normalizeMod($C[$j + $m] - $coef * $B[$j], $mod);
            }

            if (2 * $L <= $idx) {
                $L = $idx + 1 - $L;
                $B = $T;
                $b = $d;
                $m = 1;
            } else {
                $m++;
            }
        }

        // Convert connection polynomial C(x) = 1 + C1 x + C2 x^2 + ...
        // into recurrence s[n] = -C1 s[n-1] - C2 s[n-2] - ... (mod mod)
        $coeffs = [];
        for ($i = 1; $i <= $L; $i++) {
            $ci = $C[$i] ?? 0;
            $coeffs[] = self::normalizeMod(-$ci, $mod);
        }

        return $coeffs;
    }

    /**
     * Predict the nth term (0-indexed) of a linear recurrence using fast matrix exponentiation of the companion matrix.
     *
     * @param array<int,int> $initial First L terms of the sequence
     * @param array<int,int> $coeffs Recurrence coefficients (length L)
     * @param int $n Target index (0-indexed)
     * @param int $mod Modulus
     * @return int s[n] modulo mod
     */
    public static function nthTerm(array $initial, array $coeffs, int $n, int $mod): int
    {
        $L = count($coeffs);
        if ($L === 0) {
            return $initial[0] ?? 0;
        }
        if ($n < $L) {
            return self::normalizeMod($initial[$n], $mod);
        }

        // Build companion matrix of size LxL
        $M = array_fill(0, $L, array_fill(0, $L, 0));
        for ($j = 0; $j < $L; $j++) {
            $M[0][$j] = self::normalizeMod($coeffs[$j], $mod);
        }
        for ($i = 1; $i < $L; $i++) {
            $M[$i][$i - 1] = 1;
        }

        $power = $n - ($L - 1);
        $Mp = self::matrixPower($M, $power, $mod);

        // State vector V_{L-1} = [s[L-1], s[L-2], ..., s[0]]^T
        $V = [];
        for ($i = 0; $i < $L; $i++) {
            $V[$i] = self::normalizeMod($initial[$L - 1 - $i], $mod);
        }

        $resultVec = self::matrixVectorMultiply($Mp, $V, $mod);
        return self::normalizeMod($resultVec[0], $mod);
    }

    /**
     * Convenience: generate next k terms from a given prefix using the recurrence.
     *
     * @param array<int,int> $prefix At least L terms
     * @param array<int,int> $coeffs Length L
     * @param int $k How many terms to append
     * @param int $mod Modulus
     * @return array<int,int> Extended sequence of length count($prefix) + $k
     */
    public static function extendSequence(array $prefix, array $coeffs, int $k, int $mod): array
    {
        $seq = $prefix;
        $L = count($coeffs);
        for ($t = 0; $t < $k; $t++) {
            $nxt = 0;
            $len = count($seq);
            for ($i = 1; $i <= $L; $i++) {
                $nxt = ($nxt + $coeffs[$i - 1] * $seq[$len - $i]) % $mod;
            }
            $seq[] = self::normalizeMod($nxt, $mod);
        }
        return $seq;
    }

    /**
     * Multiply two matrices modulo mod.
     *
     * @param array<int,array<int,int>> $A
     * @param array<int,array<int,int>> $B
     * @param int $mod
     * @return array<int,array<int,int>>
     */
    private static function matrixMultiply(array $A, array $B, int $mod): array
    {
        $n = count($A);
        $m = count($B[0]);
        $p = count($B);
        $C = array_fill(0, $n, array_fill(0, $m, 0));
        for ($i = 0; $i < $n; $i++) {
            for ($k = 0; $k < $p; $k++) {
                if ($A[$i][$k] === 0) continue;
                $aik = $A[$i][$k];
                for ($j = 0; $j < $m; $j++) {
                    if ($B[$k][$j] === 0) continue;
                    $C[$i][$j] = ($C[$i][$j] + $aik * $B[$k][$j]) % $mod;
                }
            }
        }
        return $C;
    }

    /**
     * Raise matrix to a power modulo mod.
     *
     * @param array<int,array<int,int>> $M
     * @param int $e
     * @param int $mod
     * @return array<int,array<int,int>>
     */
    private static function matrixPower(array $M, int $e, int $mod): array
    {
        $n = count($M);
        // Identity matrix
        $R = array_fill(0, $n, array_fill(0, $n, 0));
        for ($i = 0; $i < $n; $i++) {
            $R[$i][$i] = 1;
        }

        $A = $M;
        while ($e > 0) {
            if ($e & 1) {
                $R = self::matrixMultiply($R, $A, $mod);
            }
            $A = self::matrixMultiply($A, $A, $mod);
            $e >>= 1;
        }
        return $R;
    }

    /**
     * Multiply matrix by vector modulo mod.
     *
     * @param array<int,array<int,int>> $M
     * @param array<int,int> $v
     * @param int $mod
     * @return array<int,int>
     */
    private static function matrixVectorMultiply(array $M, array $v, int $mod): array
    {
        $n = count($M);
        $m = count($M[0]);
        $res = array_fill(0, $n, 0);
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $m; $j++) {
                if ($M[$i][$j] === 0 || $v[$j] === 0) continue;
                $sum = ($sum + $M[$i][$j] * $v[$j]) % $mod;
            }
            $res[$i] = $sum;
        }
        return $res;
    }

    private static function normalizeMod(int $x, int $mod): int
    {
        $x %= $mod;
        if ($x < 0) $x += $mod;
        return $x;
    }

    private static function modInverse(int $a, int $mod): int
    {
        $a = self::normalizeMod($a, $mod);
        [$g, $x, ] = self::egcd($a, $mod);
        if ($g !== 1) {
            throw new \InvalidArgumentException("modInverse does not exist for a={$a}, mod={$mod} (gcd={$g})");
        }
        return self::normalizeMod($x, $mod);
    }

    /**
     * Extended Euclidean algorithm.
     * @return array{0:int,1:int,2:int} [g, x, y] such that ax + by = g = gcd(a,b)
     */
    private static function egcd(int $a, int $b): array
    {
        if ($b === 0) {
            return [$a, 1, 0];
        }
        [$g, $x1, $y1] = self::egcd($b, $a % $b);
        $x = $y1;
        $y = $x1 - intdiv($a, $b) * $y1;
        return [$g, $x, $y];
    }
}