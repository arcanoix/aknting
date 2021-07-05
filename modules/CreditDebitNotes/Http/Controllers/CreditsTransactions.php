<?php

namespace Modules\CreditDebitNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\JsonResponse;
use Modules\CreditDebitNotes\Jobs\Credits\DeleteCreditsTransaction;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class CreditsTransactions extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  CreditsTransaction $credits_transaction
     *
     * @return JsonResponse
     */
    public function destroy(CreditsTransaction $credits_transaction)
    {
        $response = $this->ajaxDispatch(new DeleteCreditsTransaction($credits_transaction));

        $response['redirect'] = url()->previous();

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('credit-debit-notes::invoices.credits', 2) . ' ' . trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
