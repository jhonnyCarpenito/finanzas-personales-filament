<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;

final class SocialiteController extends Controller
{
    private const PROVIDER_GOOGLE = 'google';

    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        $intent = request()->query('intent', 'login');
        if ($intent === 'link' && Auth::check()) {
            session()->put('socialite_link_provider', $provider);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        $socialiteUser = Socialite::driver($provider)->user();

        if (session()->pull('socialite_link_provider') === $provider) {
            return $this->handleLinkAccount($socialiteUser);
        }

        return $this->handleLogin($socialiteUser, $provider);
    }

    private function handleLogin(SocialiteUser $socialiteUser, string $provider): RedirectResponse
    {
        $user = $this->findOrCreateUser($socialiteUser, $provider);

        if ($user->isBlocked()) {
            return redirect()->route('filament.admin.auth.login')
                ->with('error', __('Tu cuenta ha sido bloqueada. Contacta al administrador.'));
        }

        Auth::login($user, remember: true);
        session()->regenerate();

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }

    private function handleLinkAccount(SocialiteUser $socialiteUser): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return redirect()->route('filament.admin.auth.login');
        }

        $existingUser = User::where('google_id', $socialiteUser->getId())->first();
        if ($existingUser && $existingUser->id !== $user->id) {
            return redirect()->route('filament.admin.pages.dashboard')
                ->with('error', __('Esta cuenta de Google ya está asociada a otro usuario.'));
        }

        $user->update([
            'google_id' => $socialiteUser->getId(),
            'google_email' => $socialiteUser->getEmail(),
        ]);

        return redirect()->route('filament.admin.auth.profile')
            ->with('success', __('Cuenta de Google asociada correctamente.'));
    }

    private function findOrCreateUser(SocialiteUser $socialiteUser, string $provider): User
    {
        $user = User::where('google_id', $socialiteUser->getId())->first();

        if ($user) {
            return $user;
        }

        $user = User::where('email', $socialiteUser->getEmail())->first();

        if ($user) {
            $user->update([
                $provider . '_id' => $socialiteUser->getId(),
                'google_email' => $socialiteUser->getEmail(),
            ]);

            return $user;
        }

        return User::create([
            'google_id' => $socialiteUser->getId(),
            'google_email' => $socialiteUser->getEmail(),
            'name' => $socialiteUser->getName() ?? $socialiteUser->getEmail(),
            'email' => $socialiteUser->getEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(64)),
        ]);
    }

    private function validateProvider(string $provider): void
    {
        if ($provider !== self::PROVIDER_GOOGLE) {
            abort(404);
        }
    }
}
