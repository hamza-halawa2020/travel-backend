<?php

namespace App\Filament\Resources\JournalCategories\Pages;

use App\Filament\Resources\JournalCategories\JournalCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalCategories extends ManageRecords
{
    protected static string $resource = JournalCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
