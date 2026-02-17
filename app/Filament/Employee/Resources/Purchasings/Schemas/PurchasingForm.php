<?php

namespace App\Filament\Employee\Resources\Purchasings\Schemas;

use App\Models\Material;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PurchasingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([




                        Tab::make('supplier')
                            ->schema([
                                Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                 ->searchable()
                                    ->createOptionForm([

                                        TextInput::make('name')
                                            ->label('name')

                                            ->placeholder('Please Enter Suppler name')
                                            ->required(),
                                        TextInput::make('email')
                                            ->label('email')
                                            ->placeholder('Please Enter Suppler email')
                                            ->required()
                                            ->email()
                                        ,
                                        TextInput::make('phone')
                                            ->label('phone')
                                            ->placeholder('Please Enter Suppler phone')
                                            ->required()
                                            ->numeric()
                                        ,
                                        TextInput::make('address')
                                            ->label('address')
                                            ->placeholder('Please Enter Suppler address')
                                            ->required()

                                        ,


                                    ]),


                                    // Select::make('name')

                               
   Select::make('user_id')
   ->relationship('user','name')->searchable()
    ->searchable(),
    FileUpload::make('attachments')
    ->multiple(),
    DateTimePicker::make('purchase_date')  ->default(now())
    ->required(),

 
                            ]),



Tab::make('material')
    ->schema([

        Repeater::make('items')
            ->relationship()
            ->live()
            ->afterStateUpdated(function (Get $get, Set $set) {

                $total = collect($get('items'))
                    ->sum(fn ($item) =>
                        ($item['quantity'] ?? 0) * ($item['price'] ?? 0)
                    );

                $set('total', $total);
            })
            ->schema([

                Select::make('material_id')
                    ->relationship('material', 'name')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $material = \App\Models\Material::find($state);

                        if ($material) {
                            $set('unit', $material->unit);
                        }
                    })
                    ->required(),

                Select::make('unit')
                    ->options([
                        'kg' => 'KG',
                        'piece' => 'Piece',
                        'meter' => 'Meter',
                    ])
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('total', ($get('quantity') ?? 0) * ($get('price') ?? 0));
                    }),

                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('total', ($get('quantity') ?? 0) * ($get('price') ?? 0));
                    }),

                TextInput::make('total')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->default(0),

            ])
            ->columns(5)
            ->required(),

        TextInput::make('total')
            ->numeric()
            ->disabled()
            ->dehydrated()
            ->default(0),

    ]),




                        Tab::make('inventory')
                            ->schema([
     

            TextInput::make('name')
                ->required(),

            TextInput::make('address'),

            TextInput::make('phone'),

            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->required(),

     

                            ]),
                    ])
            ]);
    }
}
