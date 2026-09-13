<?php

namespace App\Filament\Resources\JournalArticleFaqs;

use App\Filament\Resources\JournalArticleFaqs\Pages\ManageJournalArticleFaqs;
use App\Models\JournalArticleFaq;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JournalArticleFaqResource extends Resource
{
    protected static ?string $model = JournalArticleFaq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $navigationLabel = 'Article FAQs';

    protected static ?string $recordTitleAttribute = 'question';

    // Hidden from the sidebar — managed via the Article form.
    // Access directly at /admin/journal-article-faqs if needed.
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('question')->required()->columnSpanFull(),
            Textarea::make('answer')->required()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('question')->searchable()->limit(60),
                TextColumn::make('answer')->limit(80)->wrap(),
            ])
            ->recordActions([
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
            'index' => ManageJournalArticleFaqs::route('/'),
        ];
    }
}
