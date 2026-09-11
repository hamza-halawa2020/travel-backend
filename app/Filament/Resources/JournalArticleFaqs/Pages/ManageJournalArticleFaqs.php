<?php

namespace App\Filament\Resources\JournalArticleFaqs\Pages;

use App\Filament\Resources\JournalArticleFaqs\JournalArticleFaqResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalArticleFaqs extends ManageRecords
{
    protected static string $resource = JournalArticleFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
