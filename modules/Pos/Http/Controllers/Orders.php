<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Events\Document\DocumentCancelled;
use App\Jobs\Document\DeleteDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Pos\Jobs\CreateOrder;
use Modules\Pos\Models\Order as Document;

class Orders extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        $orders = Document::with('contact', 'transactions')->collect(['document_number'=> 'desc']);

        return $this->response('pos::orders.index', compact('orders'));
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateOrder($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('pos::general.orders', 1)]);
        }

        return response()->json($response);
    }

    public function show(Document $order)
    {
        // Get Order Totals
        foreach ($order->totals_sorted as $order_total) {
            $order->{$order_total->code} = $order_total->amount;
        }

        $currency_code = $order->currency_code;

        $total = money($order->total, $currency_code, true)->format();
        $order->grand_total = money($total, $currency_code)->getAmount();

        return $this->response('pos::orders.show', compact('order'));
    }

    public function markCancelled(Document $order): RedirectResponse
    {
        event(new DocumentCancelled($order));

        $message = trans('pos::orders.messages.marked_cancelled', ['type' => trans_choice('pos::general.orders', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    public function destroy(Document $order): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeleteDocument($order));

        $response['redirect'] = route('pos.orders.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('pos::general.orders', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
