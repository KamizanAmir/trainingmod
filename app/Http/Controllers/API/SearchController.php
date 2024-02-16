<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        $items = Item::where('name', 'LIKE', "%{$query}%")->get();
        $categories = Category::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json([
            'items' => $items,
            'categories' => $categories,
        ]);
    }
}

