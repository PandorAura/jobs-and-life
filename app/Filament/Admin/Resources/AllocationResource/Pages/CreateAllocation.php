<?php

namespace App\Filament\Admin\Resources\AllocationResource\Pages;

use App\Filament\Admin\Resources\AllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAllocation extends CreateRecord
{
    protected static string $resource = AllocationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
