<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->signIn();
    }


    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $user = auth()->user();

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
        create(DatabaseNotification::class);

        $unreadNotifications = $this->getJson(route('notifications.index'))
            ->assertStatus(200)
            ->json();

        $this->assertCount(1, $unreadNotifications);
    }


    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $user = auth()->user();

        create(DatabaseNotification::class);

        $this->assertCount(1, $user->unreadNotifications);

        $this->deleteJson(route('notifications.delete', $user->unreadNotifications->first()))
            ->assertStatus(200);

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }

}
