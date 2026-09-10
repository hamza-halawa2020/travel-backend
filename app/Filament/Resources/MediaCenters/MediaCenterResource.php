<?php

namespace App\Filament\Resources\MediaCenters;

use App\Filament\Resources\MediaCenters\Pages\ManageMediaCenters;
use App\Models\MediaCenter;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaCenterResource extends Resource
{
    protected static ?string $model = MediaCenter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function getModelLabel(): string
    {
        return __('Media Center');
    }

    public static function canAccess(): bool
    {
        return true;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Media Centers');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required(),
                Select::make('type')
                    ->label(__('Type'))
                    ->options([
                        'image' => __('Image'),
                        'video' => __('Video'),
                    ])
                    ->required()
                    ->live(),
                FileUpload::make('file')
                    ->label(__('File'))
                    ->visible(fn ($get) => in_array($get('type'), ['image', 'audio', 'document']))
                    ->required(fn ($get) => in_array($get('type'), ['image', 'audio', 'document'])),
                TextInput::make('video_url')
                    ->label(__('Video URL / Link'))
                    ->visible(fn ($get) => $get('type') === 'video')
                    ->required(fn ($get) => $get('type') === 'video'),
                Toggle::make('status')
                    ->label(__('Status'))
                    ->default(true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label(__('Title')),
                TextEntry::make('type')
                    ->label(__('Type')),
                ImageEntry::make('file')
                    ->label(__('Image'))
                    ->visible(fn ($record) => $record->type === 'image'),
                TextEntry::make('video_url')
                    ->label(__('Video URL'))
                    ->visible(fn ($record) => $record->type === 'video'),
                IconEntry::make('status')
                    ->boolean()
                    ->label(__('Status')),
                TextEntry::make('user.name')
                    ->label(__('Created By')),
                TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                ImageColumn::make('file')
                    ->label(__('Preview'))
                    ->visible(fn ($record) => $record?->type === 'image'),
                IconColumn::make('status')
                    ->boolean()
                    ->label(__('Status')),
                TextColumn::make('user.name')
                    ->label(__('Created By'))
                    ->sortable(),
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
            'index' => ManageMediaCenters::route('/'),
        ];
    }
}
