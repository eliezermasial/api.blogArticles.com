<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "is_active",
        "user_id"
    ];

    public function articles (): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
