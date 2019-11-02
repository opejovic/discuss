<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_an_author()
    {
        $john = factory(User::class)->create();
        $reply = factory(Reply::class)->create(['user_id' => $john->id]);
        $this->assertTrue($john->is($reply->author));
    }
}
