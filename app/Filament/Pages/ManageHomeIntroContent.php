<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageHomeIntroContent extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'Intro, Payments & Ticker';

    protected static ?string $title = 'Intro, Payments & Ticker';

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?int $navigationSort = 5;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::payloadFor('home-content');

        $this->form->fill([
            // Ticker
            'ticker' => $payload['ticker'] ?? [],

            // Intro
            'intro_eyebrow'    => $payload['intro']['eyebrow'] ?? '',
            'intro_title'      => $payload['intro']['title'] ?? '',
            'intro_body'       => $payload['intro']['body'] ?? '',
            'intro_paragraphs' => $payload['intro']['paragraphs'] ?? [],
            'intro_stats'      => $payload['intro']['stats'] ?? [],

            // Payments (Why Choose Us)
            'payments' => $payload['payments'] ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticker / Destinations Marquee')
                    ->description('Items that scroll across the banner ticker.')
                    ->schema([
                        TagsInput::make('ticker')
                            ->label('Ticker Items')
                            ->placeholder('Add item then press Enter')
                            ->columnSpanFull(),
                    ]),

                Section::make('Intro Section')
                    ->schema([
                        TextInput::make('intro_eyebrow')->label('Eyebrow'),
                        TextInput::make('intro_title')->label('Title')->columnSpanFull(),
                        Textarea::make('intro_body')->label('Body (short summary)')->rows(3)->columnSpanFull(),
                        TagsInput::make('intro_paragraphs')
                            ->label('Paragraphs (press Enter after each)')
                            ->placeholder('Add paragraph then press Enter')
                            ->columnSpanFull(),
                        Repeater::make('intro_stats')
                            ->label('Stats')
                            ->schema([
                                TextInput::make('value')->label('Value')->required(),
                                TextInput::make('label')->label('Label')->required(),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Why Choose Us (Payments Section)')
                    ->schema([
                        Repeater::make('payments')
                            ->label('')
                            ->schema([
                                TextInput::make('glyph')->label('Glyph (e.g. i.)'),
                                TextInput::make('title')->label('Title')->required(),
                                Textarea::make('body')->label('Body')->rows(2)->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
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

        $payload['ticker'] = $data['ticker'];

        $payload['intro'] = [
            'eyebrow'    => $data['intro_eyebrow'],
            'title'      => $data['intro_title'],
            'body'       => $data['intro_body'],
            'paragraphs' => $data['intro_paragraphs'],
            'stats'      => array_values($data['intro_stats']),
        ];

        $payload['payments'] = array_values($data['payments']);

        $block->update(['payload' => $payload]);

        Notification::make()->title('Intro, Payments & Ticker saved.')->success()->send();
    }
}




