<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GroupResource\Pages;
use App\Filament\Admin\Resources\GroupResource\RelationManagers;
use App\Filament\Admin\Resources\GroupResource\RelationManagers\GroupGoalsRelationManager;

use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Forms\Components\Hidden;




class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Groups';
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->label('Group Name')
                ->required()
                ->maxLength(255),

            Select::make('users')
                ->label('Assign Users to Group')
                ->relationship('users', 'name')  // 👈 this enables automatic pivot table saving
                ->multiple()
                ->searchable(),

            Hidden::make('created_by')
                ->default(auth()->id()),
        ]);
    }

   // Define the table for displaying the groups
   public static function table(Tables\Table $table): Tables\Table
   {
       return $table
           ->columns([
               TextColumn::make('name')
                   ->label('Group Name')
                   ->sortable()
                   ->searchable(),

               TextColumn::make('creator.name')
                   ->label('Created By')
                   ->sortable()
                   ->searchable(),

                   TextColumn::make('users')
                    ->label('Users in Group')
                    ->sortable()
                    ->getStateUsing(fn (Group $record) => $record->users->pluck('name')->join(', ')),
           ])
           ->actions([
               // Default edit and delete actions
               Tables\Actions\EditAction::make(),
               Tables\Actions\DeleteAction::make(),
           ])
           ->filters([
               // Add filters here (e.g., filter by group creator)
           ]);
   }

    // Optionally, you can define additional relations and logic
    public static function getRelations(): array
    {
        return [
            GroupGoalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
    protected function getHeaderActions(): array
{
    return [
        Action::make('viewGoals')
            ->label('View Goals')
            ->url(fn () => GroupGoalResource::getUrl('index', ['group_id' => $this->record->id]))
            ->color('primary'),
    ];
}
}
