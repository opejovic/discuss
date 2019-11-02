<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');

        $response->assertSuccessful();
        $response->assertViewIs('threads.index');
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_thread()
    {
        $response = $this->get($this->thread->path());

        $response->assertSuccessful();
        $response->assertViewIs('threads.show');
        $response->assertSee($this->thread->title);
        $response->assertSee($this->thread->body);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $response = $this->get($this->thread->path());

        $response->assertSuccessful();
        $response->assertSee($reply->body);
    }

    /** @test */
    function user_can_filter_threads_by_their_channel()
    {
        $this->withoutExceptionHandling();
        // Arrange - two threads, one in channel, and other not in channel
        $channel = factory(Channel::class)->create();
        $threadInChannel = factory(Thread::class)->create(['channel_id' => $channel->id]);
        $otherThread = factory(Thread::class)->create();

        // Act - get some endpoint
        $response = $this->get("threads/{$channel->slug}");

        // Assert - the thread in channel is returned only
        $response->assertSee($threadInChannel->title);
        $response->assertDontSee($otherThread->title);
    }
}
