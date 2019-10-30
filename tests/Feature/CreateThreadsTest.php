<?php

namespace Tests\Feature;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cannot_create_threads()
    {
        $this->get(route('threads.create'))->assertRedirect('login');
        $this->post(route('threads.store'), [])->assertRedirect('login');
    }

    /** @test */
    function authenticated_user_can_create_threads()
    {
        $user = factory(User::class)->create();
        $this->assertCount(0, $user->threads);
        $channel = factory(Channel::class)->create();

        $response = $this->actingAs($user)->post(route('threads.store'), [
            'channel_id' => $channel->id,
            'title' => 'My first thread',
            'body' => 'Lorem ipsum dolor sit amet'
        ]);

        $threads = $user->fresh()->threads;
        $this->assertCount(1, $threads);
        $response->assertRedirect($threads->first()->path());
    }

    /** @test */
    function title_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('threads.store'), [
            'title' => '',
            'body' => 'Lorem ipsum dolor sit amet'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    function body_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('threads.store'), [
            'title' => 'Title',
            'body' => ''
        ]);

        $response->assertSessionHasErrors('body');
    }
}
