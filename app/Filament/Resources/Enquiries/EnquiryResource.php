<?php

namespace App\Filament\Resources\Enquiries;

use App\Filament\Resources\Enquiries\Pages\ManageEnquiries;
use App\Models\Enquiry;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'Enquiry';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Enquiries';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name')->label('Name'),
            TextEntry::make('phone')->label('Phone / WhatsApp'),
            TextEntry::make('email')->label('Email')->placeholder('-'),
            TextEntry::make('service')->label('Service')->placeholder('-'),
            TextEntry::make('field_a')->label('Field A')->placeholder('-'),
            TextEntry::make('field_b')->label('Field B')->placeholder('-'),
            TextEntry::make('details')->label('Details')->columnSpanFull()->placeholder('-'),
            TextEntry::make('created_at')->label('Submitted At')->dateTime()->placeholder('-'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('phone')->label('Phone')->searchable(),
                TextColumn::make('service')->label('Service')->searchable()->placeholder('-'),
                TextColumn::make('field_a')->label('Field A')->searchable()->placeholder('-')->toggleable(),
                TextColumn::make('field_b')->label('Field B')->searchable()->placeholder('-')->toggleable(),
                TextColumn::make('details')->label('Details')->limit(60)->placeholder('-')->toggleable(),
                TextColumn::make('created_at')->label('Submitted At')->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ManageEnquiries::route('/'),
        ];
    }
}
