<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function callback(): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();

            // 1. Check if user with this google_id already exists
            $user = User::where('google_id', $googleUser->getId())->first();

            if (! $user) {
                // 2. Check if user with this email already exists
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Link existing email user with google_id
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                } else {
                    // 3. Create brand new user
                    $user = User::create([
                        'name' => $googleUser->getName() ?: 'User InvoiceRapi',
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => null, // Password nullable for Google users
                        'role' => 'tenant_admin',
                        'email_verified_at' => now(),
                    ]);
                }
            }

            Auth::login($user, remember: true);

            if ($user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            // If user has not completed onboarding (or has no tenant yet), redirect to onboarding
            if (! $user->tenant_id || ! optional($user->tenant)->onboarding_completed) {
                return redirect()->route('onboarding.index');
            }

            return redirect()->intended(route('dashboard'));
        } catch (Throwable $e) {
            Log::error('Google Auth Failed: ' . $e->getMessage());

            return redirect()->route('login')->withErrors([
                'email' => 'Gagal masuk menggunakan Google. Silakan coba lagi atau gunakan email & password.',
            ]);
        }
    }
}

