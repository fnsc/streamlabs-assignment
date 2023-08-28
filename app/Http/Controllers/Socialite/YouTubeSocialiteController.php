<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;
use App\Services\Account\Auth\SocialiteInput;
use App\Services\Account\Auth\SocialiteService;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

class YouTubeSocialiteController extends Controller
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
        $cookie = $this->getCookie($user);
        Auth::login($user, true);

        return redirect('dashboard')->withCookie($cookie);
    }

    private function getInput(User $user): SocialiteInput
    {
        return new SocialiteInput(
            name: $user->getName(),
            email: $user->getEmail(),
            avatar: $user->getAvatar()
        );
    }

    private function getCookie(UserModel $user): SymfonyCookie
    {
        $accessToken = $user->createToken('spa-token')->accessToken;

        return Cookie::make('token', $accessToken, 100080, null, null, false, false);
    }
}
