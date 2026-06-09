<?php

namespace App\Http\Controllers;

use App\Enums\CommentStatus;
use App\Events\CommentApproved;
use App\Events\CommentRejected;
use App\Events\CommentSubmitted;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Article $article)
    {
        abort_unless($article->isPublished(), 404);

        // make() costruisce il commento con article_id già impostato (dalla relazione)
        // e con il solo campo fillable 'body'
        $comment = $article->comments()->make([
            'body' => $request->validated()['body'],
        ]);
        $comment->user_id = $request->user()->id;
        $comment->status = CommentStatus::Pending;
        $comment->save();

        CommentSubmitted::dispatch($comment);

        $message = 'Commento inviato! Sarà visibile dopo l’approvazione del proprietario dell’articolo.';

        // BIVIO sincrono / asincrono (stesso schema di ArticleController@updateStatus)
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'comment' => [
                    'id' => $comment->id,
                    'author' => $comment->user->name,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at,
                    'isPending' => $comment->isPending(),
                ],
            ]);
        }

        return back()->with('success', $message);
    }

    public function approve(Comment $comment)
    {
        $comment->status = CommentStatus::Approved;
        $comment->save();

        CommentApproved::dispatch($comment);

        return back()->with('success', 'Commento approvato e pubblicato.');
    }

    public function destroy(Comment $comment)
    {

        CommentRejected::dispatch($comment);

        $comment->delete($comment);

        return back()->with('success', 'Commento eliminato. L’autore è stato avvisato.');
    }
}
