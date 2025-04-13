<?php

namespace App\Filament\Admin\Resources\ChallengeResource\Pages;

use App\Filament\Admin\Resources\ChallengeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChallenge extends EditRecord
{
    protected static string $resource = ChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
