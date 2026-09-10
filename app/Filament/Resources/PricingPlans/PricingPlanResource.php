<?php

namespace App\Filament\Resources\PricingPlans;

use App\Filament\Resources\PricingPlans\Pages\ManagePricingPlans;
use App\Models\PricingPlan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('Pricing Plan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Pricing Plans');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->label(__('Subtitle'))
                    ->maxLength(255),
                TextInput::make('currency')
                    ->label(__('Currency'))
                    ->default('USD')
                    ->required()
                    ->maxLength(10),
                TextInput::make('price')
                    ->label(__('Monthly Price'))
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                TextInput::make('classes_count')
                    ->label(__('Classes Count'))
                    ->numeric()
                    ->required()
                    ->minValue(1),
                TextInput::make('days_per_week')
                    ->label(__('Days Per Week'))
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(7),
                TextInput::make('minutes_per_class')
                    ->label(__('Minutes Per Class'))
                    ->numeric()
                    ->default(30)
                    ->required()
                    ->minValue(1),
                TextInput::make('price_per_class')
                    ->label(__('Price Per Class'))
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('badge')
                    ->label(__('Badge'))
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label(__('Sort Order'))
                    ->numeric()
                    ->default(0)
                    ->required(),
                Repeater::make('features')
                    ->label(__('Features'))
                    ->simple(
                        TextInput::make('feature')
                            ->label(__('Feature'))
                            ->required()
                    )
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->label(__('Featured')),
                Toggle::make('status')
                    ->label(__('Status'))
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')->label(__('Title')),
                TextEntry::make('subtitle')->label(__('Subtitle')),
                TextEntry::make('price')->label(__('Monthly Price'))->money('USD'),
                TextEntry::make('classes_count')->label(__('Classes')),
                TextEntry::make('days_per_week')->label(__('Days Per Week')),
                TextEntry::make('minutes_per_class')->label(__('Minutes Per Class')),
                TextEntry::make('price_per_class')->label(__('Price Per Class')),
                TextEntry::make('features')->label(__('Features'))->listWithLineBreaks(),
                IconEntry::make('is_featured')->label(__('Featured'))->boolean(),
                IconEntry::make('status')->label(__('Status'))->boolean(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label(__('Order'))
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('Monthly Price'))
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('classes_count')
                    ->label(__('Classes'))
                    ->sortable(),
                TextColumn::make('days_per_week')
                    ->label(__('Days / Week')),
                IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean(),
                IconColumn::make('status')
                    ->label(__('Status'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePricingPlans::route('/'),
        ];
    }
}
