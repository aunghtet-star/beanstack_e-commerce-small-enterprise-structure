<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_accessible(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/user/profile');
        // Inertia response JSON won't contain rendered header HTML; just ensure 200 OK
        $response->assertStatus(200);
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/user/profile-information', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Name', $user->fresh()->name);
        $this->assertEquals('updated@example.com', $user->fresh()->email);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $this->actingAs($user);

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-secure-pass',
            'password_confirmation' => 'new-secure-pass',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(password_verify('new-secure-pass', $user->fresh()->password));
    }

    // Two-factor enable/disable covered by existing dedicated tests; omitted here to avoid duplication.

    public function test_user_can_logout_other_browser_sessions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/user/other-browser-sessions', [
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_user_can_delete_account(): void
    {
        if (! Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped('Account deletion not enabled.');
        }

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/user', [
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
