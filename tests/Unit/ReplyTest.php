<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    /**
     * @var Reply
     */
    private $reply;

    protected function setUp()
    {
        parent::setUp();

        $this->reply = create(Reply::class);
    }

    /** @test */
    public function it_has_an_owner()
    {
        $this->assertInstanceOf(User::class, $this->reply->owner);
    }

    /** @test */
    public function it_has_a_thread()
    {
        $this->assertInstanceOf(Thread::class, $this->reply->thread);
    }
}
