<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Services\Account\Auth\SocialiteInput;
use App\Services\Account\Auth\SocialiteService;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class YoutubeSocialiteController extends Controller
{
    public function __construct(private readonly SocialiteService $socialiteService)
    {
    }

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): Redirector|RedirectResponse
    {
        $user = Socialite::driver('google')->user();
        $input = $this->getInput($user);
        $user = $this->socialiteService->handle($input);
        Auth::login($user, true);

        return redirect('dashboard');
    }

    private function getInput(User $user): SocialiteInput
    {
        return new SocialiteInput(
            name: $user->getName(),
            email: $user->getEmail(),
            avatar: $user->getAvatar()
        );
    }
}
