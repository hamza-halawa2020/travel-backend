<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageFaqsContent extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'FAQs';

    protected static ?string $title = 'FAQs';

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::payloadFor('home-content');

        $this->form->fill([
            'faqs' => $payload['faqs'] ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Frequently Asked Questions')
                    ->schema([
                        Repeater::make('faqs')
                            ->label('')
                            ->schema([
                                TextInput::make('question')->label('Question')->required()->columnSpanFull(),
                                Textarea::make('answer')->label('Answer')->rows(3)->required()->columnSpanFull(),
                            ])
                            ->addActionLabel('Add FAQ')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
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
        $payload['faqs'] = array_values($data['faqs']);

        $block->update(['payload' => $payload]);

        Notification::make()->title('FAQs saved.')->success()->send();
    }
}
