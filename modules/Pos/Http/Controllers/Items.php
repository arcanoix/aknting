<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Item;
use Illuminate\Support\Facades\Storage;

class Items extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-common-items')->only('index');
    }

    public function index()
    {
        $items = Item::select(['id', 'name', 'category_id', 'sale_price as price'])
            ->enabled()
            ->with('taxes')
            ->collect();

        foreach ($items as $item) {
            $item->img = $item->picture ? Storage::url($item->picture->id) : url('/modules/Pos/Resources/assets/img/item-placeholder.png');
        }

        return response()->json($items);
    }
}
