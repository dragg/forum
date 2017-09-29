<?php

use App\Models\Thread;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->sentence,
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'channel_id' => function () {
            return factory(\App\Models\Channel::class)->create()->id;
        },
    ];
});
