<?php

namespace Modules\CreditDebitNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Vendors extends Controller
{
    public function bills(Request $request, Contact $vendor): JsonResponse
    {
        if (empty($vendor)) {
            return response()->json([]);
        }

        $bills = $vendor->bills()
            ->when($request->input('status'), function ($query, $status) {
                return $query->whereIn('status', $status);
            })
            ->pluck('document_number', 'id');

        return response()->json($bills);
    }
}
