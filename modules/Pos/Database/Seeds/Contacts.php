<?php

namespace Modules\Pos\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Contact;
use Illuminate\Database\Seeder;

class Contacts extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        Contact::firstOrCreate([
            'company_id'    => $this->command->argument('company'),
            'type'          => 'customer',
            'name'          => trans('pos::settings.general.guest_customer'),
            'currency_code' => setting('default.currency'),
            'enabled'       => true,
        ]);
    }
}
