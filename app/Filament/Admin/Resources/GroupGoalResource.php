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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;

use App\Filament\Admin\Resources\GroupGoalResource\Pages\ContributeToGoal;

class GroupGoalResource extends Resource
{
    protected static ?string $model = GroupGoal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Groups';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static string $relationship = 'contributions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('amount')
                ->required()
                ->numeric()
                ->label('How much do you want to contribute?'),
        ]);
    }
    public static function creating(CreateAction $action)
    {
        $action->record(function ($data) {
            $data['user_id'] = auth()->id();
            return GroupGoalContribution::create($data);
        });
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
            TextColumn::make('title'),
            TextColumn::make('current_amount')->money('EUR'),
            TextColumn::make('target_amount')->money('EUR'),
        ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('contribute')
                ->label('Contribute')
                ->url(fn (GroupGoal $record) => ContributeToGoal::getUrl(['record' => $record]))                
                ->color('success'),
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
            ContributionRelationManagerResource\RelationManagers\ContributionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroupGoals::route('/'),
            'create' => Pages\CreateGroupGoal::route('/create'),
            'edit' => Pages\EditGroupGoal::route('/{record}/edit'),
            'contribute' => Pages\ContributeToGoal::route('/{record}/contribute')
        ];
    }
    public function contributions()
{
    return $this->hasMany(GroupGoalContribution::class);
}
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->when(
            request()->has('group_id'),
            fn (Builder $query) => $query->where('group_id', request('group_id'))
        );
}
}
