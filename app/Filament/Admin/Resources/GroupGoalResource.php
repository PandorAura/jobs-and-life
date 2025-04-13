<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GroupGoalResource\Pages;
use App\Filament\Admin\Resources\GroupGoalResource\RelationManagers;
use App\Models\GroupGoal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupGoalResource extends Resource
{
    protected static ?string $model = GroupGoal::class;

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
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'target_amount' => 'required|numeric',
        'current_amount' => 'required|numeric',
        'group_id' => 'required|exists:groups,id',
    ]);

    $validated['created_by'] = auth()->id();  // Assign the authenticated user

    GroupGoal::create($validated);

    return redirect()->route('group-goals.index');
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
            'index' => Pages\ListGroupGoals::route('/'),
            'create' => Pages\CreateGroupGoal::route('/create'),
            'edit' => Pages\EditGroupGoal::route('/{record}/edit'),
        ];
    }
}
