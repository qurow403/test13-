<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    }
}
