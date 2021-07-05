<?php

namespace Modules\CreditDebitNotes\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use Illuminate\Http\JsonResponse;

class CreditNoteTemplates extends Controller
{
    public $skip_keys = ['company_id', '_method', '_token', '_prefix', '_template', 'null'];

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware([
            'permission:update-settings-settings',
            'permission:update-credit-debit-notes-settings-credit-note'
        ])->only('update');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $fields = $request->all();
        $prefix = $request->get('_prefix', 'credit-debit-notes.credit_note');

        foreach ($fields as $key => $value) {
            $real_key = $prefix . '.' . $key;

            // Don't process unwanted keys
            if (in_array($key, $this->skip_keys)) {
                continue;
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('credit-debit-notes.settings.credit-note.edit'),
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
