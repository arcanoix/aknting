<?php

namespace Modules\Employees\Jobs\Position;

use App\Abstracts\Job;
use Modules\Employees\Models\Position;

class CreatePosition extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     */
    public function handle(): Position
    {
        return Position::create($this->request->all());
    }
}
