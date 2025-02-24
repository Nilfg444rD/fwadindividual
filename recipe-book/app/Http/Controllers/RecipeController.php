<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // Получить все рецепты
    public function index()
    {
        return response()->json(Recipe::with('category')->get(), 200, [], JSON_UNESCAPED_UNICODE);
    }
    

    // Создать новый рецепт
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        return Recipe::create($request->all());
    }

    // Получить один рецепт
    public function show(Recipe $recipe)
{
    return response()->json($recipe->load('category'), 200, [], JSON_UNESCAPED_UNICODE);
}


    // Обновить рецепт
    public function update(Request $request, Recipe $recipe)
    {
        $recipe->update($request->all());
        return $recipe;
    }

    // Удалить рецепт
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return response()->json(['message' => 'Рецепт удалён']);
    }
}
