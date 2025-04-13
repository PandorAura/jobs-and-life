<?php

namespace App\Filament\Admin\Resources\GroupGoalResource\Pages;

use App\Filament\Admin\Resources\GroupGoalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroupGoal extends EditRecord
{
    protected static string $resource = GroupGoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
