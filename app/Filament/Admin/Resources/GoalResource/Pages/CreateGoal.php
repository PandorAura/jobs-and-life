<?php

namespace App\Filament\Admin\Resources\GoalResource\Pages;

use App\Filament\Admin\Resources\GoalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGoal extends CreateRecord
{
    protected static string $resource = GoalResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
