<?php

namespace App\Filament\Admin\Resources\GroupGoalResource\Pages;

use App\Filament\Admin\Resources\GroupGoalResource;
use App\Models\GroupGoal;
use App\Models\GroupGoalContribution;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ContributeToGoal extends Page implements Forms\Contracts\HasForms, Tables\Contracts\HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    public ?GroupGoal $record = null;

    protected static string $resource = GroupGoalResource::class;

    protected static string $view = 'filament.pages.empty'; // can use any default placeholder view
    protected static ?string $slug = 'group-goals/{record}/contribute';

    public static function getRouteKey(): string
    {
        return '{record}/contribute';
    }
    public function mount($record): void
    {
        $this->record = GroupGoal::findOrFail($record);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('amount')
                ->label('Contribution Amount')
                ->numeric()
                ->required(),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        GroupGoalContribution::create([
            'group_goal_id' => $this->record->id,
            'user_id' => auth()->id(),
            'amount' => $data['amount'],
            'group_id' => $this->record->group_id, // Add this line
        ]);

        // Update the total on the goal itself
        $newTotal = GroupGoalContribution::where('group_goal_id', $this->record->id)->sum('amount');
    $this->record->update(['current_amount' => $newTotal]);

        Notification::make()
            ->title('Contribution Added')
            ->success()
            ->send();

        $this->form->fill(); // reset form
    }

    protected function getTableQuery(): Builder
    {
        return GroupGoalContribution::query()
            ->where('group_goal_id', $this->record->id);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
                ->label('Contributor'),
            Tables\Columns\TextColumn::make('amount')
                ->money('eur')
                ->label('Amount'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->dateTime(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function render(): \Illuminate\View\View
    {
        return view('filament.admin.resources.group-goal-resource.pages.contribute-to-goal', [
            'header' => "Contribute to Goal: {$this->record->title}",
            'form' => $this->form,
            'table' => $this->table,
            'submitAction' => 'submit',
        ]);
    }
}
