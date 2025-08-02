<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    protected $article;
    public function __construct(ArticleService $article)
    {
        $this->article = $article;
    }


    public function create(Request $request)
    {
       return $this->article->create($request);
    }

}
