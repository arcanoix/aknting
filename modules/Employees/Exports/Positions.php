<?php

namespace Modules\Employees\Exports;

use App\Abstracts\Export;
use Modules\Employees\Models\Position as Model;

class Positions extends Export
{
    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array)$this->ids);
        }

        return $model->cursor();
    }

    public function fields(): array
    {
        return [
            'name',
            'enabled',
        ];
    }
}
