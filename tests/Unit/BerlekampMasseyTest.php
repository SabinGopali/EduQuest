<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Algorithms\BerlekampMassey;

class BerlekampMasseyTest extends TestCase
{
    public function test_fibonacci_coefficients(): void
    {
        $mod = 1000000007;
        $fib = [0, 1, 1, 2, 3, 5, 8, 13, 21, 34];
        $coeffs = BerlekampMassey::minimalRecurrence($fib, $mod);
        $this->assertSame([1, 1], $coeffs);
    }

    public function test_nth_term_prediction(): void
    {
        $mod = 1000000007;
        $coeffs = [3, 5, 7];
        $init = [2, 4, 9];

        // Build a reference sequence to validate nth term
        $seq = $init;
        for ($i = 3; $i < 60; $i++) {
            $next = 0;
            for ($j = 1; $j <= 3; $j++) {
                $next = ($next + $coeffs[$j - 1] * $seq[$i - $j]) % $mod;
            }
            $seq[] = $next;
        }

        $n = 42;
        $pred = BerlekampMassey::nthTerm($init, $coeffs, $n, $mod);
        $this->assertSame($seq[$n], $pred);
    }
}