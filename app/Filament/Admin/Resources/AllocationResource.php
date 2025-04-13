<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AllocationResource\Pages;
use App\Models\Allocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Max;
use App\Services\BudgetAllocator;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Free budget allocation';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $remainingBudget = self::getUserRemainingBudget();
        $remainingPercentage = self::getUserRemainingPercentage();

        return $form
            ->schema([
                Forms\Components\Section::make('Allocation Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('percentage')
                            ->label('Percentage of remaining budget')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required()
                            ->rules([
                                function () {
                                    $max = self::getUserRemainingPercentage();
                                    return "max:{$max}";
                                }
                            ])
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, $set) use ($remainingBudget) {
                                $set('calculated_amount', 
                                    self::formatCurrency(($state / 100) * $remainingBudget)
                                );
                            }),

                        Forms\Components\TextInput::make('calculated_amount')
                            ->label('Estimated Amount')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(function (callable $get) use ($remainingBudget) {
                                $percentage = $get('percentage') ?? 0;
                                return self::formatCurrency(($percentage / 100) * $remainingBudget);
                            }),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Select::make('goal_id')
                            ->label('Linked Goal (optional)')
                            ->relationship('goal', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Allocation Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('percentage')
                    ->label('Budget %')
                    ->suffix('%')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('amount')
                ->label('Amount (€)')
                ->state(function (Allocation $record) {
                    $user = $record->user; // or auth()->user() if relationship exists
                    $remaining = BudgetAllocator::calculateAllocations($user)['remaining'] ?? 0;
                    return '€' . number_format(($record->percentage / 100) * $remaining, 2);
                })
                ->alignRight(),

                Tables\Columns\TextColumn::make('goal.name')
                    ->label('Linked Goal')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('goal')
                    ->relationship('goal', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListAllocations::route('/'),
            'create' => Pages\CreateAllocation::route('/create'),
            'edit' => Pages\EditAllocation::route('/{record}/edit'),
        ];
    }

    /* Helper Methods */
    private static function getUserRemainingBudget(): float
    {
        return once(function () {
            return BudgetAllocator::calculateAllocations(auth()->user())['remaining'] ?? 0;
        });
    }

    private static function getUserRemainingPercentage(): float
    {
        $user = auth()->user();
        $totalAllocated = $user->allocations()->sum('percentage');
        return max(0, 100 - $totalAllocated);
    }

    private static function formatCurrency(float $amount): string
    {
        return '€'.number_format($amount, 2);
    }
}