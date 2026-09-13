<?php

namespace App\Filament\Resources\JournalArticles;

use App\Filament\Resources\JournalArticles\Pages\ManageJournalArticles;
use App\Models\JournalArticle;
use App\Models\JournalCategory;
use App\Support\NormalizesStorageImages;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
    use NormalizesStorageImages;
    protected static ?string $model = JournalArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Journal Articles';

    protected static ?string $recordTitleAttribute = 'title';

    public static function mutateFormDataBeforeFill(array $data): array
    {
        $data['image'] = static::normalizeImageForUpload($data['image'] ?? null);

        // Normalize section images
        if (isset($data['sections']) && is_array($data['sections'])) {
            $data['sections'] = array_map(function (array $section): array {
                $section['image_src'] = static::normalizeImageForUpload($section['image_src'] ?? null);
                return $section;
            }, $data['sections']);
        }

        return $data;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Article')
                ->components([
                    Select::make('journal_category_id')
                        ->label('Category')
                        ->options(fn () => JournalCategory::query()->orderByDesc('id')->pluck('label', 'id'))
                        ->searchable()
                        ->required(),
                    Toggle::make('is_published')->label('Published')->default(true),
                    TextInput::make('title')
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('dek')->required()->columnSpanFull(),
                    Textarea::make('excerpt')->required()->columnSpanFull(),
                    FileUpload::make('image')->label('Image')->image()->disk('public')->visibility('public')->required()->columnSpanFull(),
                    TextInput::make('alt')->required()->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Sections')
                ->description('Content sections that make up the body of this article.')
                ->components([
                    Repeater::make('sections')
                        ->relationship('sections')
                        ->schema([
                            TextInput::make('heading')->required()->columnSpanFull(),
                            TagsInput::make('body')
                                ->label('Paragraphs')
                                ->helperText('Press Enter after each paragraph.')
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('pull_quote')
                                ->label('Pull Quote')
                                ->columnSpanFull(),
                            TagsInput::make('bullets')
                                ->label('Bullet Points')
                                ->columnSpanFull(),
                            FileUpload::make('image_src')->label('Image')->image()->disk('public')->visibility('public')->columnSpanFull(),
                            TextInput::make('image_alt')->label('Image Alt')->columnSpanFull(),
                            TextInput::make('image_caption')->label('Image Caption')->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->orderColumn(false)
                        ->addActionLabel('Add Section')
                        ->collapsible()
                        ->cloneable()
                        ->columnSpanFull(),
                ]),

            Section::make('FAQs')
                ->description('Attach existing FAQs — same FAQ can be shared across multiple articles.')
                ->components([
                    Select::make('sharedFaqs')
                        ->label('Attached FAQs')
                        ->relationship('sharedFaqs', 'question')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('question')->required()->columnSpanFull(),
                            Textarea::make('answer')->required()->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Related Articles')
                ->components([
                    Select::make('relatedArticles')
                        ->relationship(
                            'relatedArticles',
                            'title',
                            modifyQueryUsing: function (Builder $query, ?JournalArticle $record) {
                                return $record ? $query->whereKeyNot($record->id) : $query;
                            },
                        )
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->exists(false)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->limit(45),
                TextColumn::make('category.label')->label('Category')->sortable(),
                IconColumn::make('is_published')->label('Published')->boolean(),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['image'] = static::normalizeImageForUpload($data['image'] ?? null);

                        if (isset($data['sections']) && is_array($data['sections'])) {
                            $data['sections'] = array_map(function (array $section): array {
                                $section['image_src'] = static::normalizeImageForUpload($section['image_src'] ?? null);
                                return $section;
                            }, $data['sections']);
                        }

                        return $data;
                    }),
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
