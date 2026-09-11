<?php

namespace App\Filament\Resources\JournalArticles\Pages;

use App\Filament\Resources\JournalArticles\JournalArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalArticles extends ManageRecords
{
    protected static string $resource = JournalArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
