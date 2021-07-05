<?php

namespace Modules\Payroll\BulkActions;

use App\Abstracts\BulkAction;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Jobs\PayCalendar\DeletePayCalendar;

class PayCalendars extends BulkAction
{
    public $model = PayCalendar::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-payroll-pay-calendars',
        ],
    ];

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeletePayCalendar($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
