<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SocialiteController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $providers = [
            'facebook',
            'twitter',
            'linkedin',
            'google',
            'github',
            'gitlab',
            'bitbucket',
            'slack',
        ];
        $socialAccounts = $request->user()->userSocialAccounts;
        $providers = array_udiff($providers, $socialAccounts->pluck('provider')->toArray(), fn($a, $b) => $a <=> $b);

        return Inertia::render('settings/Socialite', [
            'socialAccounts' => $socialAccounts,
            'providers' => $providers,
        ]);
    }
}
