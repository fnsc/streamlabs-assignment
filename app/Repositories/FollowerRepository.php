<?php

namespace App\Repositories;

use App\Models\Follower;
use App\Services\Followers\FollowersInput;
use DateTime;

class FollowerRepository
{
    public function __construct(private readonly Follower $model)
    {
    }

    public function getFollowerCountLast30Days(FollowersInput $input): int
    {
        $now = $input->getNow();
        $past = $this->getPast($now);

        return $this->model->where('streamer_id', $input->getUser()->id)
            ->whereBetween('created_at', [$past, $now])
            ->count();
    }

    private function getPast(DateTime $now, string $pastTime = '-30 days'): DateTime
    {
        $past = clone $now;
        $past->modify($pastTime);

        return $past;
    }
}
