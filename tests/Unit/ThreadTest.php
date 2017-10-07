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
        ],create(User::class));

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }
}
