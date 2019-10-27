<?php

namespace Tests\Unit;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_returns_a_string_representation_of_its_path()
    {
        $thread = factory(Thread::class)->make(['id' => 1]);
    
        $this->assertEquals('threads/1', $thread->path());
    }
}
