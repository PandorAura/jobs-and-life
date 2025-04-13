<?php

namespace App\Filament\Admin\Resources\ChallengeResource\Pages;

use App\Filament\Admin\Resources\ChallengeResource;
use Illuminate\Database\Eloquent\Builder;


use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChallenges extends ListRecords
{
    protected static string $resource = ChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $currentMonth = now()->month;

        return parent::getTableQuery()
            ->where('month', '<', $currentMonth);
    }
}
