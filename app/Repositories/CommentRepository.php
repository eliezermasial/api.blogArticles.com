<?php
namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository extends BaseRepository
{
    /**
    * Create a new class instance.
    */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    /**
    * Get comments by article ID.
    * @param int $articleId
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function getByArticleId($articleId): Collection
    {
        return $this->model->where('article_id', $articleId)->orderByDesc('created_at')->get();
    }
}