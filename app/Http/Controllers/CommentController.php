<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{

    public function index(Request $request)
    {
        sleep(1);

        $comments = Comment::allFor(
            $request->input('id'),
            $request->input('type')
        )->get();

        if(request()->expectsJson()){
            return response()->json($comments, 200, [], JSON_NUMERIC_CHECK);
        }

        return view('front.comments.index', [
            'comments' => $comments
        ]);
    }


    public function store(StoreCommentRequest $request)
    {


        $data = $request->validated();

        $comment = Comment::create([
            'commentable_type' => $data['commentable_type'],
            'commentable_id' => $data['commentable_id'],
            'username' => $data['username'],
            'email' => $data['email'],
            'content' => $data['content'],
            'reply_to' => $data['reply_to'] ?? null,
            'ip' => request()->ip(),
        ]);

        if(request()->wantsJson()){
            return response()->json($comment, 200, [], JSON_NUMERIC_CHECK);
        }

        return redirect()->route('posts.index')->with('success', __('comment.created.success'));

    }



    public function update(Request $request, Comment $comment)
    {

    }


    public function destroy(Comment $comment)
    {
        if($comment->ip === request()->ip()){

            //dump($comment);

            $replies = Comment::where([
                'reply_to' =>  $comment->id,
                'commentable_type' => $comment->commentable_type,
                'commentable_id' => $comment->commentable_id,
            ])->delete();

            $comment->delete();

            if(request()->expectsJson()){
                return response()->json($comment, 200, [], JSON_NUMERIC_CHECK);
            }
            return redirect()->route('comments.index')->with('success', __('comment.destroy.success'));
        }
        if(request()->expectsJson()){
            return response()->json(__('comment.destroy.fail'), 403, [], JSON_NUMERIC_CHECK);
        }
        return redirect()->route('comments.index')->with('success', __('comment.destroy.fail'));
    }
}
