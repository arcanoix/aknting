<?php

namespace Modules\Employees\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Employees\Http\Requests\Position as Request;
use Modules\Employees\Jobs\Position\CreatePosition;

class Positions extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-employees-positions')->only(['create', 'store']);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => view('employees::modals.positions.create')->render(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreatePosition($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('employees::general.positions', 1)]);
        }

        return response()->json($response);
    }
}
