<?php

namespace Modules\Payroll\BulkActions;

use App\Abstracts\BulkAction;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Jobs\RunPayroll\DeleteRunPayroll;

class RunPayrolls extends BulkAction
{
    public $model = RunPayroll::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-payroll-run-payrolls',
        ],
    ];

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeleteRunPayroll($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
