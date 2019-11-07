<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_records_activity_whenever_a_thread_is_created()
    {
        $user = factory(User::class)->create();
        auth()->login($user);
        $thread = factory(Thread::class)->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => $user->id,
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $this->assertTrue(Activity::first()->subject->id == $thread->id);
    }

    /** @test */
    function it_records_activity_whenever_a_reply_is_created()
    {
        $user = factory(User::class)->create();
        auth()->login($user);
        $reply = factory(Reply::class)->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_reply',
            'user_id' => $user->id,
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);

        $activity = Activity::where('subject_type', 'App\Reply')->first();
        $this->assertTrue($activity->subject->id == $reply->id);
    }
}
