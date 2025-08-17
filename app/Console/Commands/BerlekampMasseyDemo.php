<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Algorithms\BerlekampMassey;

class BerlekampMasseyDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bm:demo {--mod=1000000007}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demonstrate the Berlekamp–Massey algorithm: infer recurrence and predict terms';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $mod = (int)$this->option('mod');

        $this->line('Berlekamp–Massey demo');
        $this->line("Modulus: {$mod}");

        // Example 1: Fibonacci modulo mod
        $fib = [0, 1];
        for ($i = 2; $i < 12; $i++) {
            $fib[$i] = ($fib[$i - 1] + $fib[$i - 2]) % $mod;
        }

        $coeffsFib = BerlekampMassey::minimalRecurrence($fib, $mod);
        $this->info('Inferred coefficients for Fibonacci (expect [1,1]):');
        $this->line(json_encode($coeffsFib));
        $n = 50;
        $nthFib = BerlekampMassey::nthTerm($fib, $coeffsFib, $n, $mod);
        $this->line("F_{$n} mod {$mod} = {$nthFib}");

        // Example 2: Custom 3-term recurrence: s[n] = 3*s[n-1] + 5*s[n-2] + 7*s[n-3]
        $trueCoeffs = [3, 5, 7];
        $init = [2, 4, 9];
        $seq = $init;
        for ($i = 3; $i < 40; $i++) {
            $next = 0;
            for ($j = 1; $j <= 3; $j++) {
                $next = ($next + $trueCoeffs[$j - 1] * $seq[$i - $j]) % $mod;
            }
            $seq[] = $next;
        }

        $coeffs = BerlekampMassey::minimalRecurrence($seq, $mod);
        $this->info('Inferred coefficients for custom 3-term recurrence (expect [3,5,7] mod mod):');
        $this->line(json_encode($coeffs));

        $n2 = 25;
        $nth = BerlekampMassey::nthTerm($init, $coeffs, $n2, $mod);
        $this->line("s[{$n2}] mod {$mod} = {$nth}");

        $this->line('Done.');
        return 0;
    }
}