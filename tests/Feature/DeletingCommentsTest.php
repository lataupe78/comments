<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletingCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_delete_his_comment()
    {
         $post = factory(Post::class)->create();

        $comment = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'ip' => request()->ip(),
        ]);

        /*
        $response = $this->json('GET', '/comments', ['type' => get_class($post), 'id' => $post->id ]);
        $this->assertEquals(200, $response->getStatusCode());
        $comments = json_decode($response->getContent());
        $this->assertCount(1, $comments);

        $this->assertSame($comment->username, $comments[0]->username);
        $this->assertSame(md5($comment->ip), $comments[0]->ip_md5);
        $this->assertSame(md5($comment->email), $comments[0]->email_md5);
        */
        $response = $this->json('DELETE', route('comments.destroy', $comment));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, Comment::get());
    }

    /** @test */
    public function a_user_can_delete_a_reply_he_owns()
    {
        $post = factory(Post::class)->create();

        $comment = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
        ]);

        $reply = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'reply_to' => $comment->id,
            'ip' => request()->ip(),
        ]);

        $response = $this->json('DELETE', route('comments.destroy', $reply));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, Comment::get());
    }

    /** @test */
    public function replies_are_deleted_when_a_comment_is_deleted()
    {
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();

        $comment = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'ip' => request()->ip(),
        ]);

        $reply1 = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'reply_to' => $comment->id
        ]);


        $reply2 = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'reply_to' => $comment->id
        ]);

        $this->assertCount(3, Comment::get());

        $response = $this->json('DELETE', route('comments.destroy', $comment));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, Comment::get());
    }

     /** @test */
    public function a_user_cannot_delete_comment_he_dont_owns()
    {
         $post = factory(Post::class)->create();

        $comment = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'ip' => '123.456.789.012',
        ]);

        $response = $this->json('DELETE', route('comments.destroy', $comment));
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertCount(1, Comment::get());
    }
}
