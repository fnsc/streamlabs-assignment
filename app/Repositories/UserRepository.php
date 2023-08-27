<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\Account\Auth\SocialiteInput;

class UserRepository
{
    public function __construct(private readonly User $model)
    {
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(SocialiteInput $input): User
    {
        return $this->model->create([
            'name' => $input->getName(),
            'email' => $input->getEmail(),
            'avatar' => $input->getAvatar(),
        ]);
    }

    public function update(User $user, SocialiteInput $input): User
    {
        $user->update([
            'name' => $input->getName(),
            'avatar' => $input->getAvatar(),
        ]);

        return $user->fresh();
    }
}
