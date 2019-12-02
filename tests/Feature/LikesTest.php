<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function authenticated_user_can_like_any_thread()
    {
        $response = $this->actingAs($this->user)->post(route('likes.store', $this->thread));

        $response->assertCreated();
        $this->assertEquals(1, $this->thread->likes()->count());
    }

    /** @test */
    public function authenticated_user_cannot_like_any_thread_more_than_once()
    {
        auth()->login($this->user);
        $this->thread->like();
        $this->assertEquals(1, $this->thread->likes()->count());

        $response = $this->post(route('likes.store', $this->thread));

        $response->assertForbidden();
        $this->assertEquals(1, $this->thread->fresh()->likes()->count());
    }

    /** @test */
    public function authenticated_user_can_unlike_any_thread_they_have_liked()
    {
        auth()->login($this->user);
        $this->thread->like();
        $this->assertEquals(1, $this->thread->likes()->count());

        $response = $this->delete(route('likes.destroy', $this->thread));

        $response->assertSuccessful();
        $this->assertEquals(0, $this->thread->fresh()->likes()->count());
    }
}
