<?php

namespace Modules\Payroll\Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll as Model;

class RunPayroll extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    public function definition(): array
    {
        $pay_calendar = PayCalendar::first();
        if (!$pay_calendar) {
            $pay_calendar = PayCalendar::factory()->create();
        }

        $account = Account::find(setting('payroll.account'));
        $category = Category::find(setting('payroll.category'));

        return [
            'company_id'      => $this->company->id,
            'pay_calendar_id' => $pay_calendar->id,
            'account_id'      => $account->id,
            'currency_code'   => $account->currency_code,
            'category_id'     => $category->id,
            'name'            => setting('payroll.run_payroll_prefix') . $this->faker->randomNumber(),
            'from_date'       => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s'),
            'to_date'         => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s'),
            'payment_date'    => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s'),
            'payment_method'  => setting('payroll.payment_method'),
            'currency_rate'   => 1,
            'amount'          => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}
