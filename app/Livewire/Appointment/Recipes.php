<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Recipe;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Recipes extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Appointment $appointment;

    public function render()
    {
        return view('livewire.appointment.recipes');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query(Recipe::whereHas('appointment', fn (Builder $query) => $query->where('service_id', $this->appointment->service_id)))
            ->columns([
                TextColumn::make('updated_at')
                    ->label('Data e hora')
                    ->date('d/m/Y H:i'),
                TextColumn::make('doctor.name')
                    ->label('Médico')
            ])
            ->headerActions([
                Action::make('recipes_create')
                    ->form($this->getFormSchema())
                    ->label('Adicionar receita')
                    ->action(function(array $data) {
                        $data['appointment_id'] = $this->appointment->id;
                        $data['doctor_id'] = auth()->id();
                        Recipe::create($data);

                        Notification::make('recipes_created_notification')
                            ->title('Receita adicionada com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Salvar')
            ])
            ->actions([
                Action::make('recipe_edit')
                    ->form($this->getFormSchema())
                    ->label('editar')
                    ->fillForm(function($record) {
                        return $record->toArray();
                    })
                    ->action(function($record, array $data) {
                        $record->update($data);

                        Notification::make('recipes_updated_notification')
                            ->title('Receita atualizada com sucesso!')
                            ->success()
                            ->send();

                    })->modalSubmitActionLabel('Salvar'),
                Action::make('recipe_print')
                    ->label('Imprimir')
                    ->url(fn ($record) => route('appointment.prints.recipe', $record))
            ]);
    }

    private function getFormSchema(): array
    {
        return [
            Textarea::make('description')
                ->label('Descrição')
                ->rows(15)
                ->required()
        ];
    }
}
