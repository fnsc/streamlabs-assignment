<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AbstractRepository
{
    public function __construct(private readonly Model $model)
    {
    }

    public function updateStatus(int $id, bool $status, User $user): bool
    {
        $model = $this->model->where('id', $id)
            ->where('streamer_id', $user->id)
            ->first();

        return $model->update(['read_status' => $status]);
    }
}
