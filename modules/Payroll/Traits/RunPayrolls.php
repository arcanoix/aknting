<?php

namespace Modules\Payroll\Traits;

trait RunPayrolls
{
    /**
     * Generate next run payroll number
     *
     * @return string
     */
    public function getNextRunPayrollNumber()
    {
        $prefix = setting('payroll.run_payroll_prefix', 'PR-');
        $next = setting('payroll.run_payroll_next', '1');
        $digit = setting('payroll.run_payroll_digit', '5');

        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Increase the next run payroll number
     */
    public function increaseNextRunPayrollNumber()
    {
        // Update next run payroll number
        $next = setting('payroll.run_payroll_next', 1) + 1;

        setting(['payroll.run_payroll_next' => $next]);
        setting()->save();
    }
}
