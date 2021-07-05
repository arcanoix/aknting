<div class="border-top-dashed py-2">
    <strong class="float-left">{{ trans_choice('credit-debit-notes::invoices.credits', 2) }}:</strong>
    <span>- @money($applied_credits, $invoice->currency_code, true)</span>
</div>
