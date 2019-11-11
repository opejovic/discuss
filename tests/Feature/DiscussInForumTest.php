<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $john = factory(User::class)->create();
        $thread = factory(Thread::class)->create();
        $johnsReply = factory(Reply::class)->create([
            'user_id' => $john->id
        ]);

        $this->actingAs($john)->delete(route('replies.destroy', [$thread, $johnsReply]));

        $this->assertEquals(0, $john->replies()->count());
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $john = factory(User::class)->create();
        $jane = factory(User::class)->create();
        $thread = factory(Thread::class)->create();
        $johnsReply = factory(Reply::class)->create([
            'user_id' => $john->id
        ]);

        $response = $this->actingAs($jane)->delete(route('replies.destroy', [$thread, $johnsReply]));

        $response->assertForbidden();
        $this->assertEquals(1, $john->replies()->count());
    }
}
