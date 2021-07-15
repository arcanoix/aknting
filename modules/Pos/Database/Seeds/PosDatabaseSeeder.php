<?php

namespace Modules\Pos\Database\Seeds;

use Illuminate\Database\Seeder;

class PosDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Permissions::class);
        $this->call(Accounts::class);
        $this->call(Categories::class);
        $this->call(Contacts::class);
        $this->call(Settings::class);
        $this->call(EmailTemplates::class);
    }
}
