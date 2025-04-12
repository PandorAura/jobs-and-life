<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AllocationResource\Pages;
use App\Filament\Admin\Resources\AllocationResource\RelationManagers;
use App\Models\Allocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Free budget allocation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),

                Forms\Components\TextInput::make('percentage')
                    ->label('Percentage of remaining budget')
                    ->numeric()
                    ->suffix('%')
                    ->required(),

                Forms\Components\Select::make('goal_id')
                    ->label('Linked Goal (optional)')
                    ->relationship('goal', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Allocation Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('percentage')
                    ->label('Budget %')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('goal.name')
                    ->label('Linked Goal')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListAllocations::route('/'),
            'create' => Pages\CreateAllocation::route('/create'),
            'edit' => Pages\EditAllocation::route('/{record}/edit'),
        ];
    }
}
