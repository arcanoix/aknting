<?php

namespace Modules\Employees\Imports;

use App\Abstracts\Import;
use Modules\Employees\Http\Requests\Position as Request;
use Modules\Employees\Models\Position as Model;

class Positions extends Import
{
    public function model(array $row): Model
    {
        return new Model($row);
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
