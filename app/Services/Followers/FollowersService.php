<?php

namespace App\Services\Followers;

use App\Repositories\FollowerRepository;

class FollowersService
{
    public function __construct(
        private readonly FollowerRepository $followerRepository
    ) {
    }

    public function handle(FollowersInput $input): int
    {
        return $this->followerRepository->getFollowerCountLast30Days($input);
    }
}
