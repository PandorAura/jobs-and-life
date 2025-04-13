<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Services\BudgetAllocator;
use Filament\Widgets\Widget;

class BudgetOverview extends Widget
{
    protected static string $view = 'filament.admin.widgets.budget-overview';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = auth()->user();

        return [
            'totalIncome' => $user->incomes()->sum('amount'),
            'totalSpending' => $user->mandatorySpendings()->sum('amount'),
            'freeBudget' => BudgetAllocator::getWholeFreeBudget($user),
            'remainingBudget' => BudgetAllocator::getRemainingFreeBudget($user),
        ];
    }
}
