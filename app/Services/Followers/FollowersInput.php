<?php

namespace App\Services\Followers;

use App\Models\User;
use DateTime;

class FollowersInput
{
    public function __construct(private User $user, private DateTime $now)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getNow(): DateTime
    {
        return $this->now;
    }
}
