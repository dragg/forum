<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $thread = make(Thread::class);

        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));
    }
    
    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('threads.show', [1]));

        $this->get(route('threads.index'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
