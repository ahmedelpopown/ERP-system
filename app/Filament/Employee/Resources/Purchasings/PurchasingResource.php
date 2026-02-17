<?php

namespace App\Filament\Employee\Resources\Purchasings;

use App\Filament\Employee\Resources\Purchasings\Pages\CreatePurchasing;
use App\Filament\Employee\Resources\Purchasings\Pages\EditPurchasing;
use App\Filament\Employee\Resources\Purchasings\Pages\ListPurchasings;
use App\Filament\Employee\Resources\Purchasings\Schemas\PurchasingForm;
use App\Filament\Employee\Resources\Purchasings\Tables\PurchasingsTable;
use App\Models\InventoryMaterial;
use App\Models\Purchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchasingResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PurchasingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchasingsTable::configure($table);
    }

public static function canViewAny(): bool
{
    return auth()->user()->hasRole('purchase');
    
}
public static function shouldRegisterNavigation(): bool
{
    return auth()->user()->hasRole('purchase');
}

 protected function afterCreate(): void
{
    foreach ($this->record->items as $item) {
        InventoryMaterial::updateOrCreate(
            [
                'inventory_id' => 1, // لو عندك مخزن واحد
                'material_id' => $item->material_id,
            ],
            [
                'quantity' => \DB::raw("quantity + {$item->quantity}")
            ]
        );
    }
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchasings::route('/'),
            'create' => CreatePurchasing::route('/create'),
            'edit' => EditPurchasing::route('/{record}/edit'),
        ];
    }
}
