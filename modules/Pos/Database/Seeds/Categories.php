<?php

namespace Modules\Pos\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Setting\Category;
use Illuminate\Database\Seeder;

class Categories extends Seeder
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

        $categories = [
            [
                'company_id' => $company_id,
                'name'       => trans('pos::settings.categories.pos_sale'),
                'type'       => 'income',
                'color'      => '#096F1B',
                'enabled'    => '1',
            ],
            [
                'company_id' => $company_id,
                'name'       => trans('pos::settings.categories.pos_change'),
                'type'       => 'expense',
                'color'      => '#9D0477',
                'enabled'    => '1',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
