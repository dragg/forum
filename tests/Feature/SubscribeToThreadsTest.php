<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->postJson(route('threads.subscription', [$thread->channel, $thread]))
            ->assertStatus(200);

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $user = create(User::class);
        $this->signIn($user);

        $thread = create(Thread::class);
        $thread->subscribe($user);

        $this->deleteJson(route('threads.subscription', [$thread->channel, $thread]))
            ->assertStatus(200);

        $this->assertCount(0, $thread->subscriptions);
    }

}
