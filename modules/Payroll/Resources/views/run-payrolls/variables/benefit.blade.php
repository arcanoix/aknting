<tr class="row" id="benefit-row-{{ $benefit_row }}">
    @stack('actions_td_start')
        <td class="text-center" style="vertical-align: middle;">
            @stack('actions_button_start')
                <button type="button" data-toggle="tooltip" title="{{ trans('general.save') }}" class="btn btn-xs btn-success button-benefit"><i class="fa fa-save"></i></button>
            @stack('actions_button_end')
        </td>
    @stack('actions_td_end')

    @stack('name_td_start')
        <td {!! $errors->has('benefit.' . $benefit_row . '.name') ? 'class="has-error"' : ''  !!}>
            @stack('name_input_start')
                {!! Form::select('benefit[' . $benefit_row . '][type]', $types, null, array_merge(['id' => 'benefit-type-' . $benefit_row, 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.types', 1)])])) !!}
                <input value="{{ empty($benefit) ? '' : $benefit->id }}" name="benefit[{{ $benefit_row }}][id]" type="hidden" id="benefit-id-{{ $benefit_row }}">
                {!! $errors->first('benefit.' . $benefit_row . '.name', '<p class="help-block">:message</p>') !!}
            @stack('name_input_end')
        </td>
    @stack('name_td_end')

    @stack('amount_td_start')
        <td {{ $errors->has('benefit.' . $benefit_row . 'amount') ? 'class="has-error"' : '' }}>
            @stack('amount_input_start')
                <input value="{{ empty($benefit) ? '' : $benefit->amount }}" class="form-control text-right input-amount" required="required" name="benefit[{{ $benefit_row }}][amount]" type="text" id="benefit-amount-{{ $benefit_row }}">
                <input value="{{ $currency->code }}" name="benefit[{{ $benefit_row }}][currency]" type="hidden" id="benefit-currency-{{ $benefit_row }}">
                {!! $errors->first('benefit.' . $benefit_row . 'amount', '<p class="help-block">:message</p>') !!}
            @stack('amount_input_end')
        </td>
    @stack('amount_td_end')
</tr>
