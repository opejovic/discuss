<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authenticated_user_can_like_any_thread()
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create();

        $response = $this->actingAs($user)->post(route('likes.store', $thread));

        $response->assertCreated();
        $this->assertEquals(1, $thread->likes()->count());
    }

    /** @test */
    function authenticated_user_cannot_like_any_thread_more_than_once()
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create();

        auth()->login($user);
        $thread->like();
        $this->assertEquals(1, $thread->likes()->count());

        $response = $this->post(route('likes.store', $thread));

        $response->assertForbidden();
        $this->assertEquals(1, $thread->fresh()->likes()->count());
    }
}

