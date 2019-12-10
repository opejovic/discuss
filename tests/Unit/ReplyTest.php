<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->john = factory(User::class)->create(['name' => 'JohnDoe']);
    }

    /** @test */
    public function it_has_an_author()
    {
        $reply = factory(Reply::class)->create(['user_id' => $this->john->id]);
        $this->assertTrue($this->john->is($reply->author));
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $jane = factory(User::class)->create(['name' => 'JaneDoe']);

        $reply = $this->mentionUsers($this->john, $jane);

        $this->assertThereAreTwoMentionedUsers($reply);
    }

    private function mentionUsers($john, $jane)
    {
        return factory(Reply::class)->create([
            'body' => "Mentioning @{$john->name}, and @{$jane->name}."
        ]);
    }

    private function assertThereAreTwoMentionedUsers($reply)
    {
        $this->assertCount(2, $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_users_name_in_an_anchor_tag()
    {
        $reply = factory(Reply::class)->create([
            'body' => "Hey @{$this->john->name}"
        ]);
        
        $this->assertEquals(
            'Hey <a href="/profiles/JohnDoe">@JohnDoe</a>',
            $reply->body
        );
    }
}
