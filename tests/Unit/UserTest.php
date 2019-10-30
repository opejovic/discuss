<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_have_many_threads()
    {
        $user = factory(User::class)->create();
        factory(Thread::class)->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->threads);
    }

    /** @test */
    function it_can_publish_a_thread()
    {
        $user = factory(User::class)->create();
        $user->publishThread([
            'channel_id' => factory(Channel::class)->create()->id,
            'title' => 'Sample title',
            'body' => 'Sample body'
        ]);

        $this->assertCount(1, $user->threads);
    }
}
