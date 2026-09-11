<?php

namespace App\Filament\Resources\JournalArticleFaqs;

use App\Filament\Resources\JournalArticleFaqs\Pages\ManageJournalArticleFaqs;
use App\Models\JournalArticle;
use App\Models\JournalArticleFaq;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('journal_article_id')
                ->label('Article')
                ->options(fn () => JournalArticle::query()->orderBy('id', 'desc')->pluck('title', 'id'))
                ->searchable()
                ->required(),
            TextInput::make('question')->required()->columnSpanFull(),
            Textarea::make('answer')->required()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('article.title')->label('Article')->searchable()->limit(40),
                TextColumn::make('question')->searchable()->limit(55),
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
