<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Utilities\Modules;
use Illuminate\Http\JsonResponse;
use Modules\Pos\Http\Requests\Settings as Request;

class Settings extends Controller
{
    public function edit()
    {
        $customers = Contact::customer()
            ->enabled()
            ->orderBy('name')
            ->take(setting('default.select_limit'))
            ->pluck('name', 'id');

        $income_categories = Category::income()
            ->enabled()
            ->orderBy('name')
            ->take(setting('default.select_limit'))
            ->pluck('name', 'id');

        $expense_categories = Category::expense()
            ->enabled()
            ->orderBy('name')
            ->take(setting('default.select_limit'))
            ->pluck('name', 'id');

        $accounts = Account::enabled()
            ->orderBy('name')
            ->take(setting('default.select_limit'))
            ->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('pos::settings', compact(
            'customers',
            'income_categories',
            'expense_categories',
            'accounts',
            'payment_methods'
        ));
    }

    public function update(Request $request): JsonResponse
    {
        $alias = 'pos.pos_order';

        $fields = $request->only(['number_prefix', 'number_digit', 'number_next']);

        foreach ($fields as $key => $value) {
            setting()->set($alias . '.' . $key, $value);
        }

        $alias = 'pos.general';

        $fields = $request->only([
            'cash_account_id',
            'cash_payment_method',
            'card_account_id',
            'card_payment_method',
            'guest_customer_id',
            'sale_category_id',
            'change_category_id',
            'printer_paper_size',
            'use_barcode_scanner',
        ]);

        foreach ($fields as $key => $value) {
            setting()->set($alias . '.' . $key, $value);
        }

        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $response = [
            'status'   => null,
            'success'  => true,
            'error'    => false,
            'message'  => $message,
            'data'     => null,
            'redirect' => route('settings.index')
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
