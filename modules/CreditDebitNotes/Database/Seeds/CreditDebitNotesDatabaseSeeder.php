<?php

namespace Modules\CreditDebitNotes\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class CreditDebitNotesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(EmailTemplates::class);

        Model::reguard();
    }
}
