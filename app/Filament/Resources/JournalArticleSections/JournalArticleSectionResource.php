<?php

namespace App\Filament\Resources\JournalArticleSections;

use App\Filament\Resources\JournalArticleSections\Pages\ManageJournalArticleSections;
use App\Models\JournalArticle;
use App\Models\JournalArticleSection;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JournalArticleSectionResource extends Resource
{
    protected static ?string $model = JournalArticleSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Article Sections';

    protected static ?string $recordTitleAttribute = 'heading';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Section')
                ->schema([
                    Select::make('journal_article_id')
                        ->label('Article')
                        ->options(fn () => JournalArticle::query()->orderBy('id', 'desc')->pluck('title', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('heading')->required(),
                    TagsInput::make('body')
                        ->label('Paragraphs')
                        ->helperText('Add one paragraph per item.')
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('pull_quote')->label('Pull Quote')->columnSpanFull(),
                    TagsInput::make('bullets')->columnSpanFull(),
                ])->columns(2),
            Section::make('Optional Image')
                ->schema([
                    TextInput::make('image_src')->label('Image Path')->columnSpanFull(),
                    TextInput::make('image_alt')->label('Alt Text')->columnSpanFull(),
                    TextInput::make('image_caption')->label('Caption')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('article.title')->label('Article')->searchable()->limit(40),
                TextColumn::make('heading')->searchable()->limit(45),
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
            'index' => ManageJournalArticleSections::route('/'),
        ];
    }
}
