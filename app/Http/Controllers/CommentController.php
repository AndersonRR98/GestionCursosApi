<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller

{   
 
    public function index()
    {
         $comments = Comment::with('commentable')
        ->included()   // incluye relaciones dinámicamente (?include=commentable)
        ->filter()     // filtra por campos (?filter[contenido]=texto)
        ->sort()       // ordena (?sort=-created_at)
        ->getOrPaginate(); // devuelve paginado si pasas ?page[size]=10&page[number]=1

         return response()->json($comments); 
        
        }

    public function store(Request $request)
    {
        $request->validate([
            'texto' => 'required|string',
           'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'user_id' => 'required|exists:users,id'

        ]);

        $comment = Comment::create([
            'texto' => $request->texto,
            'commentable_type' => $request->commentable_type,
            'commentable_id' => $request->commentable_id,
            'user_id'=>$request->user_id
        ]);

        return response()->json($comment, 201);
    }

   public function show($id)
    {
        $comment = Comment::with(['user', 'commentable'])->findOrFail($id);
        return response()->json($comment);
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'texto' => 'sometimes|string',
            'user_id' => 'required|exists:users,id',

        ]);

        $comment->update($request->only('texto','user_id'));
        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}


