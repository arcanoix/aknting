<?php

namespace Modules\PrintTemplate\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;

class Template extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name'=>'required|string',
            'type'=>'required',
            'pagesize'=>'required',
            'enabled'=>'required',
            'attachment'=>'mimes:' . setting('filesystems.mimes', 'pdf,jpeg,jpg,png').'|between:0,' . setting('filesystems.max_size',10485760) * 1024
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
