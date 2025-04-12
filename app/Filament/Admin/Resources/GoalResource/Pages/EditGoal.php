<?php

namespace App\Filament\Admin\Resources\GoalResource\Pages;

use App\Filament\Admin\Resources\GoalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGoal extends EditRecord
{
    protected static string $resource = GoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
