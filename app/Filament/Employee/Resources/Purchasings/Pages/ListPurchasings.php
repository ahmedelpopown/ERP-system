<?php

namespace App\Filament\Employee\Resources\Purchasings\Pages;

use App\Filament\Employee\Resources\Purchasings\PurchasingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchasings extends ListRecords
{
    protected static string $resource = PurchasingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
