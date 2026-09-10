<?php

namespace App\Filament\Resources\MainSliders\Pages;

use App\Filament\Resources\MainSliders\MainSliderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMainSliders extends ManageRecords
{
    protected static string $resource = MainSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
             ->mutateFormDataUsing(function (array $data) {
                $data['created_by'] = auth()->id();
                return $data;
            }),
        ];
    }
}
