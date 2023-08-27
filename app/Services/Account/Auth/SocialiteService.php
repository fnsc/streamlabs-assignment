<?php

namespace App\Services\Account\Auth;

use App\Models\User;
use App\Repositories\UserRepository;

readonly class SocialiteService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function handle(SocialiteInput $input): User
    {
        if ($user = $this->userRepository->findByEmail($input->getEmail())) {
            return $this->userRepository->update($user, $input);
        }

        return $this->userRepository->create($input);
    }
}
