<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->withPersonalTeam()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Admin/Dashboard')
        );
    }

    /** @test */
    public function regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->withPersonalTeam()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    /** @test */
    public function guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_dashboard_displays_correct_statistics(): void
    {
        // Create test data
        User::factory()->withPersonalTeam()->count(5)->create(['role' => 'user']);
        User::factory()->withPersonalTeam()->count(2)->create(['role' => 'admin']);

        $admin = User::factory()->withPersonalTeam()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Admin/Dashboard')
            ->has('stats')
            ->where('stats.totalUsers', 8) // 5 users + 2 admins + 1 current admin
            ->where('stats.adminUsers', 3)
        );
    }

    /** @test */
    public function admin_middleware_is_applied_to_admin_routes(): void
    {
        $user = User::factory()->withPersonalTeam()->create(['role' => 'user']);

        // Test that admin middleware blocks regular users
        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }
}
