<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = factory(User::class)->create(['name' => 'JohnDoe']);
        $jane = factory(User::class)->create(['name' => 'JaneDoe']);

        $this->actingAs($john)->mention($jane);

        $this->assertCount(1, $jane->notifications);
    }

    private function mention($user)
    {
        return $this->post(route('replies.store', $this->thread), [
            'body' => "Hey @{$user->name}, have just mentioned you!"
        ]);
    }
}
