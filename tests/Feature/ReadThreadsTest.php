<?php

namespace Tests\Feature;

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
        $response = $this->get(route('threads.show', $this->thread));

        $response->assertSuccessful();
        $response->assertViewIs('threads.show');
        $response->assertSee($this->thread->title);
        $response->assertSee($this->thread->body);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $response = $this->get("threads/{$this->thread->id}");

        $response->assertSuccessful();
        $response->assertSee($reply->body);
    }
}
