<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Challenge;

class PastChallenges extends BaseWidget
{
    protected function getStats(): array
    {
        $currentMonth = now()->month;
        $pastChallenges = Challenge::where('month', '<', $currentMonth)
            ->orderBy('month', 'desc')
            ->get();

        return $pastChallenges->map(function ($challenge) {
            return Stat::make(
                $challenge->title,
                $challenge->completed ? '✅ Completed' : '❌ Not Completed'
            )
                ->description($challenge->description)
                ->icon($challenge->completed ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                ->color($challenge->completed ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Optional progress visualization
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-50',
                    'wire:click' => "toggleCompletion({$challenge->id})"
                ]);
        })->toArray();
    }

    public function toggleCompletion($challengeId)
    {
        $challenge = Challenge::find($challengeId);
        $challenge->update([
            'completed' => !$challenge->completed,
            'completed_at' => $challenge->completed ? now() : null
        ]);
    }
}