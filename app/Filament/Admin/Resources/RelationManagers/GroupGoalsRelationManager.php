<?php

namespace App\Filament\Admin\Resources\GroupResource\RelationManagers;

use App\Models\GroupGoal;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ProgressBarColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\TextInput;

class GroupGoalsRelationManager extends RelationManager
{
    protected static string $relationship = 'goals'; // This must match the name of the hasMany() relation in Group model
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Groups';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Goal'),
                TextColumn::make('current_amount')->label('Saved')->money('eur', true),
                TextColumn::make('target_amount')->label('Target')->money('eur', true),
                /*ProgressBarColumn::make('progress')
                    ->label('Progress')
                    ->progress(function (GroupGoal $record) {
                        if ($record->target_amount > 0) {
                            return ($record->current_amount / $record->target_amount) * 100;
                        }
                        return 0;
                    })
                    ->color(fn (GroupGoal $record) =>
                        $record->current_amount >= $record->target_amount ? 'success' : 'primary'
                    ),*/
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Goal Title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('target_amount')
                    ->label('Target Amount')
                    ->numeric()
                    ->required(),

                TextInput::make('current_amount')
                    ->label('Currently Saved')
                    ->numeric()
                    ->default(0),

                    Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }
}
