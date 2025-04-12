<?php

namespace App\Filament\Admin\Resources\MandatorySpendingResource\Pages;

use App\Filament\Admin\Resources\MandatorySpendingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMandatorySpendings extends ListRecords
{
    protected static string $resource = MandatorySpendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
