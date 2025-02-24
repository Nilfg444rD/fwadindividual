<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

// ✅ Регистрация пользователя
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token], 201);
});

// ✅ Авторизация пользователя
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Неверные данные для входа.'],
        ]);
    }

    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
});

// ✅ Выход пользователя (удаление токена)
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Вы успешно вышли.']);
});

// ✅ Добавление рецепта (только авторизованный пользователь)
Route::middleware('auth:sanctum')->post('/add-recipe', function (Request $request) {
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'ingredients' => 'required|string',
        'instructions' => 'required|string',
    ]);

    $recipe = Recipe::create([
        'title' => $request->title,
        'description' => $request->description,
        'ingredients' => $request->ingredients,
        'instructions' => $request->instructions,
        'user_id' => $request->user()->id,
    ]);

    return response()->json($recipe, 201);
});

// ✅ Получение рецептов авторизованного пользователя
Route::middleware('auth:sanctum')->get('/my-recipes', function (Request $request) {
    return response()->json($request->user()->recipes);
});

// ✅ Получение данных о текущем пользователе
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
