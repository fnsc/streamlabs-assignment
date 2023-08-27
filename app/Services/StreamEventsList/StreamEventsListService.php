<?php

namespace App\Services\StreamEventsList;

use App\Repositories\EventListRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class StreamEventsListService
{
    public function __construct(private readonly EventListRepository $repository)
    {
    }

    public function handle(EventListInput $evenListInput): LengthAwarePaginator
    {
        return $this->repository->getEventList($evenListInput);
    }
}
