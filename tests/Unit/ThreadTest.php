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
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_returns_a_string_representation_of_its_path()
    {
        $thread = factory(Thread::class)->make(['id' => 1]);

        $this->assertEquals("threads/{$thread->channel->slug}/1", $thread->path());
    }

    /** @test */
    public function it_can_have_many_replies()
    {
        $replyA = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $replyB = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $replyC = factory(Reply::class)->create();

        $this->assertTrue($this->thread->replies->contains($replyA));
        $this->assertTrue($this->thread->replies->contains($replyB));
        $this->assertFalse($this->thread->replies->contains($replyC));
    }

    /** @test */
    public function it_has_an_author()
    {
        $thread = factory(Thread::class)->create(['user_id' => $this->user->id]);
        $this->assertTrue($thread->author->is($this->user));
    }

    /** @test */
    public function it_can_return_published_at_date()
    {
        $thread = factory(Thread::class)->create(['created_at' => '2019-04-10 00:00:00']);

        $this->assertEquals('10 Apr 2019', $thread->published_at);
    }

    /** @test */
    public function a_reply_can_be_added_to_it()
    {
        $this->assertEquals(0, $this->thread->replies->count());

        $this->thread->addReply([
            'user_id' => 1,
            'body' => 'Lorem ipsum'
        ]);

        $this->assertEquals(1, $this->thread->fresh()->replies->count());
    }

    /** @test */
    public function it_notifies_all_subscribers_when_a_new_reply_is_added_to_it()
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
    public function it_belongs_to_a_channel()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function it_can_be_liked()
    {
        auth()->login($this->user);

        $this->thread->like();

        $this->assertEquals(1, $this->thread->likes()->count());
    }

    /** @test */
    public function it_knows_if_it_has_been_liked()
    {
        auth()->login($this->user);
        $this->thread->like();

        $this->assertTrue($this->thread->hasBeenLiked);
    }

    /** @test */
    public function it_can_be_unliked()
    {
        auth()->login($this->user);
        $this->thread->like();
        $this->assertTrue($this->thread->hasBeenLiked);

        $this->thread->unlike();
        $this->assertFalse($this->thread->fresh()->hasBeenLiked);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $this->thread->subscribe($userId = 1);

        $this->assertTrue($this->thread->subscriptions()->where('user_id', $userId)->exists());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $this->thread->subscribe($userId = 1);
        $this->assertTrue($this->thread->subscriptions()->where('user_id', $userId)->exists());

        $this->thread->unsubscribe($userId);
        $this->assertFalse($this->thread->fresh()->subscriptions()->where('user_id', $userId)->exists());
    }

    /** @test */
    public function it_can_tell_if_logged_in_user_has_read_all_of_its_replies()
    {
        auth()->login($this->user);
        $this->thread->subscribe();

        $this->thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'Random reply'
        ]);

        $this->assertTrue($this->thread->fresh()->hasUpdatesFor($this->user));

        $this->user->read($this->thread);
        $this->assertFalse($this->thread->fresh()->hasUpdatesFor($this->user));
    }
}
