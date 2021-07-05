<?php

namespace Modules\Employees\Jobs\Position;

use App\Abstracts\Job;
use Modules\Employees\Models\Position;

class UpdatePosition extends Job
{
    protected $position;

    protected $request;

    public function __construct(Position $position, $request)
    {
        $this->position = $position;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): Position
    {
        $this->authorize();

        $this->position->update($this->request->all());

        return $this->position;
    }

    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->position->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'employees' => 'employees::general.employees',
        ];

        return $this->countRelationships($this->position, $rels);
    }
}
