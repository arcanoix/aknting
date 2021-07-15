<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Modules\Pos\Models\Order;
use Modules\Pos\Notifications\OrderReceipt;
use Throwable;

class OrderReceipts extends Controller
{
    public function __construct()
    {
        //
    }

    public function show(Order $order)
    {
        return view('pos::orders.receipt', compact('order'));
    }

    public function print(Order $order)
    {
        $view = view('pos::orders.receipt', [
            'order' => $order,
            'print' => true,
        ]);

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    public function email(Order $order, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
        ], [
            'required' => trans('pos::validation.required'),
        ]);

        if ($validator->fails()) {
            return response([
                'error'   => true,
                'message' => $validator->getMessageBag()->messages()['email'][0],
            ]);
        }

        try {
            Notification::route('mail', $request->get('email'))->notify(new OrderReceipt($order));
        } catch (Exception | Throwable $e) {
            return response([
                'error'   => true,
                'message' => $e->getMessage(),
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Email has been sent',
        ]);
    }
}
