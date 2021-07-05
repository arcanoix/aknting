<?php

namespace Modules\Payroll\Jobs\RunPayroll;

use App\Abstracts\Job;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class UpdateRunPayrollAttachments extends Job
{
    protected $request;

    protected $run_payroll;

    public function __construct($run_payroll, $request)
    {
        $this->run_payroll = $run_payroll;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): RunPayroll
    {
        DB::transaction(function () {
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->run_payroll, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'run-payrolls');

                    $this->run_payroll->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->run_payroll->attachment) {
                $this->deleteMediaModel($this->run_payroll, 'attachment', $this->request);
            }
        });

        return $this->run_payroll;
    }
}
