<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Basket extends Model
{
    protected $fillable = ['user_id', 'notebook_id', 'count'];

    // Отношение к пользователю
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Отношение к товару (notebook)
    public function notebook(): BelongsTo
    {
        return $this->belongsTo(Notebook::class);
    }
}
