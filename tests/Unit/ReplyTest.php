<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
