<?php

namespace App\Filament\Resources\JournalArticles;

use App\Filament\Resources\JournalArticles\Pages\ManageJournalArticles;
use App\Models\JournalArticle;
use App\Models\JournalCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JournalArticleResource extends Resource
{
    protected static ?string $model = JournalArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Journal Articles';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Article')
                ->schema([
                    Select::make('journal_category_id')
                        ->label('Category')
                        ->options(fn () => JournalCategory::query()->orderBy('sort_order')->pluck('label', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    TextInput::make('read_time')->label('Read Time')->required(),
                    TextInput::make('title')->required()->columnSpanFull(),
                    Textarea::make('dek')->required()->columnSpanFull(),
                    Textarea::make('excerpt')->required()->columnSpanFull(),
                    TextInput::make('image')->required()->columnSpanFull(),
                    TextInput::make('alt')->required()->columnSpanFull(),
                    TextInput::make('author')->required(),
                    TextInput::make('updated_label')->label('Updated Label')->required(),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_published')->label('Published')->default(true),
                ])->columns(2),
            Section::make('CTA')
                ->schema([
                    TextInput::make('cta_title')->label('Title')->columnSpanFull(),
                    Textarea::make('cta_body')->label('Body')->columnSpanFull(),
                    TextInput::make('cta_label')->label('Button Label'),
                    TextInput::make('cta_href')->label('Button Link'),
                ])->columns(2),
            Section::make('Related Articles')
                ->schema([
                    Select::make('relatedArticles')
                        ->relationship(
                            'relatedArticles',
                            'title',
                            modifyQueryUsing: fn (Builder $query, ?JournalArticle $record) => $record
                                ? $query->whereKeyNot($record->id)
                                : $query,
                        )
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')->searchable()->limit(45),
                TextColumn::make('category.label')->label('Category')->sortable(),
                TextColumn::make('slug')->searchable()->toggleable(),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_published')->label('Published')->boolean(),
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
            'index' => ManageJournalArticles::route('/'),
        ];
    }
}
