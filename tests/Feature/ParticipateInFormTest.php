<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInFormTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post(route('threads.replies.store', [$thread]), $reply->toArray())
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post(route('threads.replies.store', [$thread]), $reply->toArray())
            ->assertRedirect(route('threads.show', [$thread]));

        $this->get(route('threads.show', $thread))
            ->assertSee($reply->body);
    }
}
