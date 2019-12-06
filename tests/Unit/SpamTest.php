<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Inspections\Spam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spam = new Spam();
    }

    /** @test */
    public function it_validates_spam()
    {
        $this->assertFalse($this->spam->detect('Reply without spam here.'));

        $this->expectException(\Exception::class);

        $this->spam->detect('google customer support');
    }

    /** @test */
    function it_checks_for_any_key_being_held_down()
    {
        $this->expectException(\Exception::class);

        $this->spam->detect('AAAAAAAAAAAAA');
    }
}
