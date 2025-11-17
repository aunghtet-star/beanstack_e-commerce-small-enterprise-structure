<?php

namespace Tests\Unit;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Tests\TestCase;

class AddTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_member_can_be_added_to_team(): void
    {
        Event::fake([AddingTeamMember::class, TeamMemberAdded::class]);

        $teamOwner = User::factory()->withPersonalTeam()->create();
        $team = $teamOwner->currentTeam;
        $newMember = User::factory()->create();

        $action = new AddTeamMember();
        $action->add($teamOwner, $team, $newMember->email, 'editor');

        $this->assertTrue($team->fresh()->hasUser($newMember));
        Event::assertDispatched(AddingTeamMember::class);
        Event::assertDispatched(TeamMemberAdded::class);
    }

    public function test_validation_fails_for_invalid_email(): void
    {
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $team = $teamOwner->currentTeam;

        Gate::shouldReceive('forUser')
            ->with($teamOwner)
            ->andReturnSelf()
            ->shouldReceive('authorize')
            ->with('addTeamMember', $team);

        $action = new AddTeamMember();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $action->add($teamOwner, $team, 'invalid-email');
    }

    public function test_validation_fails_for_nonexistent_user(): void
    {
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $team = $teamOwner->currentTeam;

        Gate::shouldReceive('forUser')
            ->with($teamOwner)
            ->andReturnSelf()
            ->shouldReceive('authorize')
            ->with('addTeamMember', $team);

        $action = new AddTeamMember();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $action->add($teamOwner, $team, 'nonexistent@example.com');
    }

    public function test_validation_fails_when_user_already_on_team(): void
    {
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $team = $teamOwner->currentTeam;
        $existingMember = User::factory()->create();
        $team->users()->attach($existingMember);

        Gate::shouldReceive('forUser')
            ->with($teamOwner)
            ->andReturnSelf()
            ->shouldReceive('authorize')
            ->with('addTeamMember', $team);

        $action = new AddTeamMember();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $action->add($teamOwner, $team, $existingMember->email);
    }
}
