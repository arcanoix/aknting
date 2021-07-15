<?php

namespace Modules\Pos\Database\Seeds;

use App\Abstracts\Model;
use App\Traits\Permissions as Helper;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{
    use Helper;

    public $alias = 'pos';

    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-main' => 'r',
            $this->alias . '-settings' => 'r,u',
            $this->alias . '-orders' => 'c,r,u,d',
        ]);
    }
}
