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

    public static function getWholeFreeBudget(User $user): array
    {
        $totalIncome = $user->incomes()->sum('amount');
        $totalSpending = $user->mandatorySpendings()->sum('amount');
        $remaining = $totalIncome - $totalSpending;

        return $remaining;
    }

    public static function getRemainingFreeBudget(User $user)  // budget without current allocations
    {
        // 1. Calculate base available budget
        $totalIncome = $user->incomes()->sum('amount');
        $totalSpending = $user->mandatorySpendings()->sum('amount');
        $initialRemaining = $totalIncome - $totalSpending;

        // 2. Calculate total allocation percentage
        $totalAllocatedPercentage = $user->allocations()->sum('percentage');

        // 3. Calculate and return final remaining
        return $initialRemaining * (1 - ($totalAllocatedPercentage / 100));
    }
}
