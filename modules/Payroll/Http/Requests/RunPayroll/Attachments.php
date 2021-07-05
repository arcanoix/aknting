<?php

namespace Modules\Payroll\Http\Requests\RunPayroll;

use App\Abstracts\Http\FormRequest as Request;

class Attachments extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attachment = 'nullable';

        if ($this->files->get('attachment')) {
            $attachment = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        return [
            'attachment.*' => $attachment,
        ];
    }
}
