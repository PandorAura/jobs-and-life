<?php

namespace App\Filament\Admin\Resources\AllocationResource\Pages;

use App\Filament\Admin\Resources\AllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Livewire\Component;

class CreateAllocation extends CreateRecord
{
    protected static string $resource = AllocationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function beforeCreate(): void
    {
        $user = auth()->user();
        //$totalAllocated = $user->allocations()->sum('percentage');
        $currentAllocation = $this->record;
        $newPercentage = $this->form->getState()['percentage'];

        if (($currentAllocation + $newPercentage) > 100) {
            Notification::make()
            ->danger()
            ->title("danger")
            ->body("Allocated amount exceeds your available budget.")
            ->persistent()
            ->send();
            $this->halt();
        }

        $remaining = \App\Services\BudgetAllocator::calculateAllocations($user)['remaining'] ?? 0;
        $euroAmount = ($newPercentage / 100) * $remaining;

        if ($euroAmount > $remaining) {
            Notification::make()
            ->danger()
            ->title("danger")
            ->body("Allocated amount exceeds your available budget.")
            ->persistent()
            ->send();
            $this->halt();
        }
    }
}
