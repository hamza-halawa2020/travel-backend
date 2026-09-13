<?php

namespace App\Filament\Resources\Enquiries\Pages;

use App\Filament\Resources\Enquiries\EnquiryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEnquiries extends ManageRecords
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
