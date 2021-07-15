<?php

namespace Modules\Pos\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplates extends Seeder
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

        $templates = [
            [
                'alias' => 'order_receipt_customer',
                'class' => 'Modules\Pos\Notifications\OrderReceipt',
                'name'  => 'pos::settings.email.templates.order_receipt_customer',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::firstOrCreate(
                [
                    'company_id' => $company_id,
                    'alias'      => $template['alias'],
                    'class'      => $template['class'],
                    'name'       => $template['name'],
                    'subject'    => trans('pos::email_templates.' . $template['alias'] . '.subject'),
                    'body'       => trans('pos::email_templates.' . $template['alias'] . '.body'),
                ]
            );
        }
    }
}
