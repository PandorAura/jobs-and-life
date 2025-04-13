<x-filament::widget>
    <x-filament::card>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-sm text-gray-500">Total Income</div>
                <div class="text-xl font-bold text-green-600">€{{ number_format($totalIncome, 2) }}</div>
            </div>

            <div class="text-center">
                <div class="text-sm text-gray-500">Total Spendings</div>
                <div class="text-xl font-bold text-red-600">€{{ number_format($totalSpending, 2) }}</div>
            </div>

            <div class="text-center">
                <div class="text-sm text-gray-500">Free Budget</div>
                <div class="text-xl font-bold text-blue-600">€{{ number_format($freeBudget, 2) }}</div>
            </div>

            <div class="text-center">
                <div class="text-sm text-gray-500">Remaining Budget</div>
                <div class="text-xl font-bold text-yellow-600">€{{ number_format($remainingBudget, 2) }}</div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
