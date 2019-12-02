<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_a_profile()
    {
        $user = factory(User::class)->create();

        $response = $this->get(route('profile', $user));

        $response->assertSuccessful();
        $response->assertSee($user->name);
    }
}
