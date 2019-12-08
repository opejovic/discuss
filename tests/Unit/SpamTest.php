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
    public function inspection_doesnt_pass_if_text_contains_invalid_keywords()
    {
        $this->assertFalse($this->spam->inspect('have win apple iphone'));
    }

    /** @test */
    function inspection_doesnt_pass_if_any_key_is_being_held_down()
    {
        $this->assertFalse($this->spam->inspect('AAAAAAAAAAAAA'));
    }
}
