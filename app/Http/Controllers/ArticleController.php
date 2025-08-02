<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class ArticleController extends Controller
{
    protected $article;
    public function __construct(ArticleService $article)
    {
        $this->article = $article;
    }

    public function index(): JsonResponse
    {
        return $this->article->all();
    }

    public function store(Request $request): JsonResponse
    {
       return $this->article->create($request);
    }

    public function show( $article):JsonResponse
    {
        return $this->article->getArticle($article);
    }

    public function update(Request $request, $article): JsonResponse
    {
        return $this->article->update($request, $article);
    }

}
