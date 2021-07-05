<?php

namespace Modules\Payroll\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Modules\Payroll\Models\Setting\PayItem;

class PayItems extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createPayItem();

        Model::reguard();
    }

    private function createPayItem()
    {
        $company_id = $this->command->argument('company');

        $methods = [
            //Benefit
            [
                'company_id' => $company_id,
                'pay_type' => 'benefit',
                'pay_item' => trans('payroll::benefits.bonus'),
                'amount_type' => 'addition',
                'code' => 'bonus',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'benefit',
                'pay_item' => trans('payroll::benefits.commission'),
                'amount_type' => 'addition',
                'code' => 'commission',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'benefit',
                'pay_item' => trans('payroll::benefits.allowance'),
                'amount_type' => 'addition',
                'code' => 'allowance',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'benefit',
                'pay_item' => trans('payroll::benefits.benefit'),
                'amount_type' => 'addition',
                'code' => 'benefit',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'benefit',
                'pay_item' => trans('payroll::benefits.reimbursement'),
                'amount_type' => 'addition',
                'code' => 'reimbursement',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'benefit',
                'pay_item' => trans('payroll::benefits.dismissal'),
                'amount_type' => 'addition',
                'code' => 'dismissal',
            ],

            //Deduction
            [
                'company_id' => $company_id,
                'pay_type' => 'deduction',
                'pay_item' => trans('payroll::deductions.provident'),
                'amount_type' => 'subtraction',
                'code' => 'provident',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'deduction',
                'pay_item' => trans('payroll::deductions.loan'),
                'amount_type' => 'subtraction',
                'code' => 'loan',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'deduction',
                'pay_item' => trans('payroll::deductions.advancepay'),
                'amount_type' => 'subtraction',
                'code' => 'advancepay',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'deduction',
                'pay_item' => trans('payroll::deductions.advance'),
                'amount_type' => 'subtraction',
                'code' => 'advance',
            ],
            [
                'company_id' => $company_id,
                'pay_type' => 'deduction',
                'pay_item' => trans('payroll::deductions.miscelleneous'),
                'amount_type' => 'subtraction',
                'code' => 'miscelleneous',
            ]
        ];

        foreach ($methods as $method) {
            PayItem::firstOrCreate($method);
        }
    }
}
