<?php

namespace Modules\CreditDebitNotes\Database\Factories;

use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentReceived;
use App\Events\Document\DocumentSent;
use App\Events\Document\DocumentViewed;
use App\Events\Document\PaymentReceived;
use App\Jobs\Document\UpdateDocument;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use App\Traits\Documents;
use App\Utilities\Overrider;
use Database\Factories\Document as DocumentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CreditDebitNotes\Models\CreditNote as Model;

class CreditNote extends DocumentFactory
{
    use Documents;

    protected $model = Model::class;

    public function definition(): array
    {
        $contact = Contact::customer()->enabled()->inRandomOrder()->first();
        if (!$contact) {
            $contact = Contact::factory()->customer()->enabled()->create();
        }

        $statuses = ['draft', 'sent', 'viewed', 'cancelled'];

        return array_merge(parent::definition(), [
            'type'               => 'credit-note',
            'document_number'    => $this->getNextDocumentNumber('credit-note'),
            'category_id'        => $this->company->categories()->income()->inRandomOrder()->pluck('id')->first(),
            'contact_id'         => $contact->id,
            'contact_name'       => $contact->name,
            'contact_email'      => $contact->email,
            'contact_tax_number' => $contact->tax_number,
            'contact_phone'      => $contact->phone,
            'contact_address'    => $contact->address,
            'status'             => $this->faker->randomElement($statuses),
        ]);
    }

    /**
     * Indicate that the model has a related invoice.
     */
    public function withInvoice(): Factory
    {
        $invoice = Document::invoice()->where('status', 'sent')->inRandomOrder()->first();
        if (!$invoice) {
            $invoice = Document::factory()->invoice()->sent()->create();
        }

        return $this->state([
            'invoice_id'         => $invoice->id,
            'contact_id'         => $invoice->contact_id,
            'contact_name'       => $invoice->contact->name,
            'contact_email'      => $invoice->contact->email,
            'contact_tax_number' => $invoice->contact->tax_number,
            'contact_phone'      => $invoice->contact->phone,
            'contact_address'    => $invoice->contact->address,
        ]);
    }

    public function creditCustomerAccount(): Factory
    {
        return $this->state([
            'credit_customer_account' => true,
        ]);
    }

    public function dontCreditCustomerAccount(): Factory
    {
        return $this->state([
            'credit_customer_account' => false,
        ]);
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): Factory
    {
        return $this->afterCreating(function (Model $document) {
            Overrider::load('currencies');

            $init_status = $document->status;

            $document->status = 'draft';
            event(new DocumentCreated($document, request()));
            $document->status = $init_status;

            $amount = $this->faker->randomFloat(2, 1, 1000);

            $taxes = Tax::enabled()->get();

            if ($taxes->count()) {
                $tax = $taxes->random(1)->first();
            } else {
                $tax = Tax::factory()->enabled()->create();
            }

            $items = Item::enabled()->get();

            if ($items->count()) {
                $item = $items->random(1)->first();
            } else {
                $item = Item::factory()->enabled()->create();
            }

            $items = [
                [
                    'name'        => $item->name,
                    'description' => $this->faker->text,
                    'item_id'     => $item->id,
                    'tax_ids'     => [$tax->id],
                    'quantity'    => '1',
                    'price'       => $amount,
                    'currency'    => $document->currency_code,
                ]
            ];

            $request = [
                'items'                   => $items,
                'recurring_frequency'     => 'no',
                'credit_customer_account' => $this->faker->randomElement([0, 1]),
            ];

            $updated_document = $this->dispatch(new UpdateDocument($document, $request));

            switch ($init_status) {
                case 'received':
                    event(new DocumentReceived($updated_document));

                    break;
                case 'sent':
                    event(new DocumentSent($updated_document));

                    break;
                case 'viewed':
                    $updated_document->status = 'sent';
                    event(new DocumentViewed($updated_document));
                    $updated_document->status = $init_status;

                    break;
                case 'partial':
                case 'paid':
                    $payment_request = [
                        'paid_at' => $updated_document->due_at,
                        'type'    => config('type.' . $document->type . '.transaction_type'),
                    ];

                    if ($init_status === 'partial') {
                        $payment_request['amount'] = (int)round($amount / 3, $document->currency->precision);
                    }

                    event(new PaymentReceived($updated_document, $payment_request));

                    break;
                case 'cancelled':
                    event(new DocumentCancelled($updated_document));

                    break;
            }
        });
    }
}
