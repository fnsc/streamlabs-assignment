<?php

namespace App\Repositories;

use App\Models\Donation;
use App\Models\MerchSale;
use App\Models\Subscriber;
use App\Services\StreamEventsList\EventListInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EventListRepository
{
    public function __construct(
        private readonly Donation $donationModel,
        private readonly Subscriber $subscriberModel,
        private readonly MerchSale $merchSaleModel
    ) {
    }

    public function getEventList(EventListInput $evenListInput): LengthAwarePaginator
    {
        $userId = $evenListInput->getUser()->id;

        $donations = $this->getDonationsQuery($userId);
        $subscribers = $this->getSubscribersQuery($userId);
        $merchSales = $this->getMerchSaleQuery($userId);

        $query = $donations->unionAll($subscribers)->unionAll($merchSales);

        return $query->orderBy('created_at', 'desc')
            ->paginate($evenListInput->getPerPage());
    }

    private function getDonationsQuery(int $userId): Builder
    {
        return $this->donationModel->where('streamer_id', $userId)
            ->where('read_status', false)
            ->selectRaw("
                id,
                CONCAT('RandomUser', FLOOR(RAND() * 1000), ' donated ', amount, ' USD to you!') as message,
                read_status,
                'donation' AS type,
                created_at
            ");
    }

    private function getSubscribersQuery(int $userId): Builder
    {
        return $this->subscriberModel->where('streamer_id', $userId)
            ->where('read_status', false)
            ->selectRaw("
                id,
                CONCAT('RandomUser', FLOOR(RAND() * 1000), ' (Tier', tier, ') subscribed to you!') as message,
                read_status,
                'subscriber' AS type,
                created_at
            ");
    }

    private function getMerchSaleQuery(int $userId): Builder
    {
        return $this->merchSaleModel->where('streamer_id', $userId)
            ->where('read_status', false)
            ->selectRaw("
                id,
                CONCAT('RandomUser', FLOOR(RAND() * 1000), ' bought ', name, ' from you for ', amount, ' USD!') as message,
                read_status,
                'merch_sale' AS type,
                created_at
            ");
    }
}
