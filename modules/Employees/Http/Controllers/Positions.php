<?php

namespace Modules\Employees\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Import as ImportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Employees\Exports\Positions as Export;
use Modules\Employees\Http\Requests\Position as Request;
use Modules\Employees\Imports\Positions as Import;
use Modules\Employees\Jobs\Position\CreatePosition;
use Modules\Employees\Jobs\Position\DeletePosition;
use Modules\Employees\Jobs\Position\UpdatePosition;
use Modules\Employees\Models\Position;

class Positions extends Controller
{
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-employees-positions')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-employees-positions')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-employees-positions')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-employees-positions')->only('destroy');
    }

    public function index()
    {
        $positions = Position::collect();

        return view('employees::positions.index', compact('positions'));
    }

    public function create()
    {
        return view('employees::positions.create');
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreatePosition($request));

        if ($response['success']) {
            $response['redirect'] = route('employees.positions.index');

            $message = trans('messages.success.added', ['type' => trans_choice('employees::general.positions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('employees.positions.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function edit(Position $position)
    {
        return view('employees::positions.edit', compact('position'));
    }

    public function update(Position $position, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdatePosition($position, $request));

        if ($response['success']) {
            $response['redirect'] = route('employees.positions.index');

            $message = trans('messages.success.updated', ['type' => $position->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('employees.positions.edit', $position->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function duplicate(Position $position): RedirectResponse
    {
        $clone = $position->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('employees::general.positions', 1)]);

        flash($message)->success();

        return redirect()->route('employees.positions.edit', $clone->id);
    }

    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('employees::general.positions', 2));

        if ($response['success']) {
            $response['redirect'] = route('employees.positions.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['employees', 'positions']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    public function enable(Position $position): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdatePosition($position, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $position->name]);
        }

        return response()->json($response);
    }

    public function disable(Position $position): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdatePosition($position, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $position->name]);
        }

        return response()->json($response);
    }

    public function destroy(Position $position): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeletePosition($position));

        $response['redirect'] = route('employees.positions.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $position->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('employees::general.positions', 2));
    }
}
