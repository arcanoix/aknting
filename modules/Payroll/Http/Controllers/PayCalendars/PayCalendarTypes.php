<?php

namespace Modules\Payroll\Http\Controllers\PayCalendars;

use App\Abstracts\Http\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Models\PayCalendar\PayCalendar;

class PayCalendarTypes extends Controller
{
    public function getType(Request $request): JsonResponse
    {
        return response()->json([
           'data' => PayCalendar::getPaydayModes($request['type']),
        ]);
    }
}
