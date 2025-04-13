<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

class GoalsOverviewWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.goals-overview-widget';

    public function getViewData(): array
    {
        $goals = Goal::where('user_id', Auth::id())->get();

        return [
            'goals' => $goals,
        ];
    }
}
