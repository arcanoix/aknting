@extends('layouts.signed')

@section('title', trans_choice('credit-debit-notes::general.debit_notes', 1) . ': ' . $debit_note->debit_note_number)

@section('new_button')
    <a href="{{ route('portal.dashboard') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-user"></span> &nbsp;{{ trans('credit-debit-notes::debit_notes.all_debit_notes') }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header status-{{ $debit_note->status_label }}">
            <h3 class="text-white mb-0 float-right pr-4">{{ trans('credit-debit-notes::debit_notes.statuses.' . $debit_note->status) }}</h3>
        </div>

        <div class="card-body">
            <div class="row mx--4">
                <div class="col-md-7 border-bottom-1">
                    <div class="table-responsive mt-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        <img src="{{ $logo }}" alt="{{ setting('company.name') }}"/>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-5 border-bottom-1">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ setting('company.name') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {!! nl2br(setting('company.address')) !!}
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if (setting('company.tax_number'))
                                            {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}<br>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if (setting('company.phone'))
                                            {{ setting('company.phone') }}<br>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {{ setting('company.email') }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('credit-debit-notes::debit_notes.debit_note_to') }}
                                        <strong class="d-block">{{ $debit_note->contact_name }}</strong>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {!! nl2br($debit_note->contact_address) !!}
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if ($debit_note->contact_tax_number)
                                            {{ trans('general.tax_number') }}: {{ $debit_note->contact_tax_number }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if ($debit_note->contact_phone)
                                            {{ $debit_note->contact_phone }}<br>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {{ $debit_note->contact_email }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>{{ trans('credit-debit-notes::debit_notes.debit_note_number') }}:</th>
                                    <td class="text-right">{{ $debit_note->debit_note_number }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('credit-debit-notes::debit_notes.debit_note_date') }}:</th>
                                    <td class="text-right">@date($debit_note->issued_at)</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('credit-debit-notes::debit_notes.bill_number') }}:</th>
                                    <td class="text-right">
                                        {{ $debit_note->bill->bill_number }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row show-table">
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr class="row">
                                <th class="col-xs-4 col-sm-5 pl-5">{{ trans_choice('general.items', 1) }}</th>
                                <th class="col-xs-4 col-sm-1 text-center">{{ trans('credit-debit-notes::debit_notes.quantity') }}</th>
                                <th class="col-sm-3 text-right d-none d-sm-block">{{ trans('credit-debit-notes::debit_notes.price') }}</th>
                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('credit-debit-notes::debit_notes.total') }}</th>
                            </tr>
                            @foreach($debit_note->items as $debit_note_item)
                                <tr class="row">
                                    <td class="col-xs-4 col-sm-5 pl-5">
                                        {{ $debit_note_item->name }}
                                        @if (!empty($debit_note_item->item->description))
                                            <br><small class="text-pre-nowrap">{!! \Illuminate\Support\Str::limit($debit_note_item->item->description, 500) !!}<small>
                                        @endif
                                    </td>
                                    <td class="col-xs-4 col-sm-1 text-center">{{ $debit_note_item->quantity }}</td>
                                    <td class="col-sm-3 text-right d-none d-sm-block">@money($debit_note_item->price, $debit_note->currency_code, true)</td>
                                    <td class="col-xs-4 col-sm-3 text-right pr-5">@money($debit_note_item->total, $debit_note->currency_code, true)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        @if ($debit_note->notes)
                                            <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                            <p class="text-muted long-texts">{!! nl2br($debit_note->notes) !!}</p>
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach($debit_note->totals_sorted as $total)
                                    @if($total->code != 'total')
                                        <tr>
                                            <th>{{ trans($total['name']) }}:</th>
                                            <td class="text-right">@money($total->amount, $debit_note->currency_code, true)</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>{{ trans($total['name']) }}:</th>
                                            <td class="text-right">@money($total->amount, $debit_note->currency_code, true)</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col text-right">
                    <a href="{{ $print_action }}" target="_blank" class="btn btn-success header-button-top">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <a href="{{ $pdf_action }}" class="btn btn-white header-button-top" data-toggle="tooltip" title="{{ trans('credit-debit-notes::debit_notes.download_pdf') }}">
                        <i class="fa fa-file-pdf"></i>&nbsp; {{ trans('general.download') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
