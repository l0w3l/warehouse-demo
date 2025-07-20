<?php

declare(strict_types=1);

namespace App\Services\StockHistory;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class StockHistoryServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): StockHistoryServiceInterface
    {
        return App::make(StockHistoryService::class, $params);
    }
}
