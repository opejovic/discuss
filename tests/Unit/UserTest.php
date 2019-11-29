<?php

namespace Tests\Unit;

use App\User;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /** @test */
    function it_has_activities()
    {
        auth()->login($user = factory(User::class)->create());

        $user->publishThread([
            'channel_id' => factory(Channel::class)->create()->id,
            'title' => 'Sample title',
            'body' => 'Sample body'
        ]);

        $this->assertEquals(1, $user->fresh()->activities()->count());
    }

    /** @test */
    function it_can_have_many_replies()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->replies);
    }

    /** @test */
    function it_can_tell_if_its_subscribed_to_a_thread()
    {
        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();
        $thread->subscribe($user->id);
        $this->assertTrue($thread->subscriptions()->where('user_id', $user->id)->exists());

        $this->assertTrue($user->isSubscribedTo($thread));
    }
}
