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

    protected $thread;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function guests_cannot_subscribe_to_threads()
    {
        $this->post(route('threads.subscribe', 1))->assertRedirect('login');
    }

    /** @test */
    public function authenticated_users_can_subscribe_to_threads()
    {
        $response = $this->actingAs($this->user)->post(route('threads.subscribe', $this->thread));

        $response->assertSuccessful();
        $this->assertTrue($this->thread->subscriptions()->where('user_id', $this->user->id)->exists());
    }

    /** @test */
    public function authenticated_users_can_unsubscribe_from_threads()
    {
        $this->thread->subscribe($this->user->id);

        $this->actingAs($this->user)->delete(route('threads.unsubscribe', $this->thread));

        $this->assertFalse($this->user->isSubscribedTo($this->thread));
    }

    /** @test */
    public function authenticated_users_can_subscribe_to_thread_only_once()
    {
        $this->thread->subscribe($this->user->id);

        $response = $this->actingAs($this->user)->post(route('threads.subscribe', $this->thread));

        $response->assertForbidden();
    }
}
