<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_browse_threads()
    {
        // Arrange
        $threadA = factory(Thread::class)->create();
        $threadB = factory(Thread::class)->create();

        // Act
        $response = $this->get('/threads');

        // Assert
        $response->assertSuccessful();
        $response->assertViewIs('threads.index');
        $response->assertSee($threadA->title);
        $response->assertSee($threadB->title);
    }

    /** @test */
    function a_user_can_read_a_thread()
    {
        // Arrange
        $thread = factory(Thread::class)->create();
    
        // Act
        $response = $this->get(route('threads.show', $thread));
    
        // Assert 
        $response->assertSuccessful();
        $response->assertViewIs('threads.show');
        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }
}
