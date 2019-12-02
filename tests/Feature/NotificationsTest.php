<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notification_is_prepared_when_the_reply_is_left_not_by_current_user()
    {
        $thread = factory(Thread::class)->create();
        $john = factory(User::class)->create();
        $jane = factory(User::class)->create();
        $thread->subscribe($john->id);
        $this->assertCount(0, $john->notifications);

        $thread->addReply([
            'user_id' => $john->id,
            'body' => 'Johns own reply.'
        ]);

        $this->assertCount(0, $john->fresh()->notifications);

        $thread->addReply([
            'user_id' => $jane->id,
            'body' => 'Some very constructive reply.'
        ]);

        $this->assertCount(1, $john->fresh()->notifications);
    }

    /** @test */
    public function user_can_fetch_his_notifications()
    {
        $john = factory(User::class)->create();
        auth()->login($john);
        factory(DatabaseNotification::class)->create();

        $this->assertCount(1, $john->fresh()->unreadNotifications);

        $response = $this->actingAs($john)->getJson(route('notifications.index'));

        $this->assertCount(1, $response->getOriginalContent());
    }

    /** @test */
    public function user_can_clear_a_notification()
    {
        $john = factory(User::class)->create();
        auth()->login($john);
        factory(DatabaseNotification::class)->create();


        $this->assertCount(1, $john->fresh()->notifications);

        $this->actingAs($john)->delete(
            route('notifications.destroy', $john->fresh()->unreadNotifications()->first())
        );

        $this->assertCount(0, $john->fresh()->notifications);
    }
}
