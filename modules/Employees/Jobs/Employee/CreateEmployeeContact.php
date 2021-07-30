<?php

namespace Modules\Employees\Jobs\Employee;

use App\Jobs\Common\CreateContact;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Modules\Employees\Jobs\CreateEmployeeDashboard;

class CreateEmployeeContact extends CreateContact
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
}
