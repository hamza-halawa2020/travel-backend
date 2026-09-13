<?php

namespace App\Filament\Pages;

use App\Models\ContentBlock;
use App\Models\Setting;
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

class ManageSiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected string $view = 'filament.pages.manage-content';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $payload = ContentBlock::payloadFor('site-settings');
        $settings = Setting::getAllSettings();

        $this->form->fill([
            // Notification Emails (from settings table)
            'admin_notification_emails'   => $settings['admin_notification_emails'] ?? '',
            'contact_notification_emails' => $settings['contact_notification_emails'] ?? '',

            // Mail SMTP (from settings table)
            'mail_host'         => $settings['mail_host'] ?? '',
            'mail_port'         => $settings['mail_port'] ?? '587',
            'mail_username'     => $settings['mail_username'] ?? '',
            'mail_password'     => $settings['mail_password'] ?? '',
            'mail_encryption'   => $settings['mail_encryption'] ?? 'tls',
            'mail_from_address' => $settings['mail_from_address'] ?? '',
            'mail_from_name'    => $settings['mail_from_name'] ?? '',

            // Brand
            'brand_name'        => $payload['brand']['name'] ?? '',
            'brand_displayName' => $payload['brand']['displayName'] ?? '',
            'brand_monogram'    => $payload['brand']['monogram'] ?? '',
            'brand_tagline'     => $payload['brand']['tagline'] ?? '',
            'brand_ariaLabel'   => $payload['brand']['ariaLabel'] ?? '',

            // Contact
            'contact_phone'          => $payload['contact']['phone'] ?? '',
            'contact_whatsappLabel'  => $payload['contact']['whatsappLabel'] ?? '',
            'contact_callLabel'      => $payload['contact']['callLabel'] ?? '',
            'contact_corporateEmail' => $payload['contact']['corporateEmail'] ?? '',

            // Navigation
            'nav_ctaLabel' => $payload['navigation']['ctaLabel'] ?? '',
            'nav_items'    => $payload['navigation']['items'] ?? [],

            // Footer
            'footer_links' => $payload['footer']['links'] ?? [],

            // Journal Page
            'journal_eyebrow' => $payload['journalPage']['eyebrow'] ?? '',
            'journal_title'   => $payload['journalPage']['title'] ?? '',
            'journal_body'    => $payload['journalPage']['body'] ?? '',

            // Article Page
            'article_backToAllLabel'         => $payload['articlePage']['backToAllLabel'] ?? '',
            'article_backToJournalLabel'     => $payload['articlePage']['backToJournalLabel'] ?? '',
            'article_journalEyebrowSuffix'   => $payload['articlePage']['journalEyebrowSuffix'] ?? '',
            'article_startPlanningEyebrow'   => $payload['articlePage']['startPlanningEyebrow'] ?? '',
            'article_faqTitle'               => $payload['articlePage']['faqTitle'] ?? '',
            'article_relatedEyebrow'         => $payload['articlePage']['relatedEyebrow'] ?? '',
            'article_relatedTitle'           => $payload['articlePage']['relatedTitle'] ?? '',
            'article_readArticleLabel'       => $payload['articlePage']['readArticleLabel'] ?? '',
            'article_notFoundEyebrow'        => $payload['articlePage']['notFoundEyebrow'] ?? '',
            'article_notFoundTitle'          => $payload['articlePage']['notFoundTitle'] ?? '',
            'article_notFoundBody'           => $payload['articlePage']['notFoundBody'] ?? '',
            'article_notFoundButton'         => $payload['articlePage']['notFoundButton'] ?? '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Notification Emails')
                    ->icon('heroicon-o-envelope')
                    ->schema([
                        Textarea::make('admin_notification_emails')
                            ->label('Fallback Notification Emails')
                            ->rows(2)
                            ->placeholder('admin@example.com, admin2@example.com')
                            ->helperText('Used if contact-specific emails are empty.')
                            ->columnSpanFull(),
                        Textarea::make('contact_notification_emails')
                            ->label('Travel Enquiry Notification Emails')
                            ->rows(2)
                            ->placeholder('contact1@example.com, contact2@example.com')
                            ->helperText('Receives website contact and travel enquiry notifications.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Mail / SMTP Configuration')
                    ->icon('heroicon-o-at-symbol')
                    ->description('Outgoing mail server settings. Leave blank to use the server defaults from .env.')
                    ->schema([
                        TextInput::make('mail_host')
                            ->label('SMTP Host')
                            ->placeholder('mail.yourdomain.com')
                            ->columnSpanFull(),
                        TextInput::make('mail_port')
                            ->label('SMTP Port')
                            ->placeholder('587'),
                        TextInput::make('mail_encryption')
                            ->label('Encryption (tls / ssl / null)')
                            ->placeholder('tls'),
                        TextInput::make('mail_username')
                            ->label('SMTP Username / Email')
                            ->placeholder('info@yourdomain.com'),
                        TextInput::make('mail_password')
                            ->label('SMTP Password')
                            ->password()
                            ->revealable(),
                        TextInput::make('mail_from_address')
                            ->label('From Address')
                            ->placeholder('info@yourdomain.com'),
                        TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->placeholder('Total Stay Tours'),
                    ])->columns(2)->collapsible(),

                Section::make('Brand')
                    ->schema([
                        TextInput::make('brand_name')->label('Internal Name (no spaces)'),
                        TextInput::make('brand_displayName')->label('Display Name'),
                        TextInput::make('brand_monogram')->label('Monogram (e.g. TST)'),
                        TextInput::make('brand_tagline')->label('Tagline'),
                        TextInput::make('brand_ariaLabel')->label('Aria Label')->columnSpanFull(),
                    ])->columns(2)->collapsible(),

                Section::make('Contact Details')
                    ->schema([
                        TextInput::make('contact_phone')
                            ->label('Phone Number (with country code, e.g. +44 7425306013)')
                            ->helperText('The tel: and WhatsApp links are generated automatically from this number.')
                            ->columnSpanFull(),
                        TextInput::make('contact_whatsappLabel')->label('WhatsApp Button Label'),
                        TextInput::make('contact_callLabel')->label('Call Button Label'),
                        TextInput::make('contact_corporateEmail')->label('Corporate Email')->columnSpanFull(),
                    ])->columns(2),

                Section::make('Navigation')
                    ->schema([
                        TextInput::make('nav_ctaLabel')->label('CTA Button Label (e.g. Request A Quote)'),
                        Repeater::make('nav_items')
                            ->label('Navigation Items')
                            ->schema([
                                TextInput::make('label')->label('Label')->required(),
                                TextInput::make('routerLink')->label('Router Link')->default('/'),
                                TextInput::make('fragment')->label('Fragment / Anchor (e.g. hotels)'),
                            ])
                            ->columns(3)
                            ->addable(false)
                            ->deletable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                    ])->collapsible(),

                Section::make('Footer')
                    ->schema([
                        Repeater::make('footer_links')
                            ->label('Footer Links')
                            ->schema([
                                TextInput::make('label')->label('Label')->required(),
                                TextInput::make('routerLink')->label('Router Link')->default('/'),
                                TextInput::make('fragment')->label('Fragment / Anchor'),
                            ])
                            ->columns(3)
                            ->addActionLabel('Add Footer Link')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                    ])->collapsible(),

                Section::make('Journal Page Labels')
                    ->schema([
                        TextInput::make('journal_eyebrow')->label('Eyebrow'),
                        TextInput::make('journal_title')->label('Title'),
                        Textarea::make('journal_body')->label('Body')->rows(2)->columnSpanFull(),
                    ])->columns(2)->collapsible(),

                Section::make('Article Page Labels')
                    ->description('Text labels used throughout the article reader page.')
                    ->schema([
                        TextInput::make('article_backToAllLabel')->label('Back to All Label'),
                        TextInput::make('article_backToJournalLabel')->label('Back to Journal Label'),
                        TextInput::make('article_journalEyebrowSuffix')->label('Journal Eyebrow Suffix'),
                        TextInput::make('article_startPlanningEyebrow')->label('Start Planning Eyebrow'),
                        TextInput::make('article_faqTitle')->label('FAQ Section Title'),
                        TextInput::make('article_relatedEyebrow')->label('Related Articles Eyebrow'),
                        TextInput::make('article_relatedTitle')->label('Related Articles Title'),
                        TextInput::make('article_readArticleLabel')->label('Read Article Label'),
                        TextInput::make('article_notFoundEyebrow')->label('Not Found Eyebrow'),
                        TextInput::make('article_notFoundTitle')->label('Not Found Title'),
                        Textarea::make('article_notFoundBody')->label('Not Found Body')->rows(2)->columnSpanFull(),
                        TextInput::make('article_notFoundButton')->label('Not Found Button Label'),
                    ])->columns(2)->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $block = ContentBlock::query()->where('key', 'site-settings')->firstOrFail();

        $payload = $block->payload;

        // Save notification emails to settings table
        Setting::setValue('admin_notification_emails', $data['admin_notification_emails'] ?? '');
        Setting::setValue('contact_notification_emails', $data['contact_notification_emails'] ?? '');

        // Save mail SMTP settings to settings table
        Setting::setValue('mail_host',         $data['mail_host'] ?? '');
        Setting::setValue('mail_port',         $data['mail_port'] ?? '587');
        Setting::setValue('mail_username',     $data['mail_username'] ?? '');
        Setting::setValue('mail_encryption',   $data['mail_encryption'] ?? 'tls');
        Setting::setValue('mail_from_address', $data['mail_from_address'] ?? '');
        Setting::setValue('mail_from_name',    $data['mail_from_name'] ?? '');

        // Only update password if a new one was entered
        if (! empty($data['mail_password'])) {
            Setting::setValue('mail_password', $data['mail_password']);
        }

        $payload['brand'] = [
            'name'        => $data['brand_name'],
            'displayName' => $data['brand_displayName'],
            'monogram'    => $data['brand_monogram'],
            'tagline'     => $data['brand_tagline'],
            'ariaLabel'   => $data['brand_ariaLabel'],
        ];

        $payload['contact'] = [
            'phone'          => $data['contact_phone'],
            'phoneHref'      => 'tel:' . preg_replace('/\s+/', '', $data['contact_phone']),
            'whatsappHref'   => 'https://wa.me/' . ltrim(preg_replace('/\s+/', '', $data['contact_phone']), '+'),
            'whatsappLabel'  => $data['contact_whatsappLabel'],
            'callLabel'      => $data['contact_callLabel'],
            'corporateEmail' => $data['contact_corporateEmail'],
        ];

        $payload['navigation'] = [
            'ctaLabel' => $data['nav_ctaLabel'],
            'items'    => array_values($data['nav_items']),
        ];

        $payload['footer'] = [
            'copyright' => $block->payload['footer']['copyright'] ?? '',
            'links'     => array_values($data['footer_links']),
        ];

        $payload['journalPage'] = [
            'eyebrow' => $data['journal_eyebrow'],
            'title'   => $data['journal_title'],
            'body'    => $data['journal_body'],
        ];

        $payload['articlePage'] = [
            'backToAllLabel'       => $data['article_backToAllLabel'],
            'backToJournalLabel'   => $data['article_backToJournalLabel'],
            'journalEyebrowSuffix' => $data['article_journalEyebrowSuffix'],
            'startPlanningEyebrow' => $data['article_startPlanningEyebrow'],
            'faqTitle'             => $data['article_faqTitle'],
            'relatedEyebrow'       => $data['article_relatedEyebrow'],
            'relatedTitle'         => $data['article_relatedTitle'],
            'readArticleLabel'     => $data['article_readArticleLabel'],
            'notFoundEyebrow'      => $data['article_notFoundEyebrow'],
            'notFoundTitle'        => $data['article_notFoundTitle'],
            'notFoundBody'         => $data['article_notFoundBody'],
            'notFoundButton'       => $data['article_notFoundButton'],
        ];

        $block->update(['payload' => $payload]);

        Notification::make()->title('Site settings saved.')->success()->send();
    }
}
