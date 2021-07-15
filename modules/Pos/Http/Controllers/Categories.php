<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Category;

class Categories extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-common-items')->only('index');
    }

    public function index()
    {
        $categories = Category::enabled()
            ->item()
            ->collect();

        return response()->json($categories);
    }
}
