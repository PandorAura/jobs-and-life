<?php

namespace App\Filament\Admin\Resources\GroupGoalContributionResource\Pages;

use App\Filament\Admin\Resources\GroupGoalContributionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroupGoalContribution extends EditRecord
{
    protected static string $resource = GroupGoalContributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
