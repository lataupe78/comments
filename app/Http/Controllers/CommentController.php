<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        if(request()->expectsJson()){
            return response()->json($comment, 200, [], JSON_NUMERIC_CHECK);
        }

        return redirect()->route('comments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
