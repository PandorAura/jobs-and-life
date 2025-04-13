<?php

namespace App\Filament\Admin\Resources\GroupResource\Pages;

use App\Filament\Admin\Resources\GroupResource;
use App\Filament\Admin\Resources\GroupGoalResource;

use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        // Get the default actions (e.g., Delete, Save)
        $defaultActions = parent::getHeaderActions();
        
        // Add your custom "View Goals" button
        $customActions = [
            Action::make('viewGoals')
                ->label('View Goals')
                ->url(fn () => GroupGoalResource::getUrl('index', ['group_id' => $this->record->id]))
                ->color('primary')
                ->icon('heroicon-o-arrow-right'),
        ];
        
        // Merge both arrays
        return array_merge($defaultActions, $customActions);
    }
}
