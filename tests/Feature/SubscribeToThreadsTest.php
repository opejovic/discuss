<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cannot_subscribe_to_threads()
    {
        $this->post(route('threads.subscribe', 1))->assertRedirect('login');
    }

    /** @test */
    function authenticated_users_can_subscribe_to_threads()
    {
        $thread = factory(Thread::class)->create();
        $john = factory(User::class)->create();
        $jane = factory(User::class)->create();

        $response = $this->actingAs($john)->post(route('threads.subscribe', $thread));
        $response->assertSuccessful();
        $this->assertTrue($thread->subscriptions()->where('user_id', $john->id)->exists());

        // @TODO

        // $thread->addReply([
        //     'user_id' => $jane->id,
        //     'body' => 'Some very constructive reply.'
        // ]);

        // $this->assertCount(1, $john->notifications);
    }

    /** @test */
    function authenticated_users_can_subscribe_to_thread_only_once()
    {
        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();
        $thread->subscribe($user->id);

        $response = $this->actingAs($user)->post(route('threads.subscribe', $thread));

        $response->assertForbidden();
    }
}
