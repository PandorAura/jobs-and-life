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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;



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
            Select::make('group_goal_id')
                ->label('Goal')
                ->relationship('groupGoal', 'title')
                ->required(),

            Select::make('user_id')
                ->label('User')
                ->relationship('user', 'name')
                ->required(),

            TextInput::make('amount')
                ->numeric()
                ->required()
                ->label('Contribution Amount'),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('user.name')->label('User'),
            TextColumn::make('groupGoal.title')->label('Goal'),
            TextColumn::make('amount')->money('eur'),
            TextColumn::make('created_at')->dateTime(),
        ])
        ->filters([
            SelectFilter::make('group_goal_id')
                ->label('Group Goal')
                ->relationship('groupGoal', 'title'), // make sure `groupGoal()` exists in the model
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
