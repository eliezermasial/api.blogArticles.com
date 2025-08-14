<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentsController extends Controller
{
    protected $comment;

    /**
     * Create a new controller instance.
     */
    public function __construct(CommentService $comment) {
        $this->comment= $comment;
    }


    /**
     * Display a listing of the resource.
     */
    public function index($articleId)
    {
        return $this->comment->getAllForArticle($articleId);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $articleId)
    {
        return $this->comment->create($request->all(), $articleId);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->comment->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->comment->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->comment->delete($id);
    }
}
