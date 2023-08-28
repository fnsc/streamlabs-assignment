<?php

namespace App\Services\Account\Auth;

final readonly class SocialiteInput
{
    public function __construct(private string $name, private string $email, private string $avatar)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}
