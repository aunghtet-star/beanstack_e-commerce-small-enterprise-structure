<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'current_team_id' => $user->current_team_id,
                    'all_teams' => Jetstream::userHasTeamFeatures($user) ? $user->allTeams()->values() : null,
                    'current_team' => Jetstream::userHasTeamFeatures($user) ? $user->currentTeam : null,
                ] : null,
            ],
            'jetstream' => [
                'canCreateTeams' => $user &&
                                    Jetstream::userHasTeamFeatures($user) &&
                                    Gate::forUser($user)->check('create', Jetstream::newTeamModel()),
                'hasTeamFeatures' => Jetstream::hasTeamFeatures(),
                'hasApiFeatures' => Jetstream::hasApiFeatures(),
                'hasTermsAndPrivacyPolicyFeature' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
                'managesProfilePhotos' => Jetstream::managesProfilePhotos(),
                'hasAccountDeletionFeatures' => Jetstream::hasAccountDeletionFeatures(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
        ];
    }
}
