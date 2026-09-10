<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('from_date')
                ->label('From Date')
                ->native(false),
            DatePicker::make('to_date')
                ->label('To Date')
                ->native(false),
        ]);
    }
}

