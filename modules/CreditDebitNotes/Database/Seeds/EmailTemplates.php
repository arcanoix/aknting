<?php

namespace Modules\CreditDebitNotes\Database\Seeds;

use App\Models\Common\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'alias' => 'credit_note_new_customer',
                'class' => 'Modules\CreditDebitNotes\Notifications\CreditNote',
                'name' => 'credit-debit-notes::settings.email.templates.credit_note_new_customer',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                [
                    'company_id' => $this->command->argument('company'),
                    'alias' => $template['alias'],
                ],
                [
                    'class' => $template['class'],
                    'name' => $template['name'],
                    'subject' => trans('credit-debit-notes::email_templates.' . $template['alias'] . '.subject'),
                    'body' => trans('credit-debit-notes::email_templates.' . $template['alias'] . '.body'),
                ]
            );
        }
    }
}
