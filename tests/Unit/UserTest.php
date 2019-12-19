<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_can_have_many_threads()
    {
        factory(Thread::class)->create(['user_id' => $this->user->id]);

        $this->assertCount(1, $this->user->threads);
    }

    /** @test */
    public function it_can_publish_a_thread()
    {
        $this->user->publishThread([
            'channel_id' => factory(Channel::class)->create()->id,
            'title' => 'Sample title',
            'body' => 'Sample body'
        ]);

        $this->assertCount(1, $this->user->threads);
    }

    /** @test */
    public function it_has_activities()
    {
        auth()->login($this->user);

        $this->user->publishThread([
            'channel_id' => factory(Channel::class)->create()->id,
            'title' => 'Sample title',
            'body' => 'Sample body'
        ]);

        $this->assertEquals(1, $this->user->fresh()->activities()->count());
    }

    /** @test */
    public function it_can_have_many_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->user->replies);
    }

    /** @test */
    public function it_can_tell_if_its_subscribed_to_a_thread()
    {
        $thread = factory(Thread::class)->create();
        $thread->subscribe($this->user->id);
        $this->assertTrue($thread->subscriptions()->where('user_id', $this->user->id)->exists());

        $this->assertTrue($this->user->isSubscribedTo($thread));
    }

    /** @test */
    public function it_knows_if_it_has_replied_recently()
    {
        // If user has a reply that was updated in the past minute.
        factory(Reply::class)->create(['user_id' => $this->user->id]);

        $this->assertTrue($this->user->hasRepliedRecently());
    }

    /** @test */
    public function it_has_a_placeholder_avatar_if_there_is_none_uploaded()
    {
        $user = factory(User::class)->create();
        $this->assertEquals('http://localhost/images/avatar-placeholder.svg', $this->user->avatar_path);

        $user->update(['avatar_path' => 'avatars/sampleavatarimage.jpeg']);
        $this->assertEquals('http://localhost/avatars/sampleavatarimage.jpeg', $user->avatar_path);
    }
}
