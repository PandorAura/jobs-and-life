<?php

// app/Services/BudgetAllocator.php

namespace App\Services;

use App\Models\User;

class BudgetAllocator
{
    public static function calculateAllocations(User $user): array
    {
        $totalIncome = $user->incomes()->sum('amount');
        $totalSpending = $user->mandatorySpendings()->sum('amount');
        $remaining = $totalIncome - $totalSpending;

        $allocations = $user->allocations;
        $results = [];

        foreach ($allocations as $allocation) {
            $amount = $remaining * ($allocation->percentage / 100);
            $results[] = [
                'name' => $allocation->name,
                'goal' => optional($allocation->goal)->name,
                'percentage' => $allocation->percentage,
                'amount' => round($amount, 2),
            ];
        }

        return [
            'remaining' => $remaining,
            'allocations' => $results,
        ];
    }
}
