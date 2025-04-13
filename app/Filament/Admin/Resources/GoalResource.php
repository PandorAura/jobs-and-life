<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GoalResource\Pages;
use App\Filament\Admin\Resources\GoalResource\RelationManagers;
use App\Models\Goal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextInputColumn;

class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Financial Goals';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Goal Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                            
                        Forms\Components\TextInput::make('value')
                            ->numeric()
                            ->prefix('€')
                            ->required(),
                            
                        Forms\Components\TextInput::make('raised_so_far')
                            ->label('Amount Raised')
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            // ->rules([
                            //     function (Forms\Get $get) {
                            //         return function (string $attribute, $value, Closure $fail) use ($get) {
                            //             if ($value > $get('value')) {
                            //                 $fail("Raised amount cannot exceed goal value");
                            //             }
                            //         };
                            //     }
                            // ])
                            ->required(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Additional Details')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'short_term' => 'Short Term',
                                'long_term' => 'Long Term',
                            ])
                            ->required(),
                            
                        Forms\Components\DatePicker::make('target_date')
                            ->required(),
                            
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium', 
                                'high' => 'High',
                            ])
                            ->default('medium'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('value')
                    ->money('EUR')
                    ->sortable(),
                    
                    Tables\Columns\TextColumn::make('raised_so_far')
                    ->label('Raised Amount (€)'),
                    
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->state(function (Goal $record): string {
                        return $record->value > 0 
                            ? number_format(($record->raised_so_far / $record->value) * 100, 2).'%'
                            : '0%';
                    })
                    ->color(fn (Goal $record) => match (true) {
                        $record->raised_so_far >= $record->value => 'success',
                        ($record->raised_so_far / $record->value) >= 0.75 => 'warning',
                        default => 'danger',
                    }),
                    
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'long_term' => 'info',
                        'short_term' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('target_date')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('target_date', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'short_term' => 'Short Term',
                        'long_term' => 'Long Term',
                    ]),
                    
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ]),
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
            'index' => Pages\ListGoals::route('/'),
            'create' => Pages\CreateGoal::route('/create'),
            'edit' => Pages\EditGoal::route('/{record}/edit'),
        ];
    }
}
