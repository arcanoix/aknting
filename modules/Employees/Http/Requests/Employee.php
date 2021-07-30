<?php

namespace Modules\Employees\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;
use Illuminate\Validation\Rule;

class Employee extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $email_rule = '';

        if (!empty($this->request->get('email'))) {
            // Check if store or update
            if ($this->isMethod('PATCH') && $this->employee->contact) {
                $id = $this->employee->contact->id;
            } else {
                $id = null;
            }
            $company_id = $this->request->get('company_id');
            $type = $this->request->get('type', 'employee');

            $email_rule = [
                'email',
                Rule::unique('contacts')
                    ->ignore($id)
                    ->where(function ($query) use ($company_id, $type) {
                        return $query->where([
                            ['company_id', '=', $company_id],
                            ['type', '=', $type],
                            ['deleted_at', '=', null],
                        ]);
                    }),
            ];
        }

        $attachment = 'nullable';

        if ($this->files->get('attachment')) {
            $attachment = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        return [
            'birth_day'    => 'required|date_format:Y-m-d|before_or_equal:hired_at',
            'gender'       => 'required|string',
            'position_id'  => 'required|integer',
            'amount'       => 'required|numeric',
            'hired_at'     => 'required|date_format:Y-m-d|after_or_equal:birth_day',
            'attachment.*' => $attachment,

            // Contact
            'type'         => 'required|string',
            'name'         => 'required|string',
            'email'        => $email_rule,
            'phone'        => 'nullable|string',
            'enabled'      => 'integer|boolean',
        ];
    }
}
