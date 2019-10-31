<?php

namespace Tests\Feature;

use App\Reply;
use App\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Thread;

class DiscussInForumTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    function guests_cannot_discuss_in_forum()
    {
        $this->post('threads/asd/1/replies', [])->assertRedirect('login');
    }

    /** @test */
    function authenticated_users_can_discuss_in_threads()
    {
        $response = $this->actingAs($this->user)
            ->from($this->thread->path())
            ->post(route('replies.store', [$this->thread->channel, $this->thread]), [
                'body' => 'Very nice read! Keep up the good work!'
            ]);

        $response->assertRedirect($this->thread->path());
        $this->get($this->thread->path())->assertSee('Very nice read! Keep up the good work!');
        $this->assertEquals(1, Reply::all()->count());
        $this->assertTrue(Reply::first()->author->is($this->user));
    }

    /** @test */
    function in_order_to_save_a_reply_a_body_is_required()
    {
        $response = $this->actingAs($this->user)
            ->from($this->thread->path())
            ->post(route('replies.store', [$this->thread->channel, $this->thread]), [
                'body' => ''
            ]);

        $response->assertSessionHasErrors('body');
        $this->assertEquals(0, Reply::all()->count());
    }
}
