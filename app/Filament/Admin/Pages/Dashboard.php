<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use App\Filament\Admin\Widgets\AllocationPieChart;
use App\Filament\Admin\Widgets\MonthlyChallenge;
class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
            MonthlyChallenge::class,
            AllocationPieChart::class,
        ];
    }

}
