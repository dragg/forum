<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use Tests\TestCase;

class ParticipateInFormTest extends TestCase
{
    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post(route('threads.replies.store', [$thread]), $reply->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post(route('threads.replies.store', [$thread]), $reply->toArray());

        $this->get(route('threads.show', [$thread->channel->slug, $thread]))
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post(route('threads.replies.store', [$thread]), $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
