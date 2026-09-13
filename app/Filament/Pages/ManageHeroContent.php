<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageHeroContent extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'Hero Section';

    protected static ?string $title = 'Hero Section';

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::payloadFor('home-content');

        $this->form->fill([
            'eyebrow' => $payload['hero']['eyebrow'] ?? '',
            'title' => $payload['hero']['title'] ?? '',
            'accent' => $payload['hero']['accent'] ?? '',
            'description' => $payload['hero']['description'] ?? '',
            'slides' => $payload['hero']['slides'] ?? [],
            'primaryLabel' => $payload['hero']['actions']['primaryLabel'] ?? '',
            'primaryHref' => $payload['hero']['actions']['primaryHref'] ?? '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero Text')
                    ->schema([
                        TextInput::make('eyebrow')->label('Eyebrow')->required(),
                        TextInput::make('title')->label('Title')->required(),
                        TextInput::make('accent')->label('Accent (coloured part)')->required(),
                        Textarea::make('description')->label('Description')->rows(3)->columnSpanFull(),
                    ])->columns(2),

                Section::make('Slides')
                    ->schema([
                        Repeater::make('slides')
                            ->label('')
                            ->schema([
                                FileUpload::make('image')->label('Image')->image()->disk('public')->directory('hero')->visibility('public')->required()->columnSpanFull(),
                                TextInput::make('alt')->label('Alt Text')->required(),
                                TextInput::make('link')->label('Link (e.g. /journal/slug)'),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Slide')
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Section::make('Call-to-Action Buttons')
                    ->schema([
                        TextInput::make('primaryLabel')->label('Primary Button Label'),
                        TextInput::make('primaryHref')->label('Primary Button Link'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $block = ContentBlock::query()->where('key', 'home-content')->firstOrFail();

        $payload = $block->payload;

        $payload['hero'] = [
            'eyebrow' => $data['eyebrow'],
            'title' => $data['title'],
            'accent' => $data['accent'],
            'description' => $data['description'],
            'slides' => $data['slides'],
            'actions' => [
                'primaryLabel' => $data['primaryLabel'],
                'primaryHref' => $data['primaryHref'],
            ],
        ];

        $block->update(['payload' => $payload]);

        Notification::make()->title('Hero section saved.')->success()->send();
    }
}
