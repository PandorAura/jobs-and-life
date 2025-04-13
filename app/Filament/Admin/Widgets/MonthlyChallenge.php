<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Challenge;


class MonthlyChallenge extends BaseWidget
{
    protected function getStats(): array
    {
        $currentMonth = now()->month;
        $challenge = Challenge::where('month', $currentMonth)->first();

        if (!$challenge) {
            return [
                Stat::make('Current Challenge', 'No challenge set for this month')
                    ->description('Check back later or contact admin')
                    ->icon('heroicon-o-exclamation-circle')
            ];
        }

        return [
            Stat::make('This Month\'s Challenge', $challenge->title)
                ->description($challenge->description)
                ->icon('heroicon-o-trophy')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-50 transition',
                    'wire:click' => "dispatch('open-modal', { id: 'challenge-details' })"
                ]),
        ];
    }
}
