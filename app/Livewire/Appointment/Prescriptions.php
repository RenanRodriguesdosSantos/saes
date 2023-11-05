<?php

namespace App\Livewire\Appointment;

use App\Enums\MedicinePresentation;
use App\Enums\PrescriptionStatus;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\MedicinePrescription;
use App\Models\Prescription;
use App\Models\Recipe;
use Filament\Forms\Components\Repeater;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Prescriptions extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Appointment $appointment;

    public function render()
    {
        return view('livewire.appointment.prescriptions');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query(Prescription::whereHas('appointment', fn (Builder $query) => $query->where('service_id', $this->appointment->service_id)))
            ->columns([
                TextColumn::make('updated_at')
                    ->label('Data e hora')
                    ->date('d/m/Y H:i'),
                TextColumn::make('doctor.name')
                    ->label('Médico')
            ])
            ->headerActions([
                Action::make('prescritptions_create')
                    ->form($this->getFormSchema())
                    ->label('Adicionar prescrição')
                    ->action(function(array $data) {
                        DB::transaction(function () use ($data) {
                            /** @var Prescription */
                            $prescription = Prescription::create([
                                'appointment_id' => $this->appointment->id,
                                'doctor_id' => auth()->id()
                            ]);
                            
                            foreach ($data['items'] as $value) {
                                $prescription->medicines()
                                    ->create([
                                        'medicine_id' => $value['medicine_id'],
                                        'amount' => $value['amount'],
                                        'medicine_apresentation' => $value['medicine_apresentation'],
                                        'doctor_note' => $value['note'],
                                        'status' => PrescriptionStatus::PENDING
                                    ]);
                            }

                            Notification::make('prescritptions_created_notification')
                                ->title('Prescrição adicionada com sucesso!')
                                ->success()
                                ->send();
                        });

                    })
                    ->modalWidth('6xl')
                    ->modalSubmitActionLabel('Salvar')
            ])
            ->actions([
                Action::make('prescritption_edit')
                    ->form($this->getFormSchema())
                    ->label('editar')
                    ->fillForm(function(Prescription $record) {
                        $data['items'] = [];

                        $record->medicines()
                            ->get()
                            ->each(function ($item) use (&$data) { 
                                $data['items'][] = $item->toArray();
                            });

                        return $data;
                    })
                    ->action(function(Prescription $record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $keep = [];
                            foreach ($data['items'] as $value) {
                                if (key_exists('id', $value)) {
                                    $keep[] = $value['id'];
                                }
                            }

                            $record->medicines()
                                ->whereNotIn('id', $keep)
                                ->delete();

                            foreach ($data['items'] as $value) {
                                if (key_exists('id', $value)) {
                                    $item = MedicinePrescription::find($value['id']);

                                    $item->update([
                                        'amount' => $value['amount'],
                                        'medicine_apresentation' => $value['medicine_apresentation'],
                                        'doctor_note' => $value['note']
                                    ]);
                                } else {
                                    $record->medicines()
                                        ->create([
                                            'medicine_id' => $value['medicine_id'],
                                            'amount' => $value['amount'],
                                            'medicine_apresentation' => $value['medicine_apresentation'],
                                            'doctor_note' => $value['note']
                                        ]);
                                }
                            }

                            Notification::make('prescritptions_updated_notification')
                                ->title('Prescrição atualizada com sucesso!')
                                ->success()
                                ->send();
                        });
                    })
                    ->modalWidth('6xl')
                    ->modalSubmitActionLabel('Salvar'),
            ]);
    }

    private function getFormSchema(): array
    {
        return [
            Repeater::make('items')
                ->label('Itens')
                ->reorderable(false)
                ->columns(7)
                ->schema([
                    TextInput::make('amount')
                        ->label('Quantidade')
                        ->required()
                        ->numeric(),
                    Select::make('medicine_id')
                        ->label('Prescrição')
                        ->columnSpan(3)
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return Medicine::where('name', 'LIKE', "%$search%")
                                ->limit(20)->pluck('name', 'id')->toArray();
                        })
                        ->getOptionLabelUsing(function ($value) {
                            return Medicine::find($value)?->name;
                        }),
                    Select::make('medicine_apresentation')
                        ->label('Apresentação')
                        ->options(MedicinePresentation::asSelectArray()),
                    TextInput::make('note')
                        ->label('Observação')
                        ->columnSpan(2)
                ])
        ];
    }
}
