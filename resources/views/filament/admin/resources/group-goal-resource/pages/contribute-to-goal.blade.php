<x-filament-panels::page>
<x-filament::card>
        <h2 class="text-xl font-bold mb-4">Make a Contribution</h2>
        {{ $this->form }}
        
        <x-filament::button wire:click="submit" type="button" class="mt-4">
            Submit Contribution
        </x-filament::button>
    </x-filament::card>

    <x-filament::card class="mt-8">
        <h2 class="text-xl font-bold mb-4">Previous Contributions</h2>
        {{ $this->table }}
    </x-filament::card>
</x-filament-panels::page>
