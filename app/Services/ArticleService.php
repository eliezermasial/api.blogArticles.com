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

    /**
     * Récupère la liste complète des articles.
     *
     * @return JsonResponse La réponse JSON contenant tous les articles.
     */
    public function all(): JsonResponse
    {
        $articles = $this->articleRepo->get();

        return response()->json($articles);
    }

    /**
    * Crée un nouvel article avec les informations fournies par l'utilisateur connecté.
    *
    * @param mixed $request Les données de la requête (title, content, etc.).
    * @return JsonResponse La réponse JSON contenant le message de succès et l'article créé.
    */
    public function create($request): JsonResponse
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

    /**
    * Récupère les détails d'un article à partir de son identifiant.
    *
    * @param int $id L'identifiant de l'article.
    * @return JsonResponse La réponse JSON contenant les informations de l'article.
    */
    public function getArticle($id): JsonResponse
    {
        $article = $this->articleRepo->find($id);

        return response()->json($article,200);
    }

    public function update($request, $id): JsonResponse
    {
        $user = Auth::user();
        $article = $this->articleRepo->find($id);
        
        if($user->id != $article->author_id)
        {
            return response()->json(["message" => "vous n'avez pas accès à cet article"], 403);
        }

        $slug = Str::slug($request['title']);

        $article = $this->articleRepo->update($id,[
            'content' => $request->content,
            'title' => $request->title,
            'slug' => $slug,
            'isActive' => true,
            'isSharable' => false,
            'isComment' => true
        ]);

        return response()->json(["message" => "success", "article" => $article],200);
    }

}
