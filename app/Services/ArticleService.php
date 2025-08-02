<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ArticleRepository;


class ArticleService
{
    protected $articleRepo;
    /**
     * Create a new class instance.
     */
    public function __construct(ArticleRepository $articleRepo)
    {
        $this->articleRepo = $articleRepo;
    }

    public function all()
    {
        $articles = $this->articleRepo->get();

        return response()->json($articles);
    }

    public function create($request)
    {
        $user = Auth::user();
        $author = $user->id;
        $slug = Str::slug($request['title']);

        $category = Category::firstOrCreate([
            "name" => "etude",
            "slug" => "etude",
            "description" => "pour les etudiant",
            "is_active" => true,
            ]);

        $article = $this->articleRepo->create([
            'content' => $request->content,
            'title' => $request->title,
            'slug' => $slug,
            'isActive' => true,
            'isSharable' => true,
            'isComment' => true,
            'author_id' => $author,
            'category_id' => $category->id
        ]);

        return response()->json(["message"=> "article crée","article"=> $article], 200);
    }

}
