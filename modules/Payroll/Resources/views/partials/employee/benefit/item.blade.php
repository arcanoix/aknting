<tr class="row" v-for="(row, index) in benefits" :index="index">
    @stack('actions_td_start')
        <td class="col-md-2 border-left-0 border-right-0 border-bottom-0 hidden-xs text-center">
            @stack('actions_button_start')
                <button v-if="row.id"
                    type="button"
                    data-toggle="tooltip"
                    title="{{ trans('general.delete') }}"
                    class="btn btn-icon btn-danger btn-lg"
                    @click="deleteBenefit(row.id)">
                        <i class="fa fa-trash"></i>
                </button>
                <button v-else
                    type="button"
                    data-toggle="tooltip"
                    title="{{ trans('general.save') }}"
                    class="btn btn-icon btn-success btn-lg"
                    @click="saveBenefit(row)">
                        <i class="fa fa-save"></i>
                </button>
            @stack('actions_button_end')
        </td>
    @stack('actions_td_end')

    @stack('type_td_start')
        <td class="col-md-7 border-right-0 border-bottom-0 pb-0 hidden-xs text-left" >
            @stack('type_input_start')
                <div v-if="row.id" style="padding-top: 0.8125rem">
                    @{{ row.name }}
                </div>
                <akaunting-select v-else
                    class="col-md-12"
                    :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('payroll::general.benefits', 1)]) }}'"
                    :name="'benefit_type_' + index"
                    :options="{{ json_encode($benefit_type) }}"
                    :value="row.type"
                    @interface="row.type = $event"
                ></akaunting-select>
            @stack('type_input_end')
        </td>
    @stack('type_td_end')

    @stack('amount_td_start')
        <td class="col-md-3 border-right-0 border-bottom-0 pb-0 hidden-xs text-right">
            @stack('amount_input_start')
            <div v-if="row.id" style="padding-top: 0.8125rem">
                @{{ row.amount_format }}
            </div>
            <akaunting-money v-else
                col="text-right input-price"
                :name="'benefit_amount' + index"
                required
                v-model="row.amount"
                @interface="row.amount = $event"
                :error="form.errors.get('benefit_amount.' + index)"
                :currency="row.currency"
                :dynamic-currency="row.currency"
            ></akaunting-money>
            <input :name="'benefit_currency.' + index"
                   data-item="currency"
                   v-model="row.currency"
                   type="hidden">
            @stack('amount_input_end')
        </td>
    @stack('amount_td_end')
</tr>
