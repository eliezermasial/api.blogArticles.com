<?php

namespace App\Services;

use Exception;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ArticleRepository;


class ArticleService
{
    use ApiResponse;
    
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
        try {
            $articles = $this->articleRepo->get();

            return $this->success($articles);

        } catch (\Exception $e)
        {
            return $this->serverError("Erreur lors de la récupération des articles.");
        }

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

        try {
            $author = $user->id;

            $slug = Str::slug($request['title']);

            $article = $this->articleRepo->create([
                'content' => $request->content,
                'title' => $request->title,
                'slug' => $slug,
                'isActive' => true,
                'isSharable' => true,
                'isComment' => true,
                'author_id' => $author,
                'category_id' => $request->category_id
            ]);

            return $this->created($article);
            
        } catch (Exception $e) {
            return $this->serverError("Erreur lors de la création de l'article.");
        }
        
    }

    /**
    * Récupère les détails d'un article à partir de son identifiant.
    *
    * @param int $id L'identifiant de l'article.
    * @return JsonResponse La réponse JSON contenant les informations de l'article.
    */
    public function getArticle($id): JsonResponse
    {
        try {
            $article = $this->articleRepo->find($id);
            
            if(!$article) {
                return $this->notFound();
            }

            return $this->success($article);

        } catch (\Exception $e) {
            
            return $this->serverError("Erreur lors de la récupération de l'article.");
        }
        
    }

    public function update($request, $id): JsonResponse
    {
        $user = Auth::user();
        try {
            $article = $this->articleRepo->find($id);

            if (!$article) {
                return $this->notFound("Article non trouvé.");
            }
        
            if($user->id != $article->author_id) {
                return $this->forbidden("Vous n'êtes pas autorisé à modifier cet article.");
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

            return $this->success($article);

        } catch (Exception $e) {
            
            return $this->serverError("Erreur lors de la mise à jour de l'article.");
        }

    }

    /**
    * Supprime un article à partir de son identifiant.
    *
    * @param int $id L'identifiant de l'article à supprimer.
    * @return JsonResponse La réponse JSON confirmant la suppression.
    */
    public function delete($id): JsonResponse
    {
        try {
            $article = $this->articleRepo->find($id);

            if (!$article) {
                return $this->notFound("Article non trouvé.");
            }
            
            $this->articleRepo->delete($id);

            return $this->deleted("Article supprimé avec succès.");

        } catch (Exception $e) {

            return $this->serverError("Erreur lors de la suppression de l'article.");
        }
    }

}
