<?php

use App\Models\Comment;
use App\Models\Post;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
	public function run(Faker $faker)
	{
		$posts = Post::all();

		foreach ($posts as $post) {

			for($i=0; $i<$faker->numberBetween(3, 6); $i++ ){


				$comment = factory(Comment::class)->create([
					'commentable_type' => get_class($post),
					'commentable_id' => $post->id,
				]);

				if(mt_rand(0, 10) > 3){
					$reply = factory(Comment::class, $faker->numberBetween(1, 3))->create([
						'commentable_type' => get_class($post),
						'commentable_id' => $post->id,
						'reply_to' => $comment->id
					]);
				}
			}
		}
	}
}
