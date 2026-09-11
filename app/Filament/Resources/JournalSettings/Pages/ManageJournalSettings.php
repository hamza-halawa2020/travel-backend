<?php

namespace App\Filament\Resources\JournalSettings\Pages;

use App\Filament\Resources\JournalSettings\JournalSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalSettings extends ManageRecords
{
    protected static string $resource = JournalSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
