<?php

namespace Modules\Employees\Jobs\Employee;

use App\Jobs\Common\UpdateContact;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use Modules\Employees\Jobs\CreateEmployeeDashboard;

class UpdateEmployeeContact extends UpdateContact
{
    public function createUser()
    {
        // Check if user exist
        if ($user = User::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $data = $this->request->all();
        $data['locale'] = setting('default.locale', 'en-GB');

        $employee_role = Role::firstWhere('name', 'employee');

        $user = User::create($data);
        $user->roles()->attach($employee_role);
        $user->companies()->attach($data['company_id']);

        $this->dispatch(new CreateEmployeeDashboard($user->id));

        $this->request['user_id'] = $user->id;
    }

    public function countRelationships($model, $relationships): array
    {
        $record = new \stdClass();
        $record->model = $model;
        $record->relationships = $relationships;

        $counter = [];

        foreach ((array)$record->relationships as $relationship => $text) {
            if (!$c = $model->$relationship()->count()) {
                continue;
            }

            $text = Str::contains($text, '::') ? $text : 'general.' . $text;
            $counter[] = $c . ' ' . strtolower(trans_choice($text, ($c > 1) ? 2 : 1));
        }

        return $counter;
    }
}
