<?php

namespace Tests\Unit;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
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
    function it_belongs_to_a_channel()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }
}
