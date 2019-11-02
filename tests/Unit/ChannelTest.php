<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_have_threads()
    {
        $channel = factory(Channel::class)->create();
        $threadInChannel = factory(Thread::class)->create(['channel_id' => $channel->id]);
        $otherThread = factory(Thread::class)->create();

        $this->assertTrue($channel->threads->contains($threadInChannel));
        $this->assertFalse($channel->threads->contains($otherThread));

    }
}
