<?php

namespace App\Filament\Resources\JournalArticleSections\Pages;

use App\Filament\Resources\JournalArticleSections\JournalArticleSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalArticleSections extends ManageRecords
{
    protected static string $resource = JournalArticleSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
