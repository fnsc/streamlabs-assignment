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
        $status = $evenListInput->getStatus();

        $donations = $this->getDonationsQuery($userId, $status);
        $subscribers = $this->getSubscribersQuery($userId, $status);
        $merchSales = $this->getMerchSaleQuery($userId, $status);

        $query = $donations->unionAll($subscribers)->unionAll($merchSales);

        return $query->orderBy('created_at', 'desc')
            ->paginate($evenListInput->getPerPage());
    }

    private function getDonationsQuery(int $userId, ?bool $status): Builder
    {
        $query = $this->donationModel->selectRaw("
                id,
                CONCAT('RandomUser', FLOOR(RAND() * 1000), ' donated ', amount, ' USD to you!') as message,
                read_status,
                'donation' AS type,
                created_at
            ")->where('streamer_id', $userId);

        if (is_null($status)) {
            return  $query;
        }

        return $query->where('read_status', $status);

    }

    private function getSubscribersQuery(int $userId, ?bool $status): Builder
    {
        $query = $this->subscriberModel->selectRaw("
                id,
                CONCAT(name, ' (Tier', tier, ') subscribed to you!') as message,
                read_status,
                'subscriber' AS type,
                created_at
            ")->where('streamer_id', $userId);

        if (is_null($status)) {
            return  $query;
        }

        return $query->where('read_status', $status);

    }

    private function getMerchSaleQuery(int $userId, ?bool $status): Builder
    {
        $query = $this->merchSaleModel->selectRaw("
                id,
                CONCAT('RandomUser', FLOOR(RAND() * 1000), ' bought ', amount, ' ', name, ' from you for ', (amount * unit_price) , ' USD!') as message,
                read_status,
                'merch_sale' AS type,
                created_at
            ")
            ->where('streamer_id', $userId);

        if (is_null($status)) {
            return  $query;
        }

        return $query->where('read_status', $status);
    }
}
