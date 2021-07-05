<?php

namespace Modules\Payroll\Http\Controllers\Common;

use Artisan;
use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Utilities\Modules;
use Illuminate\Http\JsonResponse;
use Modules\Payroll\Models\Setting\PayItem;
use Modules\Payroll\Http\Requests\Common\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class Settings extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-payroll-payroll')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-payroll-payroll')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-payroll-payroll')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-payroll-payroll')->only('destroy');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $accounts = Account::enabled()->pluck('name', 'id');
        $categories = Category::expense()->enabled()->orderBy('name')->pluck('name', 'id');
        $payment_methods = Modules::getPaymentMethods();
        $pay_items = PayItem::where('company_id', company_id())->get();

        $pay_types = [
            'benefit' => trans('payroll::settings.type.benefit'),
            'deduction' => trans('payroll::settings.type.deduction')
        ];

        $amount_types = [
            'addition' => trans('payroll::settings.type.addition'),
            'subtraction' => trans('payroll::settings.type.subtraction')
        ];

        return view('payroll::settings.edit', compact('accounts', 'pay_types', 'amount_types', 'categories', 'payment_methods', 'pay_items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function update(Setting $request)
    {
        setting()->set('payroll.run_payroll_prefix', $request['run_payroll_prefix']);
        setting()->set('payroll.run_payroll_next', $request['run_payroll_next']);
        setting()->set('payroll.run_payroll_digit', $request['run_payroll_digit']);
        setting()->set('payroll.account', $request['account']);
        setting()->set('payroll.category', $request['category']);
        setting()->set('payroll.payment_method', $request['payment_method']);
        setting()->save();

        if (config('setting.cache.enabled')) {
            Cache::forget(setting()->getCacheKey());
        }

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'data' => null,
            'redirect' => route('payroll.settings.edit'),
        ];

        session(['aka_notify' => $response]);

        if ($response['success']) {
            $message = trans('messages.success.added', ['type' => trans_choice('general.settings', 1)]);

            flash($message)->success();

        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function payItemCreate()
    {
        $pay_types = [
            'benefit' => trans('payroll::settings.type.benefit'),
            'deduction' => trans('payroll::settings.type.deduction')
        ];

        $amount_types = [
            'addition' => trans('payroll::settings.type.addition'),
            'subtraction' => trans('payroll::settings.type.subtraction')
        ];

        $html = view('payroll::modals.settings.pay-item', compact('amount_types', 'pay_types'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function payItemStore(Request $request)
    {
        PayItem::create(array_merge($request->input(), [
            'company_id' => company_id()
        ]));

        $pay_items = PayItem::where('company_id', company_id())->get();

        $html = view('payroll::settings.pay-item-index', compact('pay_items'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('payroll.settings.edit'),
            'data' => [],
            'html' => $html,
        ];

        $message =  trans('payroll::settings.pay_item_added');

        flash($message)->success();

        return response()->json($response);
    }

    public function payItemDestroy(PayItem $payItem): JsonResponse
    {
        $payItem->delete();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $payItem,
            'message' => '',
        ];

        $response['redirect'] = route('payroll.settings.edit');

        $message = trans('messages.success.deleted', ['type' => $payItem->pay_item]);

        flash($message)->success();

        return response()->json($response);
    }

    public function payItemEdit(PayItem $payItem)
    {
        $pay_item = $payItem;

        $amount_types = [
            'addition' => trans('payroll::settings.type.addition'),
            'subtraction' => trans('payroll::settings.type.subtraction')
        ];

        $pay_types = [
            'benefit' => trans('payroll::settings.type.benefit'),
            'deduction' => trans('payroll::settings.type.deduction')
        ];

        $html = view('payroll::modals.settings.pay-item-edit', compact('pay_item', 'amount_types', 'pay_types'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html
        ]);
    }

    public function payItemUpdate(PayItem $payItem, Request $request)
    {
        $payItem->update($request->input());

        $pay_items = PayItem::where('company_id', company_id())->get();

        $html = view('payroll::settings.pay-item-index', compact('pay_items'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('payroll.settings.edit'),
            'data' => [],
            'html' => $html,
        ];

        $message =  trans('payroll::settings.pay_item_updated');

        flash($message)->success();

        return response()->json($response);
    }
}
