<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Thread;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    /** @test */
    public function guests_may_not_create_threads()
    {
        $thread = make(Thread::class);

        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('login'));

        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('threads.show', [$thread->channel->slug, 1]));

        $this->get(route('threads.index'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 99])
            ->assertSessionHasErrors('channel_id');
    }

    private function publishThread(array $overrides = [])
    {
        $this->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.store', $thread->toArray()));
    }
}
