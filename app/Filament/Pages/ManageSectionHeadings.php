<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSectionHeadings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'Section Headings';

    protected static ?string $title = 'Section Headings';

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::payloadFor('home-content');
        $s = $payload['sections'] ?? [];

        $this->form->fill([

            // Payments / Why us
            'payments_eyebrow' => $s['payments']['eyebrow'] ?? '',
            'payments_title'   => $s['payments']['title'] ?? '',

            // Destinations
            'destinations_eyebrow'       => $s['destinations']['eyebrow'] ?? '',
            'destinations_title'         => $s['destinations']['title'] ?? '',
            'destinations_accent'        => $s['destinations']['accent'] ?? '',
            'destinations_nextLabel'     => $s['destinations']['nextLabel'] ?? '',
            'destinations_previousLabel' => $s['destinations']['previousLabel'] ?? '',

            // Journal preview
            'journal_eyebrow'  => $s['journal']['eyebrow'] ?? '',
            'journal_title'    => $s['journal']['title'] ?? '',
            'journal_accent'   => $s['journal']['accent'] ?? '',
            'journal_body'     => $s['journal']['body'] ?? '',
            'journal_ctaLink'  => $s['journal']['ctaLink'] ?? '',
            'journal_ctaLabel' => $s['journal']['ctaLabel'] ?? '',

            // FAQ section
            'faq_eyebrow' => $s['faq']['eyebrow'] ?? '',
            'faq_title'   => $s['faq']['title'] ?? '',

            // Contact section
            'contact_eyebrow' => $s['contact']['eyebrow'] ?? '',
            'contact_title'   => $s['contact']['title'] ?? '',
            'contact_accent'  => $s['contact']['accent'] ?? '',
            'contact_body'    => $s['contact']['body'] ?? '',


            // Floating CTA
            'floating_callLabel'      => $s['floating']['callLabel'] ?? '',
            'floating_whatsappLabel'  => $s['floating']['whatsappLabel'] ?? '',

        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Why Choose Us (Payments) Heading')
                    ->schema([
                        TextInput::make('payments_eyebrow')->label('Eyebrow'),
                        TextInput::make('payments_title')->label('Title'),
                    ])->columns(2)->collapsible(),

                Section::make('Destinations Section')
                    ->schema([
                        TextInput::make('destinations_eyebrow')->label('Eyebrow'),
                        TextInput::make('destinations_title')->label('Title'),
                        TextInput::make('destinations_accent')->label('Accent'),
                        TextInput::make('destinations_nextLabel')->label('Next Label'),
                        TextInput::make('destinations_previousLabel')->label('Previous Label'),
                    ])->columns(2)->collapsible(),

                Section::make('Journal Preview (Home Page)')
                    ->schema([
                        TextInput::make('journal_eyebrow')->label('Eyebrow'),
                        TextInput::make('journal_title')->label('Title'),
                        TextInput::make('journal_accent')->label('Accent'),
                        Textarea::make('journal_body')->label('Body')->rows(2)->columnSpanFull(),
                        TextInput::make('journal_ctaLink')->label('CTA Link'),
                        TextInput::make('journal_ctaLabel')->label('CTA Label'),
                    ])->columns(2)->collapsible(),

                Section::make('FAQ Section Heading')
                    ->schema([
                        TextInput::make('faq_eyebrow')->label('Eyebrow'),
                        TextInput::make('faq_title')->label('Title'),
                    ])->columns(2)->collapsible(),

                Section::make('Contact Section')
                    ->schema([
                        TextInput::make('contact_eyebrow')->label('Eyebrow'),
                        TextInput::make('contact_title')->label('Title'),
                        TextInput::make('contact_accent')->label('Accent'),
                        Textarea::make('contact_body')->label('Body')->rows(3)->columnSpanFull(),
                    ])->columns(2)->collapsible(),


                Section::make('Floating CTA Buttons')
                    ->schema([
                        TextInput::make('floating_callLabel')->label('Call Button Label'),
                        TextInput::make('floating_whatsappLabel')->label('WhatsApp Button Label'),
                    ])->columns(2)->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $block = ContentBlock::query()->where('key', 'home-content')->firstOrFail();

        $payload = $block->payload;
        unset($payload['sections']['services']);


        $payload['sections']['payments'] = [
            'eyebrow' => $data['payments_eyebrow'],
            'title'   => $data['payments_title'],
        ];

        $payload['sections']['destinations'] = [
            'eyebrow'       => $data['destinations_eyebrow'],
            'title'         => $data['destinations_title'],
            'accent'        => $data['destinations_accent'],
            'nextLabel'     => $data['destinations_nextLabel'],
            'previousLabel' => $data['destinations_previousLabel'],
        ];

        $existingJournalSection = $payload['sections']['journal'] ?? [];
        $payload['sections']['journal'] = array_merge($existingJournalSection, [
            'eyebrow'  => $data['journal_eyebrow'],
            'title'    => $data['journal_title'],
            'accent'   => $data['journal_accent'],
            'body'     => $data['journal_body'],
            'ctaLink'  => $data['journal_ctaLink'],
            'ctaLabel' => $data['journal_ctaLabel'],
        ]);

        $payload['sections']['faq'] = [
            'eyebrow' => $data['faq_eyebrow'],
            'title'   => $data['faq_title'],
        ];

        $payload['sections']['contact'] = [
            'eyebrow' => $data['contact_eyebrow'],
            'title'   => $data['contact_title'],
            'accent'  => $data['contact_accent'],
            'body'    => $data['contact_body'],
        ];


        $payload['sections']['floating'] = [
            'callLabel'     => $data['floating_callLabel'],
            'whatsappLabel' => $data['floating_whatsappLabel'],
        ];

        $block->update(['payload' => $payload]);

        Notification::make()->title('Section headings saved.')->success()->send();
    }
}

