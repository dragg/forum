<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_consists_of_threads()
    {
        $channel = create(Channel::class);

        $thread = create(Thread::class, ['channel_id' => $channel]);

        $this->assertInstanceOf(Collection::class, $channel->threads);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
