<?php

namespace App\Repositories;

use App\Models\MerchSale;

class MerchSaleRepository extends AbstractRepository
{
    public function __construct(MerchSale $model)
    {
        parent::__construct($model);
    }
}
