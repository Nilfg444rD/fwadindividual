<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Получить все категории
    public function index()
{
    return response()->json(Category::all(), 200, [], JSON_UNESCAPED_UNICODE);
}

    // Создать категорию
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:categories']);

        return Category::create($request->all());
    }

    // Удалить категорию
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Категория удалена']);
    }
}
