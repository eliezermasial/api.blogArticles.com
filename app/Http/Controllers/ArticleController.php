<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    protected $article;
    public function __construct(ArticleService $article)
    {
        $this->article = $article;
    }

    public function index()
    {
        return $this->article->all();
    }

    public function create(Request $request): JsonResponse
    {
       return $this->article->create($request);
    }

}
