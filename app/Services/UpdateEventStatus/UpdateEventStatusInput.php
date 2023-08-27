<?php

namespace App\Services\UpdateEventStatus;

use App\Models\User;

class UpdateEventStatusInput
{
    public function __construct(
        private readonly User $user,
        private readonly bool $status,
        private readonly int $id,
        private readonly string $type
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
