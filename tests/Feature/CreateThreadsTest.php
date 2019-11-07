<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cannot_create_threads()
    {
        $this->get(route('threads.create'))->assertRedirect('login');
        $this->post(route('threads.store'), [])->assertRedirect('login');
    }

    /** @test */
    function authenticated_user_can_create_threads()
    {
        $user = factory(User::class)->create();
        $this->assertCount(0, $user->threads);
        $channel = factory(Channel::class)->create(['id' => 1]);

        $response = $this->actingAs($user)->post(route('threads.store'), [
            'channel_id' => $channel->fresh()->id,
            'title' => 'My first thread',
            'body' => 'Lorem ipsum dolor sit amet'
        ]);

        $threads = $user->fresh()->threads;
        $this->assertCount(1, $threads);
        $response->assertRedirect($threads->first()->path());
    }

    /** @test */
    function title_is_required()
    {
        $this->publishThread([
            'title' => null,
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    function body_is_required()
    {
        $this->publishThread([
            'body' => null,
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    function valid_channel_is_required()
    {
        $this->publishThread([
           'channel_id' => 999
        ])->assertSessionHasErrors('channel_id');

        $this->publishThread([
           'channel_id' => null
        ])->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function channel_must_be_existing_channel()
    {
        $this->publishThread([
           'channel_id' => '123123'
        ])->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function guests_cannot_delete_any_threads()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->delete(route('threads.destroy', $thread));

        $response->assertRedirect('login');
        $this->assertEquals(1, Thread::count());
    }

    /** @test */
    function thread_can_be_deleted_by_its_author()
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create(['user_id' => $user->id]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response = $this->actingAs($user)->delete(route('threads.destroy', $thread));

        $response->assertRedirect('home');
        $this->assertEquals(0, Thread::count());
        $this->assertEquals(0, Reply::count());
    }

    /** @test */
    function thread_cannot_be_deleted_by_unauthorized_members()
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create();

        $response = $this->actingAs($user)->delete(route('threads.destroy', $thread));

        $response->assertStatus(403);
        $this->assertEquals(1, Thread::count());
    }

    public function publishThread($overrides)
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->make($overrides)->toArray();

        return $this->actingAs($user)->post(route('threads.store'), $thread);
    }
}
