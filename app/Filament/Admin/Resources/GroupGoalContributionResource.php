<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GroupGoalContributionResource\Pages;
use App\Filament\Admin\Resources\GroupGoalContributionResource\RelationManagers;
use App\Models\GroupGoalContribution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupGoalContributionResource extends Resource
{
    protected static ?string $model = GroupGoalContribution::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Groups';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroupGoalContributions::route('/'),
            'create' => Pages\CreateGroupGoalContribution::route('/create'),
            'edit' => Pages\EditGroupGoalContribution::route('/{record}/edit'),
        ];
    }
}
