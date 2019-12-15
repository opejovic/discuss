<?php

namespace Tests\Unit;

use App\Thread;
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

        // Set the current route. It is temporary.
        $this->post(route('replies.store', factory(Thread::class)->create()));
    }

    /** @test */
    public function inspection_doesnt_pass_if_text_contains_invalid_keywords()
    {
        $this->assertStringContainsString('you are trying to spam', $this->spam->inspect('have win apple iphone'));
    }

    /** @test */
    public function inspection_doesnt_pass_if_any_key_is_being_held_down()
    {
        $this->assertStringContainsString('Holding down keys much?', $this->spam->inspect('AAAAAAAAAAA'));
    }
}
