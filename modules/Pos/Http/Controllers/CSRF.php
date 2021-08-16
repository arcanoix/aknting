<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;

class CSRF extends Controller
{
    public function __invoke()
    {
        return response(csrf_token());
    }
}
