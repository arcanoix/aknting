<?php

namespace Modules\Payroll\Database\Seeds;

use Illuminate\Database\Seeder;

class Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Widgets::class);
        $this->call(PayItems::class);
        $this->call(Reports::class);
        $this->call(Settings::class);
        $this->call(Permissions::class);
    }
}
