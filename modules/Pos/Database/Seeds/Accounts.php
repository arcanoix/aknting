<?php

namespace Modules\Pos\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use Illuminate\Database\Seeder;

class Accounts extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        Account::firstOrCreate(
            [
                'company_id'    => $company_id,
                'name'          => trans('pos::settings.accounts.cash'),
                'enabled'       => '1',
                'currency_code' => setting('default.currency'),
            ],
            [
                'number' => '',
            ]
        );

        Account::firstOrCreate(
            [
                'company_id'    => $company_id,
                'name'          => trans('pos::settings.accounts.bank'),
                'enabled'       => '1',
                'currency_code' => setting('default.currency'),
            ],
            [
                'number' => '',
            ]
        );
    }
}
