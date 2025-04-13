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

                    Select::make('users')  // `users` is the relationship method from the Group model
                    ->label('Assign Users to Group')
                    ->multiple()  // Allow selecting multiple users
                    ->options(User::all()->pluck('name', 'id')) // List all users (you can customize this as needed)
                    ->searchable() // Allow searching for users
                    ->required(), // Optionally, make this required

                    Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()), // Automatically assigns the user creating the group
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

                   TextColumn::make('users.name')
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
}
