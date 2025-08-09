<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Article $article)
    {
        parent::__construct($article);
    }
}
