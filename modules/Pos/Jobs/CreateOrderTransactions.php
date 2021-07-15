<?php

namespace Modules\Pos\Jobs;

use App\Abstracts\Job;

class CreateOrderTransactions extends Job
{
    protected $order;

    protected $request;

    public function __construct($order, $request)
    {
        $this->order = $order;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle()
    {
        foreach ($this->request['payments'] as $payment) {
            $payment['paid_at'] = $this->order->issued_at;
            $payment['payment_method'] = $payment['type'] === 'cash' ? setting('pos.general.cash_payment_method') : setting('pos.general.card_payment_method');
            $payment['account_id'] = $payment['type'] === 'cash' ? setting('pos.general.cash_account_id') : setting('pos.general.card_account_id');
            $payment['category_id'] = setting('pos.general.sale_category_id');
            $payment['type'] = 'income';

            $this->dispatch(new CreateBankingDocumentTransaction($this->order, $payment));
        }

        if ($this->request['change']) {
            $this->dispatch(new CreateBankingDocumentTransaction($this->order, [
                'paid_at'        => $this->order->issued_at,
                'payment_method' => 'offline-payments.cash.1',
                'account_id'     => setting('pos.general.cash_account_id'),
                'category_id'    => setting('pos.general.sale_category_id'),
                'type'           => 'expense',
                'amount'         => $this->request['change'],
            ]));
        }
    }
}
