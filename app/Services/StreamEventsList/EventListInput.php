<?php

namespace App\Services\StreamEventsList;

use App\Models\User;

class EventListInput
{
    public function __construct(
        private readonly User $user,
        private readonly ?bool $status = false,
        private readonly int $perPage = 100
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
