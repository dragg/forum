<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $user = create(User::class);
        $thread = create(Thread::class)->subscribe($user);

        $this->assertCount(0, $user->notifications);

        $thread->addReply((make(Reply::class))->toArray(), $user);

        $this->assertCount(0, $user->fresh()->notifications);

        $thread->addReply((make(Reply::class))->toArray(), create(User::class));

        $this->assertCount(1, $user->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();
        $user = auth()->user();

        /** @var Thread $thread */
        $thread = create(Thread::class)->subscribe($user);

        $thread->addReply((make(Reply::class))->toArray(), create(User::class));

        $response = $this->getJson(route('notifications.index'))
            ->assertStatus(200)
            ->decodeResponseJson();

        $this->assertCount(1, $response);
    }


    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();
        $user = auth()->user();

        /** @var Thread $thread */
        $thread = create(Thread::class)->subscribe($user);

        $thread->addReply((make(Reply::class))->toArray(), create(User::class));

        $this->assertCount(1, $user->unreadNotifications);

        $notification = $user->unreadNotifications->first();

        $this->deleteJson(route('notifications.delete', $notification))
            ->assertStatus(200);

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }

}
