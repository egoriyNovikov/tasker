<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_profile(): void
    {
        $this->patchJson('/api/me', ['name' => 'New name'])
            ->assertUnauthorized();
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patchJson('/api/me', [
                'name' => 'New name',
                'telegram_login' => '@tasker_user',
                'password' => 'new-password',
            ])
            ->assertOk()
            ->assertJsonPath('name', 'New name')
            ->assertJsonPath('telegram_login', 'tasker_user')
            ->assertJsonMissingPath('password');

        $user->refresh();

        $this->assertSame('tasker_user', $user->telegram_login);
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_user_can_keep_own_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patchJson('/api/me', ['email' => $user->email])
            ->assertOk();
    }

    public function test_user_cannot_take_email_or_telegram_login_of_another_user(): void
    {
        $otherUser = User::factory()->create(['telegram_login' => 'taken']);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patchJson('/api/me', [
                'email' => $otherUser->email,
                'telegram_login' => '@taken',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'telegram_login']);
    }
}
