<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSettings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected string $view = 'filament.pages.manage-settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Settings';

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::getAllSettings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel(),
                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->tel(),
                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->url(),
                        TextInput::make('youtube')
                            ->label('youtube')
                            ->url(),
                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->url(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email(),
                    ])->columns(2),

                Section::make('Notification Emails')
                    ->schema([
                        Textarea::make('admin_notification_emails')
                            ->label('Fallback Notification Emails')
                            ->rows(2)
                            ->placeholder('e.g. admin@example.com, admin2@example.com')
                            ->helperText('Used if contact-specific emails are empty.')
                            ->columnSpanFull(),
                        Textarea::make('contact_notification_emails')
                            ->label('Travel Enquiry Notification Emails')
                            ->rows(2)
                            ->placeholder('e.g. contact1@example.com, contact2@example.com')
                            ->helperText('Receives website contact and travel enquiry notifications.')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('About Us')
                    ->schema([
                        Textarea::make('about_us')
                            ->label('About Us')
                            ->rows(5),
                        Textarea::make('about_us_footer')
                            ->label('About Us (Footer)')
                            ->rows(3),
                        // TextInput::make('address')
                        //     ->label('Address'),
                    ]),

            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            foreach ($data as $key => $value) {
                Setting::setValue($key, $value);
            }

            Notification::make()
                ->title('Settings saved successfully')
                ->success()
                ->send();
        } catch (\Exception $exception) {
            Notification::make()
                ->title('Error saving settings')
                ->danger()
                ->send();
        }
    }
}
