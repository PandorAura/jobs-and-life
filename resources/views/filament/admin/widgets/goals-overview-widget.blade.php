<div class="space-y-4">
    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Goals Overview</h2>

    @forelse ($goals as $goal)
        @php
            $progress = 0;
            if ($goal->value != 0) {
                $progress = ($goal->raised_so_far / $goal->value) * 100;
            }
            $progress = min(max($progress, 0), 100);
            
            $progressColor = match(true) {
                $progress >= 75 => 'bg-emerald-500',
                $progress >= 50 => 'bg-blue-500',
                $progress >= 25 => 'bg-amber-500',
                default => 'bg-red-500',
            };
        @endphp

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="flex justify-between items-center mb-2">
                <span class="font-medium text-gray-800 dark:text-gray-200">
                    {{ $goal->name }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    €{{ number_format($goal->raised_so_far, 2) }} / €{{ number_format($goal->value, 2) }}
                    ({{ round($progress) }}%)
                </span>
            </div>

            <div class="w-full bg-gray-200 dark:bg-gray-700 h-3 rounded-full">
                <div
                    class="h-3 rounded-full transition-all duration-500 {{ $progressColor }}"
                    style="width: {{ $progress }}%;"
                    title="{{ round($progress) }}% complete"
                ></div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">No goals yet.</p>
    @endforelse
</div>