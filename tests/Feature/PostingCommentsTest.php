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
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id
        ];

        $comment = factory(Comment::class)->make($datas)->getAttributes();

        // on force la réponse en json
        $response = $this->json('POST', '/comments', $comment);

        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, Comment::count());

        $this->assertSame(md5(request()->ip()), $content->ip_md5);
        $this->assertSame(null, $content->reply_to);

    }

    /** @test */
    public function a_user_cannot_post_comments_on_inexisting_post()
    {
         $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => 40
        ];

        $comment = factory(Comment::class)->make($datas)->getAttributes();

        // on force la réponse en json
        $response = $this->json('POST', '/comments', $comment);

        $content = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Comment::count());
    }
}
