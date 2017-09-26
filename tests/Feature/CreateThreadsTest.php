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
        $thread = factory(Thread::class)->make();

        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('login'));
    }
    
    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make();
        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('threads.show', [1]));

        $this->get(route('threads.index'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
