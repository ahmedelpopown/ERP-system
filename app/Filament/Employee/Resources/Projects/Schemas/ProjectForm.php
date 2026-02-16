<?php

namespace App\Filament\Employee\Resources\Projects\Schemas;

use App\Models\City;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;


use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;


class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()

                    ->tabs([

                        Tab::make('Project information')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('address')
                                    ->required(),

                                RichEditor::make('details')
                                    ->default(null)
                                    ->columnSpanFull(),



                                Select::make('city_id')
                                    ->label('المدينة')
                                    ->options(City::pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('اسم المدينة')
                                            ->required(),
                                    ])
                                    ->createOptionUsing(function (array $data, callable $get) {
                                        $city = City::create([
                                            'name' => $data['name'],
                                        ]);

                                        // رجع الـ id فقط، مش الـ model كامل
                                        return $city->id;
                                    }),




                                FileUpload::make('files')
                                    ->multiple()
                                    ->directory('projects')
                                    ->preserveFilenames(),

DatePicker::make('start_at')
    ->label('تاريخ البداية')
    ->required()
    ->minDate(now())
    ->reactive()
    ->afterStateUpdated(function ($set, $get, $state) {
        $state = Carbon::parse($state);
        $end = $get('end_at') ? Carbon::parse($get('end_at')) : null;

        if ($end && $end->lt($state)) {
            $set('end_at', $state->format('Y-m-d'));
            $end = $state;
        }

        if ($end) {
            $diff = $end->diffInDays($state);
            $set('duration', $diff); // القيمة هتبعت للـ database
        }
    }),

DatePicker::make('end_at')
    ->label('تاريخ الانتهاء')
    ->required()
    ->reactive()
    ->afterStateUpdated(function ($set, $get, $state) {
        $state = Carbon::parse($state);
        $start = $get('start_at') ? Carbon::parse($get('start_at')) : now();

        if ($state->lt($start)) {
            $set('end_at', $start->format('Y-m-d'));
            $state = $start;
        }

        $diff = $state->diffInDays($start);
        $set('duration', $diff); // القيمة هتبعت للـ database
    })
    ->minDate(function ($get) {
        return $get('start_at') ?? now();
    }),

TextInput::make('duration')
    ->label('المدة (أيام)')
    ->numeric()
    ->reactive()
    ->dehydrated() // تأكد إن القيمة هتتبعت حتى لو الحقل disabled
    ->disabled(),
                            ]),








                        Tab::make('Client Information')
                            ->schema([
                                Section::make('Select or Add Client')
                                    ->schema([
                                        Select::make('client_id')
                                            ->label('Select Client')
                                            ->relationship('client', 'name')
                                            ->searchable()
                                            ->placeholder('Select an existing client'),

                                        Action::make('createClient')
                                            ->label('Add New Client')
                                            ->button()
                                            ->modalHeading('Add New Client')
                                            ->form([
                                                TextInput::make('name')
                                                    ->label('Client Name')
                                                    ->required(),
                                                TextInput::make('email')
                                                    ->label('Email')
                                                    ->email()
                                                    ->required(),
                                                TextInput::make('phone')
                                                    ->label('Phone')
                                                    ->required(),
                                                TextInput::make('address')
                                                    ->label('Address')
                                                    ->required(),
                                            ])
                                            ->action(function ($data, $form) {
                                                // create new client
                                                $client = \App\Models\Client::create($data);

                                                // set the form state for the new client_id
                                                $form->fill([
                                                    'client_id' => $client->id,
                                                ]);
                                            }),
                                    ])
                                    ->columns(1),
                            ]),






                        Tab::make('payment Duration')
                            ->schema([
                                Section::make('Select or Add payments')
                                    ->schema([
                                        Select::make('pricing_id')
                                            ->label('Select Pricing')
                                            ->relationship('pricing', 'name')
                                            ->searchable()
                                            ->placeholder('Select an existing payment'),

                                        Action::make('createPayment')
                                            ->label('Add New Payment')
                                            ->button()
                                            ->modalHeading('Add New Payment')
                                            ->form([
                                                TextInput::make('name')
                                                    ->label('Payment Name')
                                                    ->required(),
                                                TextInput::make('budget')
                                                    ->label('budget')
                                                    ->numeric()
                                                    ->required(),
                                                TextInput::make('payments')
                                                    ->label('payments')
                                                    ->numeric()

                                                    ->required(),
                                                TextInput::make('duration')
                                                    ->label('duration')->numeric()
                                                    ->required(),
                                            ])
                                            ->action(function ($data, $form) {
                                                // create new client
                                                $pricing = \App\Models\Pricing::create($data);

                                                // set the form state for the new client_id
                                                $form->fill([
                                                    'pricing_id' => $pricing->id,
                                                ]);
                                            }),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->activeTab(1)



            ]);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
{
    if (isset($data['start_at'], $data['end_at'])) {
        $data['duration'] = Carbon::parse($data['end_at'])->diffInDays(Carbon::parse($data['start_at']));
    }
    return $data;
}
}
