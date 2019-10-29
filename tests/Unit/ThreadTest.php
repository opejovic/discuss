<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_returns_a_string_representation_of_its_path()
    {
        $thread = factory(Thread::class)->make(['id' => 1]);

        $this->assertEquals('threads/1', $thread->path());
    }

    /** @test */
    function it_can_have_many_replies()
    {
        $thread = factory(Thread::class)->create();
        $replyA = factory(Reply::class)->create(['thread_id' => $thread->id]);
        $replyB = factory(Reply::class)->create(['thread_id' => $thread->id]);
        $replyC = factory(Reply::class)->create();

        $this->assertTrue($thread->replies->contains($replyA));
        $this->assertTrue($thread->replies->contains($replyB));
        $this->assertFalse($thread->replies->contains($replyC));
    }
}
