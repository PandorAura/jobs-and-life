<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Filament\Admin\Widgets\AllocationPieChart;
use App\Services\ChatGptService;
use Illuminate\Support\Str;

use App\Filament\Admin\Widgets\MonthlyChallenge;
class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.admin.pages.dashboard';

    // Existing header widget(s)
    public function getHeaderWidgets(): array
    {
        return [
            MonthlyChallenge::class,
            AllocationPieChart::class,
        ];
    }

    // Public properties for Livewire binding
    public $scenario;
    public $advice;

    /**
     * Optionally, initialize properties when the component mounts.
     */
    public function mount()
    {
        $this->scenario = ''; 
        $this->advice = '';
    }

    /**
     * Returns a string with the user's current budget details.
     * Replace this with your real logic to get the user's data.
     */
    public function getUserBudgetDetails(): string
    {
        // Get the logged-in user. Adjust this if you use a different method (e.g., auth()->guard('filament')->user())
        $user = auth()->user();
        if (!$user) {
            return "<p>User not authenticated.</p>";
        }

        // Calculate key values from the user's data
        $totalIncome    = $user->incomes()->sum('amount');
        $totalSpending  = $user->mandatorySpendings()->sum('amount');
        $remainingBudget = \App\Services\BudgetAllocator::getWholeFreeBudget($user);
        $allocationsData = \App\Services\BudgetAllocator::calculateAllocations($user);

        // Build the HTML output
        $html = "<h2>Your Budget Details</h2>";
        $html .= "<p><strong>Total Income:</strong> $" . number_format($totalIncome, 2) . "</p>";
        $html .= "<p><strong>Total Mandatory Spending:</strong> $" . number_format($totalSpending, 2) . "</p>";
        $html .= "<p><strong>Remaining Free Budget:</strong> $" . number_format($remainingBudget, 2) . "</p>";
        
        $html .= "<h3>Allocations Breakdown</h3>";
        if (!empty($allocationsData['allocations'])) {
            $html .= "<ul>";
            foreach ($allocationsData['allocations'] as $allocation) {
                $html .= "<li>";
                $html .= "<strong>" . $allocation['name'] . "</strong>";
                if (!empty($allocation['goal'])) {
                    $html .= " (Goal: " . $allocation['goal'] . ")";
                }
                $html .= " – " . $allocation['percentage'] . "% allocated, Amount: $" . number_format($allocation['amount'], 2);
                $html .= "</li>";
            }
            $html .= "</ul>";
        } else {
            $html .= "<p>No allocations found.</p>";
        }

        return $html;
    }


    /**
     * Called when the form is submitted.
     * This method calls the ChatGptService with the selected scenario and existing user details.
     */
    public function generateAdvice()
    {
        if (!$this->scenario) {
            session()->flash('error', 'Please select a scenario.');
            return;
        }

        // Retrieve your existing details as needed
        $userDetails = $this->getUserBudgetDetails();

        // Call the ChatGPT service
        $chatGptService = app(\App\Services\ChatGptService::class);
        $response = $chatGptService->generateTimeGoalAdvice($userDetails, $this->scenario);

        // Remove any triple backticks or ```html markers
        $cleanedResponse = str_ireplace(['```html', '```'], '', $response);

        // Assign the cleaned response to $this->advice so the Blade file can render it
        $this->advice = $cleanedResponse;
    }
}
