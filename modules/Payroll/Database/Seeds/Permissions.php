<?php

namespace Modules\Payroll\Database\Seeds;

use App\Abstracts\Model;
use App\Traits\Permissions as Helper;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{
    use Helper;

    public $alias = 'payroll';

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
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-payroll'       => 'c,r,u,d',
            $this->alias . '-employees'     => 'c,r,u,d',
            $this->alias . '-pay-calendars' => 'c,r,u,d',
            $this->alias . '-run-payrolls'  => 'c,r,u,d',
            $this->alias . '-settings'      => 'r,u',
        ]);

        $rows = [
            'employee' => [
                $this->alias . '-widgets-paychecks-received' => 'r',
                $this->alias . '-widgets-total-salary'       => 'r',
                $this->alias . '-widgets-total-benefits'     => 'r',
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);
    }
}
