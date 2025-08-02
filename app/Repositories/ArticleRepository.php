<?php

namespace App\Repositories;

use App\Models\Article;
use App\Contracts\Interface\UserRepositoryInterface;

class ArticleRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Article $article)
    {
        parent::__construct($article);
    }
    
    public function create(array $data): Article
    {
        return $this->model->create($data);
    }
}
