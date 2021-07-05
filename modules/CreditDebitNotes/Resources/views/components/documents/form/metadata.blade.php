<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
        @if (!$hideContact)
        <div class="row">
            <x-select-contact-card
                type="{{ $contactType }}"
                :contact="$contact"
                :contacts="$contacts"
                :search_route="$contactSearchRoute"
                :create_route="$contactCreateRoute"
                error="form.errors.get('contact_name')"
                :text-add-contact="$textAddContact"
                :text-create-new-contact="$textCreateNewContact"
                :text-edit-contact="$textEditContact"
                :text-contact-info="$textContactInfo"
                :text-choose-different-contact="$textChooseDifferentContact"
            />
        </div>
        @endif
    </div>

    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <div class="row">
            @if (!$hideIssuedAt)
            {{ Form::dateGroup('issued_at', trans($textIssuedAt), 'calendar', ['id' => 'issued_at', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], $issuedAt) }}
            @endif

            @if (!$hideDocumentNumber)
            {{ Form::textGroup('document_number', trans($textDocumentNumber), 'file', ['required' => 'required'], $documentNumber) }}
            @endif

            @if (!$hideDueAt)
            {{ Form::dateGroup('due_at', trans($textDueAt), 'calendar', ['id' => 'due_at', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], $dueAt) }}
            @else
            {{ Form::hidden('due_at', old('issued_at', $issuedAt), ['id' => 'due_at', 'v-model' => 'form.issued_at']) }}
            @endif

            @if (!$hideOrderNumber)
            {{ Form::textGroup('order_number', trans($textOrderNumber), 'shopping-cart', [], $orderNumber) }}
            @endif

            @if ($type === 'debit-note')
            {{ Form::selectGroup(
                'bill_id',
                trans_choice('general.bills', 1),
                'file-invoice-dollar',
                empty($document) ? [] : $document->vendor_bills,
                empty($document) ? '' : $document->bill_id,
                ['dynamicOptions' => 'bills']
            ) }}
            @elseif ($type === 'credit-note')
            {{ Form::selectGroup(
                'invoice_id',
                trans_choice('general.invoices', 1),
                'file-invoice-dollar',
                empty($document) ? [] : $document->customer_invoices,
                empty($document) ? '' : $document->invoice_id,
                ['dynamicOptions' => 'invoices']
            ) }}
            @endif
            {{ Form::hidden('original_contact_id', empty($document) ? null : $contact->id, ['id' => 'original_contact_id']) }}
        </div>
    </div>
</div>
