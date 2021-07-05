<?php

namespace Modules\CreditDebitNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Customers extends Controller
{
    public function invoices(Request $request, Contact $customer): JsonResponse
    {
        if (empty($customer)) {
            return response()->json([]);
        }

        $invoices = $customer->invoices()
            ->when($request->input('status'), function ($query, $status) {
                return $query->whereIn('status', $status);
            })
            ->pluck('document_number', 'id');

        return response()->json($invoices);
    }
}
