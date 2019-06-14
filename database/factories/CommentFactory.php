<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->email,
        'ip' => $faker->ipv4,
        'content' => $faker->sentence
    ];
});
