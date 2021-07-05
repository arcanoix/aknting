<?php

namespace Modules\Employees\Database\Seeds;

use Illuminate\Database\Seeder;

class EmployeesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Permissions::class);
        $this->call(Dashboards::class);
    }
}
