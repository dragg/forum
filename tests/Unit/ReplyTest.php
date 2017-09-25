<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Reply
     */
    private $reply;

    protected function setUp()
    {
        parent::setUp();

        $this->reply = factory(Reply::class)->create();
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
