<?php

namespace App\Filament\Resources\JournalSettings;

use App\Filament\Resources\JournalSettings\Pages\ManageJournalSettings;
use App\Models\JournalSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JournalSettingResource extends Resource
{
    protected static ?string $model = JournalSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Journal Settings';

    protected static ?string $recordTitleAttribute = 'page_title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('page_eyebrow')->required(),
            TextInput::make('page_title')->required()->columnSpanFull(),
            Textarea::make('page_body')->required()->columnSpanFull(),
            TextInput::make('filter_label')->required(),
            TextInput::make('default_category_slug')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_eyebrow'),
                TextColumn::make('page_title')->limit(50),
                TextColumn::make('default_category_slug'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageJournalSettings::route('/'),
        ];
    }
}
