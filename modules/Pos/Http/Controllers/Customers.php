<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;

class Customers extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-sales-customers')->only('index');
    }

    public function index()
    {
        $items = Contact::where('type', 'customer')
            ->collect();

        return response()->json($items);
    }
}
