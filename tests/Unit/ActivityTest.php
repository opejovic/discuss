<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_activity_whenever_a_thread_is_created()
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
    public function it_records_activity_whenever_a_reply_is_created()
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

    /** @test */
    public function it_fetches_feed_for_any_user()
    {
        auth()->login($user = factory(User::class)->create());
        $threadFromLastWeek = factory(Thread::class)->create(['created_at' => now()->subWeek()]);
        $threadFromToday = factory(Thread::class)->create();

        Activity::where('subject_id', $threadFromLastWeek->id)
            ->first()
            ->update(['created_at' => now()->subWeek()]);

        $response = $this->get("profiles/{$user->name}");

        $response->assertSeeInOrder([
            $threadFromToday->title,
            $threadFromLastWeek->title
        ]);
    }
}
