<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Services\BudgetAllocator;

class AllocationPieChart extends ChartWidget
{
    protected static ?string $heading = 'Your Free Budget Allocations';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $user = auth()->user();
        $allocations = BudgetAllocator::calculateAllocations($user)['allocations'];
        
        return [
            'datasets' => [
                [
                    'label' => 'Budget Allocations',
                    'data' => collect($allocations)->pluck('amount'),
                    'backgroundColor' => $this->generateColors(count($allocations)),
                ],
            ],
            'labels' => collect($allocations)->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    private function generateColors($count): array
    {
        // Generate N random pastel colors
        return collect(range(1, $count))->map(function () {
            return sprintf('hsl(%d, 70%%, 70%%)', rand(0, 360));
        })->toArray();
    }
}
