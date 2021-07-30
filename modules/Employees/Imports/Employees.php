<?php

namespace Modules\Employees\Imports;

use App\Abstracts\Import;
use App\Http\Requests\Common\Contact as ContactRequest;
use App\Models\Common\Contact;
use Modules\Employees\Http\Requests\Employee as EmployeeRequest;
use Modules\Employees\Models\Employee as Model;
use Modules\Employees\Models\Position;

class Employees extends Import
{
    public function batchSize(): int
    {
        return 1;
    }

    public function model(array $row): ?Model
    {
        // TODO: use `implements OnEachRow` when Laravel Excel will use WithMapping with OnEachRow

        if ($this->isEmpty($row, 'position_id')) {
            $position = Position::create([
                'company_id' => $row['company_id'],
                'name'       => $row['position'],
                'enabled'    => true,
            ]);
            $row['position_id'] = $position->id;
        }

        $contact = Contact::create($row);
        $row['contact_id'] = $contact->id;

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'employee';
        $row['position_id'] = (int) Position::where('name', $row['position'])->pluck('id')->first();
        $row['amount'] = $row['salary'];

        return $row;
    }

    public function rules(): array
    {
        $employee_rules = array_filter((new EmployeeRequest())->rules(), function ($value, $key) {
            return in_array($key, [
                'birth_day',
                'gender',
                'position_id',
                'amount',
                'hired_at',
            ]);
        }, ARRAY_FILTER_USE_BOTH);

        $rules = array_merge(
            (new ContactRequest([], ['email' => 'just a string to trigger adding the email rule']))->rules(),
            $employee_rules
        );

        return $this->replaceForBatchRules($rules);
    }
}
