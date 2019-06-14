<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrievingCommentsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_gets_main_comments()
    {

        //$this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id
        ];

        $comment1 = factory(Comment::class)->create($datas);
        $comment2 = factory(Comment::class)->create($datas);
        $comment3 = factory(Comment::class)->create($datas);
        $this->assertEquals(3, Comment::count());

        $response = $this->json('GET', '/comments', ['type' => get_class($post), 'id' => $post->id ]);

        $this->assertEquals(200, $response->getStatusCode());
        $comments = json_decode($response->getContent());
        $this->assertEquals(3, count($comments));
        $this->assertSame(null, $comments[0]->reply_to);

        //dump($comments);

    }

    /** @test */
    public function it_gets_comments_with_replies()
    {
        //$this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id
        ];
        $comment1 = factory(Comment::class)->create($datas);
        $comment2 = factory(Comment::class)->create($datas);
        $reply = factory(Comment::class)->create(array_merge($datas, ['reply_to' => $comment1->id]));

        $response = $this->json('GET', '/comments', ['type' => get_class($post), 'id' => $post->id ]);

        $this->assertEquals(200, $response->getStatusCode());
        $comments = json_decode($response->getContent());

        //dump($comments);

        $this->assertEquals(2, count($comments));
        $this->assertSame($comment1->id, $comments[0]->replies[0]->reply_to);


    }

     /** @test */
    public function it_gets_comments_with_replies_sorted_by_created_at_desc()
    {
        //$this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
        ];

        $comment1 = factory(Comment::class)->create(array_merge($datas, [
            'created_at' => Carbon::now()->sub('2 hours')
        ]));

        $comment2 = factory(Comment::class)->create($datas);

        $datas['reply_to'] = $comment1->id;
        $reply1 = factory(Comment::class)->create(
            array_merge($datas, ['created_at'=> Carbon::now()->sub('1 hour')])
        );
        $reply2 = factory(Comment::class)->create(
            array_merge($datas, ['created_at'=> Carbon::now()])
        );
        $reply3 = factory(Comment::class)->create(
            array_merge($datas, ['created_at'=> Carbon::now()->sub('3 hours')])
        );

        $response = $this->json('GET', '/comments', ['type' => get_class($post), 'id' => $post->id ]);

        $this->assertEquals(200, $response->getStatusCode());
        $comments = json_decode($response->getContent());
        //dump($comments);

        $this->assertCount(2, $comments);

        // on veut les comments dans l'ordre decroissant soit  2, 1
        $this->assertSame($comment2->id, $comments[0]->id);
        $this->assertSame($comment1->id, $comments[1]->id);

        // on veut les replies dans l'ordre decroissant soit  2, 1, 3
        $this->assertCount(3, $comments[1]->replies);
        $this->assertSame($reply2->id, $comments[1]->replies[0]->id);
        $this->assertSame($reply1->id, $comments[1]->replies[1]->id);
        $this->assertSame($reply3->id, $comments[1]->replies[2]->id);

    }

      /** @test */
    public function it_gets_comments_without_sensible_fields()
    {
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();
        $datas = [
            'commentable_type' => get_class($post),
            'commentable_id' => $post->id,
        ];

        $comment1 = factory(Comment::class)->create(array_merge($datas, [
            'created_at' => Carbon::now()->sub('2 hours')
        ]));

        $response = $this->json('GET', '/comments', ['type' => get_class($post), 'id' => $post->id ]);

        $this->assertEquals(200, $response->getStatusCode());
        $comments = json_decode($response->getContent());

        $this->assertCount(1, $comments);

        $this->assertObjectNotHasAttribute('email', $comments[0]);
        $this->assertObjectNotHasAttribute('ip', $comments[0]);
        $this->assertObjectHasAttribute('email_md5', $comments[0]);
        $this->assertObjectHasAttribute('ip_md5', $comments[0]);
        $this->assertSame(md5($comment1->ip), $comments[0]->ip_md5);
        $this->assertSame(md5($comment1->email), $comments[0]->email_md5);


        //dump($comments[0]);
    }
}
