<?php

namespace Modules\Pos\Jobs;

use App\Abstracts\Job;
use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Documents;
use Modules\Pos\Models\Order;

class CreateOrder extends Job
{
    use Documents;

    protected $order;

    protected $request;

    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    public function handle()
    {
        $this->prepareRequest();

        \DB::transaction(function () {
            $this->order = $this->dispatch(new CreateDocument($this->request));

            $this->dispatch(new CreateOrderTransactions($this->order, $this->request));
        });

        return $this->order;
    }

    protected function prepareRequest()
    {
        $currency_code = setting('default.currency');
        $contact = Contact::find($this->request['customer_id'] ?? setting('pos.general.guest_customer_id'));

        $this->request['currency_code'] = $currency_code;
        $this->request['currency_rate'] = config('money.' . $currency_code . '.rate');
        $this->request['document_number'] = $this->getNextDocumentNumber(Order::TYPE);
        $this->request['contact_id'] = $contact->id;
        $this->request['contact_name'] = $contact->name;
        $this->request['contact_email'] = $contact->email;
        $this->request['contact_tax_number'] = $contact->tax_number;
        $this->request['contact_phone'] = $contact->phone;
        $this->request['contact_address'] = $contact->address;
        $this->request['category_id'] = setting('pos.general.sale_category_id');
    }
}
