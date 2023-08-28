<?php

namespace App\Repositories;

use App\Models\Subscriber;

class SubscriberRepository extends AbstractRepository
{
    public function __construct(Subscriber $model)
    {
        parent::__construct($model);
    }
}
