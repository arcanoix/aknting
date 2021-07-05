<?php

namespace Modules\CreditDebitNotes\Database\Factories;

use App\Models\Common\Contact;
use App\Models\Document\Document as Model;
use App\Traits\Documents;
use Database\Factories\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebitNote extends Document
{
    use Documents;

    /**
     * Indicate that the model type is debit note.
     */
    public function debitNote(): Factory
    {
        return $this->state(function (array $attributes): array {
            $contact = Contact::vendor()->enabled()->inRandomOrder()->first();
            if (!$contact) {
                $contact = Contact::factory()->vendor()->enabled()->create();
            }

            $statuses = ['draft', 'sent', 'viewed', 'cancelled'];

            return [
                'type'               => 'debit-note',
                'document_number'    => $this->getNextDocumentNumber('debit-note'),
                'category_id'        => $this->company->categories()->expense()->inRandomOrder()->pluck('id')->first(),
                'contact_id'         => $contact->id,
                'contact_name'       => $contact->name,
                'contact_email'      => $contact->email,
                'contact_tax_number' => $contact->tax_number,
                'contact_phone'      => $contact->phone,
                'contact_address'    => $contact->address,
                'status'             => $this->faker->randomElement($statuses),
            ];
        });
    }

    /**
     * Indicate that the model has a related bill.
     */
    public function withBill(): Factory
    {
        $bill = Model::bill()->where('status', 'received')->inRandomOrder()->first();
        if (!$bill) {
            $bill = Model::factory()->bill()->received()->create();
        }

        return $this->state([
            'bill_id'            => $bill->id,
            'contact_id'         => $bill->contact_id,
            'contact_name'       => $bill->contact->name,
            'contact_email'      => $bill->contact->email,
            'contact_tax_number' => $bill->contact->tax_number,
            'contact_phone'      => $bill->contact->phone,
            'contact_address'    => $bill->contact->address,
        ]);
    }
}
