<?php
namespace App\Services;

use App\Models\Role;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CommentRepository;


class CommentService
{
    use ApiResponse;
    protected $commentRepo;
    /**
    * Create a new class instance.
    */
    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    /**
    * Retrieve all comments for a specific article.
    * @param int $articleId The ID of the article for which to retrieve comments.
    * @return JsonResponse The response containing the comments or an error message.
    */
    public function getAllForArticle($articleId)
    {
        try {
            if(empty($articleId)) {
                return $this->error('Article ID is required', 400);
            }

            $comments = $this->commentRepo->getByArticleId($articleId);

            return $this->success($comments);

        } catch (\Exception $e) {

            return $this->serverError("Error retrieving comments: " . $e->getMessage());
        }
    }

    /**
    * Create a new comment for an article.
    * @param mixed $request The request data containing the comment details.
    * @param int $articleId The ID of the article to which the comment belongs.
    * @return JsonResponse The response containing the created comment or an error message.
    */
    public function create($request, $articleId, $parentId = null): JsonResponse
    {
        $user = Auth::user();
        try {
            
            if(empty($articleId)) {
                return $this->error('Article ID is required', 400);
            }

            $request['article_id'] = $articleId;

            $comment = $this->commentRepo->create([
                'message' => $request['message'],
                'name' => "tamba",
                'email' => "eliezermaksjsk@gmail.com",
                'article_id' => $articleId,
                'user_id' => $user ? $user->id : null,
                'is_active' => true,
                'web_site' => "ww.eliezerr",
                'parent_id' => $parentId
            ]);

            return $this->created($comment, 'Commentaire ajouté avec succès');

        } catch (\Exception $e) {
            
            return $this->serverError("Error creating category: " . $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     * @param string $id The ID of the comment to display.
     * @return JsonResponse The response containing the comment details or an error message.
     */
    public function show($id)
    {
        try {

            $comment = $this->commentRepo->find($id);

            return $this->success($comment);

        } catch (\Exception $e) {

            return $this->serverError("Error lors de la recuperation d'un commentaire". $e->getMessage());
        }
    }

    public function update($id, $request)
    {
        try {
            if(empty($id)) {
                return $this->error('Comment ID is required', 400);
            }

            $comment = $this->commentRepo->update($id, $request);

            return $this->success($comment, 'Commentaire mis à jour');

        } catch (\Exception $e) {

            return $this->serverError('Error updating update'. $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id The ID of the comment to delete.
     * @return JsonResponse The response indicating success or failure of the deletion.
     */
    public function delete($id): JsonResponse
    {
        $user = Auth::user();
        
        try {
            $comment = $this->commentRepo->find($id);

            $isCommentOwner = $comment->user_id == $user->id;
            $isAdmin = $comment->user && $comment->user->role->name === Role::ADMIN;
            $isArticleOwner = $comment->article && $comment->article->user_id == $user->id;
            
            if(!($isCommentOwner || $isAdmin || $isArticleOwner)) {
                return $this->error('Unauthorized action', 403);
            }

            $this->commentRepo->delete($id);

            return $this->deleted('Commentaire supprimé avec succès');

        } catch (\Exception $e) {
            return $this->serverError('Error deleting comment: ' . $e->getMessage());
        }
    }
}