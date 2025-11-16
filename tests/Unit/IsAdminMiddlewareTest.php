<?php

namespace Tests\Unit;

use App\Http\Middleware\IsAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class IsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_admin_users_to_proceed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new IsAdmin;

        $response = $middleware->handle($request, function ($req) {
            return response('Success', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function it_blocks_regular_users_from_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new IsAdmin;

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $middleware->handle($request, function ($req) {
            return response('Success', 200);
        });
    }

    /** @test */
    public function it_blocks_unauthenticated_users(): void
    {
        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new IsAdmin;

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $middleware->handle($request, function ($req) {
            return response('Success', 200);
        });
    }
}
