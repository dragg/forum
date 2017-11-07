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

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $reply = create(Reply::class);

        $this->delete(route('replies.delete', $reply))
            ->assertRedirect('login');

        $this->signIn()
            ->deleteJson(route('replies.delete', $reply))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this
            ->deleteJson(route('replies.delete', $reply))
            ->assertStatus(302);

        $this->assertDatabaseMissing($reply->getTable(), ['id' => $reply->id]);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $reply = create(Reply::class);

        $this->patch(route('replies.update', $reply))
            ->assertRedirect('login');

        $this->signIn()
            ->patchJson(route('replies.update', $reply))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $updatedReply = 'New changed body.';

        $this
            ->patch(route('replies.update', $reply), ['body' => $updatedReply]);

        $this->assertDatabaseHas($reply->getTable(), [
            'id' => $reply->id,
            'body' => $updatedReply
        ]);
    }

}
