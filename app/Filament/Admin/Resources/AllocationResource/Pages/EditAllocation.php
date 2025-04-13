<?php

namespace App\Filament\Admin\Resources\AllocationResource\Pages;

use App\Filament\Admin\Resources\AllocationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAllocation extends EditRecord
{
    protected static string $resource = AllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $user = auth()->user();
        $currentAllocation = $this->record; // The allocation being edited
        $newPercentage = $this->form->getState()['percentage'];
        
        // Calculate total allocations excluding the current one being edited
        $totalAllocated = $user->allocations()
            ->where('id', '!=', $currentAllocation->id)
            ->sum('percentage');
            
        $remainingPercentage = 100 - $totalAllocated;

        // Check percentage-based allocation
        if ($newPercentage > $remainingPercentage) {
            Notification::make()
                ->danger()
                ->title("Budget Exceeded")
                ->body("You can only allocate {$remainingPercentage}% more of your budget.")
                ->persistent()
                ->send();
            $this->halt();
            return;
        }

        // Check monetary allocation if needed
        $remainingBudget = \App\Services\BudgetAllocator::calculateAllocations($user)['remaining'] ?? 0;
        
        if ($remainingBudget > 0) {
            $euroAmount = ($newPercentage / 100) * $remainingBudget;
            
            if ($euroAmount > $remainingBudget) {
                Notification::make()
                    ->danger()
                    ->title("Budget Exceeded")
                    ->body("This allocation would exceed your available budget by ".number_format($euroAmount - $remainingBudget, 2)." EUR.")
                    ->persistent()
                    ->send();
                $this->halt();
            }
        }
    }
}