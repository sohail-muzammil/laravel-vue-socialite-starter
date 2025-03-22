<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSocialite;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Exception;
use Inertia\Inertia;

class UserSocialiteController extends Controller
{
    public function redirect(string $driver): RedirectResponse
    {
        return Inertia::location(
            Socialite::driver($driver)->stateless()->redirect()->getTargetUrl()
        );
    }

    public function callback(string $driver): RedirectResponse
    {
        try {
            $socialiteUser = Socialite::driver($driver)->stateless()->user();

            if (Auth::check()) {
                try {
                    $this->connectSocialAccount(Auth::user(), $socialiteUser, $driver);
                    return to_route('dashboard')->with('success', ucfirst($driver) . ' account connected successfully.');
                } catch (Exception $e) {
                    return to_route('dashboard')->withErrors(['error' => $e->getMessage()]);
                }
            }

            $user = User::with('userSocialAccounts')
                ->where('email', $socialiteUser->getEmail())
                ->first();

            if ($user) {
                if (!$this->hasSocialAccount($user, $driver, $socialiteUser->getId())) {
                    return to_route('login')->withErrors([
                        'error' => ucfirst($driver) . ' account not connected. Sign up or use another method.'
                    ]);
                }

                Auth::login($user);
            } else {
                $userSocialAccount = $this->findOrCreateProviderUser($socialiteUser, $driver);
                Auth::login($userSocialAccount->user);
            }

            request()->session()->regenerate();
            return to_route('dashboard');
        } catch (Exception $e) {
            Log::error('Socialite callback error: ' . $e->getMessage());
            return to_route('login')->withErrors(['error' => 'Login failed. Please try again.']);
        }
    }

    private function findOrCreateProviderUser(SocialiteUser $socialiteUser, string $driver): UserSocialite
    {
        return DB::transaction(function () use ($socialiteUser, $driver) {
            $providerAccount = UserSocialite::firstOrNew([
                'provider' => $driver,
                'social_id' => $socialiteUser->getId(),
            ]);

            if ($providerAccount->exists) {
                return $providerAccount;
            }

            $user = User::firstOrCreate(
                ['email' => $socialiteUser->getEmail()],
                [
                    'name' => $socialiteUser->getName(),
                    'email_verified_at' => now(),
                ]
            );

            return $this->createSocialAccount($user, $socialiteUser, $driver);
        });
    }

    private function createSocialAccount(User $user, SocialiteUser $socialiteUser, string $driver): UserSocialite
    {
        return $user->userSocialAccounts()->create([
            'provider' => $driver,
            'social_id' => $socialiteUser->getId(),
            'nickname' => $socialiteUser->getNickname(),
            'name' => $socialiteUser->getName(),
            'email' => $socialiteUser->getEmail(),
            'avatar' => $socialiteUser->getAvatar(),
            'data' => json_encode($socialiteUser->user),
            'token' => $socialiteUser->token,
            'refresh_token' => $socialiteUser->refreshToken,
            'token_expires_at' => $socialiteUser->expiresIn ? now()->addSeconds($socialiteUser->expiresIn) : null,
        ]);
    }

    private function connectSocialAccount(User $user, SocialiteUser $socialiteUser, string $driver): void
    {
        if (UserSocialite::where('provider', $driver)
            ->where('social_id', $socialiteUser->getId())
            ->exists()
        ) {
            throw new Exception(ucfirst($driver) . ' account is already linked to another user.');
        }

        if ($user->userSocialAccounts()->where('provider', $driver)->exists()) {
            throw new Exception(ucfirst($driver) . ' account is already connected.');
        }

        $this->createSocialAccount($user, $socialiteUser, $driver);
    }

    public function disconnect(string $driver): RedirectResponse
    {
        try {
            $socialAccount = Auth::user()->userSocialAccounts()
                ->where('provider', $driver)
                ->firstOrFail();

            $socialAccount->delete();
            return to_route('dashboard')->with('success', ucfirst($driver) . ' account disconnected.');
        } catch (Exception $e) {
            return to_route('dashboard')->withErrors(['error' => 'Disconnection failed. Try again.']);
        }
    }

    private function hasSocialAccount(User $user, string $driver, string $socialId): bool
    {
        return $user->userSocialAccounts
            ->where('provider', $driver)
            ->where('social_id', $socialId)
            ->isNotEmpty();
    }
}
