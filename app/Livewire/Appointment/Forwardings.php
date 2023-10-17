<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Forwarding;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class Forwardings extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Appointment $appointment;

    public function render()
    {
        return view('livewire.appointment.forwardings');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query(Forwarding::whereHas('appointment', fn (Builder $query) => $query->where('service_id', $this->appointment->service_id)))
            ->columns([
                TextColumn::make('updated_at')
                    ->label('Data e hora')
                    ->date('d/m/Y H:i'),
                TextColumn::make('doctor.name')
                    ->label('Médico')
            ])
            ->headerActions([
                Action::make('forwardings_create')
                    ->form($this->getFormSchema())
                    ->label('Adicionar encaminhamento')
                    ->action(function(array $data) {
                        $data['appointment_id'] = $this->appointment->id;
                        $data['doctor_id'] = auth()->id();
                        $forwarding = Forwarding::create($data);
                        $forwarding->diagnoses()->sync($data['diagnoses']);

                        Notification::make('forwardings_created_notification')
                            ->title('Encaminhamento adicionado com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Salvar')
            ])
            ->actions([
                Action::make('forwarding_edit')
                    ->form($this->getFormSchema())
                    ->label('editar')
                    ->fillForm(function($record) {
                        $data = $record->toArray();
                        $data['diagnoses'] = $record->diagnoses->pluck('id');
                        return $data;
                    })
                    ->action(function($record, array $data) {
                        $record->update($data);
                        $record->diagnoses()->sync($data['diagnoses']);

                        Notification::make('forwardings_updated_notification')
                            ->title('Encaminhamento atualizado com sucesso!')
                            ->success()
                            ->send();

                    })->modalSubmitActionLabel('Salvar')
            ]);
    }

    private function getFormSchema(): array
    {
        return [
            TextInput::make('specialty')
                ->label('Especialidade')
                ->required(),
            TextInput::make('entity')
                ->label('Estabelecimento')
                ->required(),
            Textarea::make('clinical_history')
                ->label('História Clínica')
                ->required()
                ->default(fn () => $this->appointment->symptoms),
            Textarea::make('exams')
                ->label('Exames realizados')
                ->required()
                ->default(fn () => $this->appointment->results),
            Select::make('diagnoses')
                ->label('Hipóteses Diagnósticas')
                ->multiple()
                ->required()
                ->searchable()
                ->getSearchResultsUsing(function (string $search) {
                    return Diagnosis::where('code', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->limit(20)->get()->mapWithKeys(fn (Diagnosis $diagnosis) => [$diagnosis->id => "{$diagnosis->code} - {$diagnosis->description}"]);
                })
                ->getOptionLabelsUsing(function ($values) {
                    return Diagnosis::whereIn('id', $values)
                        ->get()
                        ->mapWithKeys(fn (Diagnosis $diagnosis) => [$diagnosis->id => "{$diagnosis->code} - {$diagnosis->description}"]);
                })
                ->default(fn () => $this->appointment->diagnoses->pluck('id')->toArray())
        ];
    }
}
