<?php

namespace App\Filament\Resources\MediaCenters\Pages;

use App\Filament\Resources\MediaCenters\MediaCenterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMediaCenters extends ManageRecords
{
    protected static string $resource = MediaCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->id();
                    return $data;
                }),
        ];
    }
}
