<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * @var Thread
     */
    private $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    public function it_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function it_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function it_can_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
        ], create(User::class));

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function it_can_be_subscribed_to()
    {
        $thread = $this->thread;

        $this->signIn();
        $user = auth()->user();

        $thread->subscribe($user);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $user->id)->count()
        );
    }

    /** @test */
    public function it_can_be_unsubscribed_from()
    {
        $thread = $this->thread;

        $this->signIn();
        $user = auth()->user();

        $thread->subscribe($user);

        $thread->unsubscribe($user);

        $this->assertCount(
            0,
            $thread->subscriptions
        );
    }

    /** @test */
    public function it_know_if_the_user_is_subscribed_to_it()
    {
        $thread = $this->thread;

        $user = create(User::class);

        $this->assertFalse($thread->isSubscribedTo($user));

        $thread->subscribe($user);

        $this->assertTrue($thread->isSubscribedTo($user));
    }

    /** @test */
    public function it_know_authorized_user_is_subscribed_to_it()
    {
        $thread = $this->thread;

        $user = create(User::class);
        $this->signIn($user);

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe($user);

        $this->assertTrue($thread->isSubscribedTo);
    }

}
