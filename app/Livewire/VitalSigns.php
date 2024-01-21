<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\VitalSigns as ModelsVitalSigns;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class VitalSigns extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Service $service;
    public bool $onlyView = false;

    public function render()
    {
        return view('livewire.vital-signs');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query($this->service->vitalSigns()->getQuery())
            ->columns([
                TextColumn::make('blood_glucose')
                    ->label('HGT')
                    ->numeric('2', ',', '.'),
                TextColumn::make('heart_rate')
                    ->label('FC')
                    ->numeric(),
                TextColumn::make('saturation')
                    ->label('SPO2')
                    ->numeric(),
                TextColumn::make('temperature')
                    ->label('TAX')
                    ->numeric('2', ',', '.'),
                TextColumn::make('blood_pressure')
                    ->label('PA'),
                TextColumn::make('weight')
                    ->label('Peso')
                    ->numeric('2', ',', '.'),
                TextColumn::make('glasgow')
                    ->label('Glasgow'),
                TextColumn::make('updated_at')
                    ->label('Criação')
                    ->dateTime('d/m/y H:i')
            ])
            ->recordAction('vital_signs_edit')
            ->actions([
                Action::make('vital_signs_edit')
                    ->form(fn ($record) => $this->getFormSchema($record->nurse_id != auth()->id()))
                    ->label('editar')
                    ->fillForm(function($record) {
                        return $record->toArray();
                    })
                    ->action(function($record, array $data) {
                        $record->update($data);

                        Notification::make('vitals_signs_updated_notification')
                            ->title('Sinais vitais atualizados com sucesso!')
                            ->success()
                            ->send();

                    })->modalSubmitActionLabel('Salvar')
                    ->hidden($this->onlyView)
                    ->modalSubmitAction(fn ($record) => $record->nurse_id != auth()->id() ? false : null)
            ])
            ->headerActions([
                Action::make('vital_signs_create')
                    ->form($this->getFormSchema())
                    ->label('Adicionar sinais vitais')
                    ->action(function(array $data) {
                        $data['service_id'] = $this->service->id;
                        $data['nurse_id'] = auth()->id();
                        ModelsVitalSigns::create($data);

                        Notification::make('vitals_signs_created_notification')
                            ->title('Sinais vitais adicionados com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Salvar')
                    ->hidden($this->onlyView)
            ])
            ->emptyState(new HtmlString(''))
            ->paginated(false);
    }


    public function getFormSchema($disabled = false) : array
    {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('blood_glucose')
                        ->label('Glicemia')
                        ->numeric()
                        ->disabled($disabled),
                    TextInput::make('heart_rate')
                        ->label('Frequência Cardíaca')
                        ->numeric()
                        ->disabled($disabled),
                    TextInput::make('saturation')
                        ->label('Saturação de Oxigênio')
                        ->numeric()
                        ->disabled($disabled),
                    TextInput::make('temperature')
                        ->label('Temperatura Axilar')
                        ->numeric()
                        ->disabled($disabled),
                    TextInput::make('blood_pressure')
                        ->label('Pressão Arterial')
                        ->disabled($disabled),
                    TextInput::make('weight')
                        ->label('Peso')
                        ->numeric()
                        ->disabled($disabled),
                    Select::make('glasgow')
                        ->label('Escala de Glasgow')
                        ->options([
                            3 => 3,
                            4 => 4, 
                            5 => 5,
                            6 => 6,
                            7 => 7,
                            8 => 8,
                            9 => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                            13 => 13,
                            14 => 14,
                            15 => 15
                        ])
                        ->disabled($disabled)
                ])
        ];
    }
}
