<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
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
    public function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');

        $response->assertSuccessful();
        $response->assertViewIs('threads.index');
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_thread()
    {
        $response = $this->get($this->thread->path());

        $response->assertSuccessful();
        $response->assertViewIs('threads.show');
        $response->assertSee($this->thread->title);
        $response->assertSee($this->thread->body);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $response = $this->get(route('replies.index', $reply->thread));

        $response->assertSee($reply->body);
    }

    /** @test */
    public function user_can_filter_threads_by_their_channel()
    {
        $channel = factory(Channel::class)->create();
        $threadInChannel = factory(Thread::class)->create(['channel_id' => $channel->id]);
        $otherThread = factory(Thread::class)->create();

        $response = $this->get("threads/{$channel->slug}");

        $response->assertSee($threadInChannel->title);
        $response->assertDontSee($otherThread->title);
    }

    /** @test */
    public function user_can_filter_threads_by_any_username()
    {
        $john = factory(User::class)->create(['name' => 'John']);
        $threadByJohn = factory(Thread::class)->create(['user_id' => $john->id]);
        $otherThread = factory(Thread::class)->create();

        $response = $this->get('/threads?by=John');

        $response->assertSee($threadByJohn->title);
        $response->assertDontSee($otherThread->title);
    }

    /** @test */
    public function user_can_filter_threads_by_popularity()
    {
        // Arrange: Two threads, one with 3 replies, one with 2 replies, and one with no replies
        $threadWithThreeReplies = factory(Thread::class)->create(['created_at' => now()->subDay()]);
        $threadWithTwoReplies = factory(Thread::class)->create(['created_at' => now()->addDay()]);
        $threadWithNoReplies = factory(Thread::class)->create(['created_at' => now()->subWeek()]);

        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReplies->id]);
        factory(Reply::class, 3)->create(['thread_id' => $threadWithTwoReplies->id]);
        factory(Reply::class, 3)->create(['thread_id' => $threadWithNoReplies->id]);

        // Act: When We filter by popularity (eg. /threads?popular)
        $response = $this->get('threads?popular');

        // Assert: Threads should be returned in proper order (by replies count)
        $response->assertSeeInOrder([
            $threadWithThreeReplies->title,
            $threadWithTwoReplies->title,
            $threadWithNoReplies->title
        ]);
    }

    /** @test */
    public function user_can_filter_unanswered_threads()
    {
        $threadWithThreeReplies = factory(Thread::class)->create();
        $threadAWithNoReplies = factory(Thread::class)->create();
        $threadBWithNoReplies = factory(Thread::class)->create();

        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $response = $this->get('threads?unanswered');

        $response->assertDontSee($threadWithThreeReplies->title);
        $response->assertSee($threadAWithNoReplies->title);
        $response->assertSee($threadBWithNoReplies->title);
    }
}
