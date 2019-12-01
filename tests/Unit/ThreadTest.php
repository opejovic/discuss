<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    function it_returns_a_string_representation_of_its_path()
    {
        $thread = factory(Thread::class)->make(['id' => 1]);

        $this->assertEquals("threads/{$thread->channel->slug}/1", $thread->path());
    }

    /** @test */
    function it_can_have_many_replies()
    {
        $replyA = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $replyB = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $replyC = factory(Reply::class)->create();

        $this->assertTrue($this->thread->replies->contains($replyA));
        $this->assertTrue($this->thread->replies->contains($replyB));
        $this->assertFalse($this->thread->replies->contains($replyC));
    }

    /** @test */
    function it_has_an_author()
    {
        $john = factory(User::class)->create();
        $thread = factory(Thread::class)->create(['user_id' => $john->id]);
        $this->assertTrue($thread->author->is($john));
    }

    /** @test */
    function it_can_return_published_at_date()
    {
        $thread = factory(Thread::class)->create(['created_at' => '2019-04-10 00:00:00']);

        $this->assertEquals('10 Apr 2019', $thread->published_at);
    }

    /** @test */
    function a_reply_can_be_added_to_it()
    {
        $this->assertEquals(0, $this->thread->replies->count());

        $this->thread->addReply([
            'user_id' => 1,
            'body' => 'Lorem ipsum'
        ]);

        $this->assertEquals(1, $this->thread->fresh()->replies->count());
    }

    /** @test */
    function it_notifies_all_subscribers_when_a_new_reply_is_added_to_it()
    {
        Notification::fake();

        $john = factory(User::class)->create();
        $jane = factory(User::class)->create();
        $jack = factory(User::class)->create();

        $this->thread->subscribe($john->id);
        $this->thread->subscribe($jane->id);
        $this->thread->subscribe($jack->id);

        $this->thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'Lorem ipsum'
        ]);

        Notification::assertSentTo($john, ThreadWasUpdated::class);
        Notification::assertSentTo($jane, ThreadWasUpdated::class);
        Notification::assertSentTo($jack, ThreadWasUpdated::class);
    }

    /** @test */
    function it_belongs_to_a_channel()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    function it_can_be_liked()
    {
        auth()->login(factory(User::class)->create());

        $this->thread->like();

        $this->assertEquals(1, $this->thread->likes()->count());
    }

    /** @test */
    function it_knows_if_it_has_been_liked()
    {
        auth()->login(factory(User::class)->create());
        $this->thread->like();

        $this->assertTrue($this->thread->hasBeenLiked);
    }

    /** @test */
    function it_can_be_unliked()
    {
        auth()->login(factory(User::class)->create());
        $this->thread->like();
        $this->assertTrue($this->thread->hasBeenLiked);

        $this->thread->unlike();
        $this->assertFalse($this->thread->fresh()->hasBeenLiked);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = factory(Thread::class)->create();

        $thread->subscribe($userId = 1);

        $this->assertTrue($thread->subscriptions()->where('user_id', $userId)->exists());
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        $thread = factory(Thread::class)->create();
        $thread->subscribe($userId = 1);
        $this->assertTrue($thread->subscriptions()->where('user_id', $userId)->exists());

        $thread->unsubscribe($userId);

        $this->assertFalse($thread->fresh()->subscriptions()->where('user_id', $userId)->exists());
    }
}
