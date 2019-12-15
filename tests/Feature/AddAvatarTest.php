<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function guests_cannot_upload_avatars()
    {
        $this->uploadAvatar()->assertUnauthorized();
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->actingAs($this->user)
            ->uploadAvatar('not-a-valid-avatar')
            ->assertJsonValidationErrors('avatar');
    }

    /** @test */
    public function a_user_may_add_avatar_to_their_profile()
    {
        Storage::fake();
        
        $avatar = UploadedFile::fake()->image('avatar.jpg');
        
        $this->actingAs($this->user)->uploadAvatar($avatar)->assertSuccessful();

        $this->assertUploadedAndSavedToUsersAccount($avatar);
    }

    private function uploadAvatar($avatar = null)
    {
        return $this->json('POST', "/api/users/{$this->user}/avatar", [
            'avatar' => $avatar
        ]);
    }

    private function assertUploadedAndSavedToUsersAccount($avatar)
    {
        $avatarPath = "avatars/{$avatar->hashName()}";

        $this->assertEquals($avatarPath, $this->user->avatar_path);

        Storage::disk('public')->assertExists($avatarPath);
        Storage::disk('public')->delete($avatarPath);
    }
}
