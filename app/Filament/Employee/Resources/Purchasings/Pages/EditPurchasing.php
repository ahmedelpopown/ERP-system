<?php

namespace App\Filament\Employee\Resources\Purchasings\Pages;

use App\Filament\Employee\Resources\Purchasings\PurchasingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchasing extends EditRecord
{
    protected static string $resource = PurchasingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
