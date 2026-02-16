<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review Information')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required()
                            ->preload(),
                        Select::make('reviewer_id')
                            ->relationship('reviewer', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        TextInput::make('review_period')
                            ->required()
                            ->default(now()->format('Y-m-d'))
                            ->placeholder('Select Review Period'),

                    ]),

                Section::make('Performance_of_Work')
                    ->columns(2)
                    ->schema([
                        TextInput::make('quality_of_work')
                            ->required()
                            ->minValue(1)
                            ->maxValue(10)
                            ->live()
                            ->numeric()
                            ->afterStateUpdated(fn($state,Set $set, Get $get) =>
                                self::calculateOverViewRating($set, $get)),
                        TextInput::make('productivity')
                            ->required()
                            ->minValue(1)
                            ->maxValue(10)
                            ->live()
                            ->numeric()
                            ->afterStateUpdated(fn($state,Set $set, Get $get) =>
                                self::calculateOverViewRating($set, $get)),
                        TextInput::make("communication")
                            ->required()
                            ->minValue(1)
                            ->maxValue(10)
                            ->live()
                            ->numeric()
                            ->afterStateUpdated(fn($state,Set $set, Get $get) =>
                                self::calculateOverViewRating($set, $get)),
                        TextInput::make('teamwork')
                            ->required()
                            ->minValue(1)
                            ->maxValue(10)
                            ->live()
                            ->numeric()
                            ->afterStateUpdated(fn($state,Set $set, Get $get) =>
                                self::calculateOverViewRating($set, $get)),
                        TextInput::make("leadership")
                            ->required()
                            ->minValue(1)
                            ->maxValue(10)
                            ->live()
                            ->numeric()
                            ->afterStateUpdated(fn($state,Set $set, Get $get) =>
                                self::calculateOverViewRating($set, $get)),
                                TextInput::make("overall_rating")
                                ->suffix('/ 10')
                                ->dehydrated()
                                ->disabled()
                                ->numeric()
                                ->required()
                    ]),

                Section::make('Feedback and Goals')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('strengths')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('area_for_improvement')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('goals')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('comments')
                            ->default(null)
                            ->columnSpanFull()
                    ]),
            ]);
    }

    protected static function calculateOverViewRating(Set $set,Get $get)
    {
        $quality_of_work =(int)$get('quality_of_work');
        $productivity =(int)$get('productivity');
        $communication =(int)$get('communication');
        $teamwork =(int)$get('teamwork');
        $leadership =(int)$get('leadership');
        $overviewRating = round(($quality_of_work + $productivity + $communication + $teamwork + $leadership )/5, 2);
        $set("overall_rating", $overviewRating);
    }
}