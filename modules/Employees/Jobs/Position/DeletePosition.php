<?php

namespace Modules\Employees\Jobs\Position;

use App\Abstracts\Job;
use Modules\Employees\Models\Position;

class DeletePosition extends Job
{
    protected $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    public function handle(): bool
    {
        $this->authorize();

        $this->position->delete();

        return true;
    }

    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->position->name, 'text' => implode(', ', $relationships)]);

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
