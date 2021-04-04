<?php

/** @var Factory $factory */

use App\Reply;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class),
        'tweet_id' => factory(App\Tweet::class),
        'body' => $faker->sentence(),
        'parent_id' => null
    ];
});
