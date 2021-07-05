<?php

namespace Modules\Payroll\Database\Seeds;

use App\Abstracts\Model;
use App\Utilities\Overrider;
use App\Models\Common\Company;
use App\Models\Setting\Category;
use Illuminate\Database\Seeder;

class Settings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $old_company_id = company_id();

        $company_id = $this->command->argument('company');

        // Get First Expenses Category
        company($company_id)->makeCurrent();
        $category = Category::enabled()->type('expense')->orderBy('name')->first();

        $account = setting('default.account');

        setting()->set('payroll.run_payroll_prefix', 'PR-');
        setting()->set('payroll.run_payroll_next', '1');
        setting()->set('payroll.run_payroll_digit', '5');
        setting()->set('payroll.category', $category->id);
        setting()->set('payroll.payment_method', 'offline-payments.cash.1');
        setting()->set('payroll.account', $account);
        setting()->save();

        company($old_company_id)->makeCurrent();
    }
}
