<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageDestinationsContent extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'Destinations';

    protected static ?string $title = 'Destinations';

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::payloadFor('home-content');

        $this->form->fill([
            'destinations' => $payload['destinations'] ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Destinations')
                    ->schema([
                        Repeater::make('destinations')
                            ->label('')
                            ->schema([
                                TextInput::make('name')->label('City / Name')->required(),
                                TextInput::make('region')->label('Region / Country')->required(),
                                FileUpload::make('image')->label('Image')->image()->disk('public')->directory('destinations')->visibility('public')->columnSpanFull(),
                                TextInput::make('link')->label('Link (e.g. /journal/slug)')->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Destination')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
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
        $payload['destinations'] = collect($data['destinations'])->map(fn (array $destination): array => [
            'name' => $destination['name'] ?? '',
            'region' => $destination['region'] ?? '',
            'image' => $destination['image'] ?? '',
            'link' => $destination['link'] ?? '',
        ])->values()->all();

        $block->update(['payload' => $payload]);

        Notification::make()->title('Destinations saved.')->success()->send();
    }
}
