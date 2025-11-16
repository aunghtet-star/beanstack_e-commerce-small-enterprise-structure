<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_check_if_user_is_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    /** @test */
    public function it_can_check_if_user_is_regular_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $this->assertFalse($admin->isUser());
        $this->assertTrue($regularUser->isUser());
    }

    /** @test */
    public function it_has_default_role_as_user(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('user', $user->role);
        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function it_can_have_role_attribute_set(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertEquals('admin', $user->role);
    }

    /** @test */
    public function it_can_update_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $this->assertTrue($user->isUser());
        
        $user->update(['role' => 'admin']);
        
        $this->assertTrue($user->fresh()->isAdmin());
        $this->assertFalse($user->fresh()->isUser());
    }
}
