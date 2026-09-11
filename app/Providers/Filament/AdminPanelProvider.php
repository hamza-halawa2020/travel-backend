<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Resources\JournalArticleFaqs\JournalArticleFaqResource;
use App\Filament\Resources\JournalArticles\JournalArticleResource;
use App\Filament\Resources\JournalArticleSections\JournalArticleSectionResource;
use App\Filament\Resources\JournalCategories\JournalCategoryResource;
use App\Filament\Resources\JournalSettings\JournalSettingResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->brandName('Total Stay Tours')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                ContactResource::class,
                JournalCategoryResource::class,
                JournalArticleResource::class,
                JournalArticleSectionResource::class,
                JournalArticleFaqResource::class,
                JournalSettingResource::class,
            ])
            ->pages([
                Dashboard::class,
                ManageSettings::class,
            ])
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // ->spa()
            ->sidebarCollapsibleOnDesktop();
    }
}
