<?php

use Faker\Generator as Faker;

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => \App\Notifications\ThreadWasUpdated::class,
        'notifiable_id' => function () {
            return auth()->user()->id ?? create(\App\Models\User::class)->id;
        },
        'notifiable_type' => \App\Models\User::class,
        'data' => ['foo' => 'bar'],
    ];
});
