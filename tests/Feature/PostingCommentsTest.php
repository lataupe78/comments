<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostingCommentsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_user_can_post_comments()
    {
        //$this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id
        ]);

        $response = $this->json('POST', '/comments', $comment->getAttributes());
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, Comment::count());

        $this->assertSame(md5(request()->ip()), $content->ip_md5);
        $this->assertSame(null, $content->reply_to);

    }

    /** @test */
    public function a_user_cannot_post_comments_on_non_existent_post()
    {
        // $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => 40
        ]);

        $response = $this->json('POST', '/comments', $comment->getAttributes());
        $content = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Comment::count());
    }


    /** @test */
    public function a_user_can_reply_to_an_existent_comment()
    {
        $post = factory(Post::class)->create();

        $comment = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
        ]);

        $reply = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'reply_to' => $comment->id
        ]);

        $response = $this->json('POST', '/comments', $reply->getAttributes());
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, Comment::count());

    }


        /** @test */
    public function a_user_cannot_reply_to_an_existent_reply()
    {
        $post = factory(Post::class)->create();

        $comment = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
        ]);

        $reply = factory(Comment::class)->create([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'content' => "Reply to comment #{$comment->id}",
            'reply_to' => $comment->id
        ]);

        $reply2 = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'content' => "Reply to reply #{$reply->id}",
            'reply_to' => $reply->id
        ]);

        $response = $this->json('POST', '/comments', $reply2->getAttributes());
        $content = json_decode($response->getContent());
        /*
        dump($content);

        $check_response = $this->json('GET', '/comments', [
            'type' => get_class($post), 'id' => $post->id
        ]);
        $comments = json_decode($check_response->getContent());
        dump($comments);
        */

        $this->assertEquals(2, Comment::count());
        $this->assertEquals(422, $response->getStatusCode());

        $this->assertObjectHasAttribute('errors', $content);
        $this->assertObjectHasAttribute('reply_to', $content->errors);



    }

    /** @test */
    public function a_user_cannot_reply_to_non_existent_comment()
    {
        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'reply_to' => 40
        ]);

        $response = $this->json('POST', '/comments', $comment->getAttributes());
        $content = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Comment::count());

    }


    /** @test */
    public function a_user_cannot_post_comments_on_non_existent_related_model()
    {
        // $this->withoutExceptionHandling();
        $comment = factory(Comment::class)->make([
            'commentable_type' => 'App\Models\NotARelatedModel',
            'commentable_id' => 1
        ]);

        $response = $this->json('POST', '/comments', $comment->getAttributes());
        $content = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Comment::count());
    }

    /** @test */
    public function username_is_required()
    {
        // $this->withoutExceptionHandling();
        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'username' => ''
        ]);

        $response = $this->json('POST', '/comments', $comment->getAttributes());
        $content = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Comment::count());
        $this->assertObjectHasAttribute('errors', $content);
        $this->assertObjectHasAttribute('username', $content->errors);
    }

    /** @test */
    public function valid_email_is_required()
    {
        // $this->withoutExceptionHandling();
        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->make([
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
            'email' => 'not-a-_valid-_email'
        ]);

        $response = $this->json('POST', '/comments', $comment->getAttributes());
        $content = json_decode($response->getContent());
        //dump(gettype($content));
        //dump($content);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Comment::count());
        $this->assertObjectHasAttribute('errors', $content);
        $this->assertObjectHasAttribute('email', $content->errors);
    }
}
