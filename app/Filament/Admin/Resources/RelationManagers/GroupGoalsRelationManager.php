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
use Filament\Tables\Columns\ProgressColumn;
use Filament\Tables\Actions\Action;
use App\Models\GroupGoalContribution;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;


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
            TextColumn::make('current_amount')
                ->label('Saved')
                ->money('EUR')
                ->color(fn ($record) => $record->current_amount >= $record->target_amount ? 'success' : 'primary'),
            TextColumn::make('target_amount')->label('Target')->money('EUR'),
            /*ProgressColumn::make('progress')
                ->progress(fn ($record) => $record->target_amount > 0 
                    ? ($record->current_amount / $record->target_amount) * 100 
                    : 0
                )
                ->color(fn ($record) => $record->current_amount >= $record->target_amount ? 'success' : 'primary'),*/
        ])
            
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Edit/Delete existing actions
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                // New Contribution Action (Inline Form)
                Action::make('contribute')
                    ->label('Add Contribution')
                    ->form([
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->maxValue(function ($record) {
                                $remaining = $record->target_amount - $record->current_amount;
                                return max(0.01, $remaining);
                            })
                            ->label('Contribution Amount (EUR)'),
                    ])
                    ->action(function ($record, array $data) {
                        // Wrap in transaction
                        \DB::transaction(function () use ($record, $data) {
                            // Create contribution
                            GroupGoalContribution::create([
                                'group_goal_id' => $record->id,
                                'user_id' => auth()->id(),
                                'amount' => $data['amount'],
                            ]);
                            
                            // Refresh and increment
                            $newTotal = GroupGoalContribution::where('group_goal_id', $record->id)->sum('amount');
        $record->update(['current_amount' => $newTotal]);
                        });
                        
                        Notification::make()
                            ->title('Contribution Added')
                            ->success()
                            ->send();
                    }),
                    
                // View Contributions Action
                Action::make('viewContributions')
                    ->label('View All')
                    ->url(fn ($record) => \App\Filament\Admin\Resources\GroupGoalContributionResource::getUrl('index', [
                        'tableFilters' => [
                            'group_goal_id' => [
                                'value' => $record->id
                            ]
                        ]
                    ]))
                    ->icon('heroicon-o-eye')
                    ->color('gray'),
                
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
