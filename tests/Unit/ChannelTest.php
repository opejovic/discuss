<?php

namespace Tests\Unit;

use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_threads()
    {
        $channel = factory(Channel::class)->create();
        $threadInChannel = factory(Thread::class)->create(['channel_id' => $channel->id]);
        $otherThread = factory(Thread::class)->create();

        $this->assertTrue($channel->threads->contains($threadInChannel));
        $this->assertFalse($channel->threads->contains($otherThread));
    }
}
