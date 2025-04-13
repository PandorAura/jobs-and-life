<?php

namespace App\Filament\Admin\Resources\MandatorySpendingResource\Pages;

use App\Filament\Admin\Resources\MandatorySpendingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMandatorySpending extends CreateRecord
{
    protected static string $resource = MandatorySpendingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
