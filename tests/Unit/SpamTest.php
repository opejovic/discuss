<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Utilities\Spam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Reply without spam here.'));
    }
}
