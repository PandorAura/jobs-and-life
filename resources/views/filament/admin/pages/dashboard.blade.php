<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-xl font-bold">
            AI Financial Advice
        </h2>
        
        {{-- Advice Form --}}
        <form wire:submit.prevent="generateAdvice" class="space-y-2">
            <label for="scenario" class="block font-medium">Select Advice Scenario</label>
            <select wire:model="scenario" id="scenario" class="form-input block w-full">
                <option value="">-- Choose an advice scenario --</option>
                @foreach(config('chat-gpt-prompts.time_goal') as $key => $value)
                    <option value="{{ $key }}">{{ ucfirst($key) }}</option>
                @endforeach
            </select>

            <x-filament::button type="submit" class="btn btn-primary mt-4">
                Get Advice
            </x-filament::button>
        </form>

        {{-- Display the advice if available --}}
        @if($advice)
            <div class="p-3 rounded-md bg-gray-100 mt-4">
                <strong>Advice:</strong>
                <div class="mt-1 prose">
                    {!! $advice !!}
                </div>
            </div>
        @endif

        {{-- Optionally display any error --}}
        @if(session('error'))
            <div class="p-3 rounded-md bg-red-100 mt-4">
                <strong>Error:</strong>
                <p class="mt-1">{{ session('error') }}</p>
            </div>
        @endif
    </div>
</x-filament::page>
