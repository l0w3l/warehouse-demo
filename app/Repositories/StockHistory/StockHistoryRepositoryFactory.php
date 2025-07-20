<?php

declare(strict_types=1);

namespace App\Repositories\StockHistory;

use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class StockHistoryRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): StockHistoryRepositoryInterface
    {
        return new StockHistoryRepository;
    }
}
