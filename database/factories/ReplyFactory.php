<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function () {
            return factory(\App\Models\Thread::class)->create()->id;
        },
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'body' => $faker->paragraph,
    ];
});
