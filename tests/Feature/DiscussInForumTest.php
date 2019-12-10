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
    public function guests_cannot_discuss_in_forum()
    {
        $this->post('threads/1/replies', [])->assertRedirect('login');
    }

    /** @test */
    public function authenticated_users_can_discuss_in_threads()
    {
        $this->actingAs($this->user)
            ->from($this->thread->path())
            ->post(route('replies.store', $this->thread), [
                'body' => 'Very nice read! Keep up the good work!'
            ]);

        $this->assertEquals(1, Reply::all()->count());
        $this->assertTrue(Reply::first()->author->is($this->user));
    }

    /** @test */
    public function in_order_to_save_a_reply_a_body_is_required()
    {
        $response = $this->actingAs($this->user)
            ->from($this->thread->path())
            ->post(route('replies.store', $this->thread), [
                'body' => ''
            ]);

        $response->assertSessionHasErrors('body');
        $this->assertEquals(0, Reply::all()->count());
    }

    /** @test */
    public function in_order_to_save_a_reply_a_body_cannot_contain_spam()
    {
        $response = $this->actingAs($this->user)
            ->from($this->thread->path())
            ->post(route('replies.store', $this->thread), [
                'body' => 'AAAAAAAAAAAAAAA'
            ]);

        $response->assertSessionHasErrors('body');
        $this->assertEquals(0, Reply::all()->count());
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $johnsReply = factory(Reply::class)->create([
            'user_id' => $this->user->id
        ]);

        $this->actingAs($this->user)->delete(route('replies.destroy', $johnsReply));

        $this->assertEquals(0, $this->user->replies()->count());
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $jane = factory(User::class)->create();
        $johnsReply = factory(Reply::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($jane)->delete(route('replies.destroy', $johnsReply));

        $response->assertForbidden();
        $this->assertEquals(1, $this->user->replies()->count());
    }

    /** @test */
    public function authorized_users_can_edit_replies()
    {
        $johnsReply = factory(Reply::class)->create([
            'user_id' => $this->user->id,
            'body' => 'Example reply',
        ]);

        $this->actingAs($this->user)->patch(route('replies.update', $johnsReply), [
            'body' => 'The updated reply'
        ]);

        $this->assertEquals('The updated reply', $johnsReply->fresh()->body);
    }

    /** @test */
    public function unauthorized_users_cannot_edit_replies()
    {
        $jane = factory(User::class)->create();
        $johnsReply = factory(Reply::class)->create([
            'user_id' => $this->user->id,
            'body' => 'Example reply'
        ]);

        $response = $this->actingAs($jane)->patch(route('replies.update', $johnsReply), [
            'body' => 'The updated reply'
        ]);

        $response->assertForbidden();
        $this->assertEquals('Example reply', $johnsReply->fresh()->body);
    }

    /** @test */
    public function users_can_reply_only_once_per_minute()
    {
        $this->assertCount(0, Reply::all());

        $this->replyTwice();

        $this->assertOnlyOneReplyHasBeenCreated();
    }

    private function replyTwice()
    {
        $this->actingAs($this->user)
            ->post(
                route('replies.store', $this->thread),
                [
                    'body' => 'First reply. It should be saved.'
                ]
            )->assertCreated();

        $this->actingAs($this->user)
            ->post(
                route('replies.store', $this->thread),
                [
                    'body' => 'Second reply, immediately after the first. It should not be allowed.'
                ]
            );
    }

    private function assertOnlyOneReplyHasBeenCreated()
    {
        $this->assertCount(1, Reply::all());
    }
}
