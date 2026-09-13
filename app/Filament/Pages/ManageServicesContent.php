<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use App\Support\NormalizesStorageImages;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageServicesContent extends Page
{
    use NormalizesStorageImages;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $title = 'Services';

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::rawPayloadFor('home-content');

        $this->form->fill([
            'services' => $this->normalizeImagesInCollection($payload['services'] ?? []),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Services')
                    ->description('Manage the 5 service cards (Hotels, Private Aviation, Yacht Charters, Chauffeur, VIP).')
                    ->schema([
                        Repeater::make('services')
                            ->label('')
                            ->schema([
                                Section::make('Basic Info')
                                    ->schema([
                                        TextInput::make('id')->label('ID (no spaces, e.g. hotels)')->required(),
                                        TextInput::make('eyebrow')->label('Eyebrow (e.g. No. 01)'),
                                        TextInput::make('title')->label('Title')->required(),
                                        TextInput::make('tag')->label('Tag / Desk Name'),
                                        FileUpload::make('image')->label('Image')->image()->disk('public')->visibility('public')->columnSpanFull(),
                                        TextInput::make('link')->label('Link (e.g. /journal/slug)')->columnSpanFull(),
                                        Textarea::make('summary')->label('Summary')->rows(2)->columnSpanFull(),
                                    ])->columns(2),

                                Section::make('Details & Highlights')
                                    ->schema([
                                        TextInput::make('listTitle')->label('List Title'),
                                        TagsInput::make('details')
                                            ->label('Details (paragraphs)')
                                            ->placeholder('Add paragraph then press Enter')
                                            ->columnSpanFull(),
                                        TagsInput::make('highlights')
                                            ->label('Highlights / Service List')
                                            ->placeholder('Add item then press Enter')
                                            ->columnSpanFull(),
                                        TagsInput::make('closing')
                                            ->label('Closing Lines')
                                            ->placeholder('Add closing line then press Enter')
                                            ->columnSpanFull(),
                                    ])->columns(2),

                            ])
                            ->addable(false)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $block = ContentBlock::query()->where('key', 'home-content')->firstOrFail();

        $payload = $block->payload;
        $payload['services'] = array_values($data['services']);

        $block->update(['payload' => $payload]);

        Notification::make()->title('Services saved.')->success()->send();
    }
}

