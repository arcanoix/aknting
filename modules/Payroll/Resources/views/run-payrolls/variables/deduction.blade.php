<tr id="deduction-row-{{ $deduction_row }}">
    @stack('actions_td_start')
        <td class="text-center" style="vertical-align: middle;">
            @stack('actions_button_start')
                <button type="button" data-toggle="tooltip" title="{{ trans('general.save') }}" class="btn btn-xs btn-success button-deduction"><i class="fa fa-save"></i></button>
            @stack('actions_button_end')
        </td>
    @stack('actions_td_end')

    @stack('name_td_start')
        <td {!! $errors->has('deduction.' . $deduction_row . '.name') ? 'class="has-error"' : ''  !!}>
            @stack('name_input_start')
                {!! Form::select('deduction[' . $deduction_row . '][type]', $types, null, array_merge(['id' => 'deduction-type-' . $deduction_row, 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.types', 1)])])) !!}
                <input value="{{ empty($deduction) ? '' : $deduction->id }}" name="deduction[{{ $deduction_row }}][id]" type="hidden" id="deduction-id-{{ $deduction_row }}">
                {!! $errors->first('deduction.' . $deduction_row . '.name', '<p class="help-block">:message</p>') !!}
            @stack('name_input_end')
        </td>
    @stack('name_td_end')

    @stack('amount_td_start')
        <td {{ $errors->has('deduction.' . $deduction_row . 'amount') ? 'class="has-error"' : '' }}>
            @stack('amount_input_start')
                <input value="{{ empty($deduction) ? '' : $deduction->amount }}" class="form-control text-right input-amount" required="required" name="deduction[{{ $deduction_row }}][amount]" type="text" id="deduction-amount-{{ $deduction_row }}">
                <input value="{{ $currency->code }}" name="deduction[{{ $deduction_row }}][currency]" type="hidden" id="deduction-currency-{{ $deduction_row }}">
                {!! $errors->first('deduction.' . $deduction_row . 'amount', '<p class="help-block">:message</p>') !!}
            @stack('amount_input_end')
        </td>
    @stack('amount_td_end')
</tr>
