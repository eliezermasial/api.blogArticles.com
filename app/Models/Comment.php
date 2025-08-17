<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'message',
        'name',
        'email',
        'article_id',
        'user_id',
        'is_active',
        'web_site',
        'parent_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    //Relation pour les réponses
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    //Relation pour accéder au parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
