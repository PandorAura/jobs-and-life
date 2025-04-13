<?php

namespace App\Filament\Admin\Resources\AllocationResource\Pages;

use App\Filament\Admin\Resources\AllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllocations extends ListRecords
{
    protected static string $resource = AllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
