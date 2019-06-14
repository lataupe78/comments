<?php

use App\Models\Post;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
   	public function run(Faker $faker)
    {
        factory(Post::class, 10)->create();
    }
}
