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
        $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id
        ];
        $comment = factory(Comment::class)->make($datas);

        $response = $this->call('POST', '/comments', $comment->toArray());

        $this->assertEquals(1, Comment::count());
    }
}
