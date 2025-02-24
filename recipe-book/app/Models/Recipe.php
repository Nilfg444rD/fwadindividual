<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'instructions',
        'user_id',
    ];

    /**
     * Получение пользователя, который создал рецепт.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
