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
                    ->label('Glasgow')
                    ->numeric(),
            ])
            ->actions([
                Action::make('vital_signs_edit')
                    ->form($this->getFormSchema())
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
            ])
            ->emptyState(new HtmlString(''));
    }


    public function getFormSchema() : array
    {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('blood_glucose')
                        ->label('Glicemia')
                        ->numeric(),
                    TextInput::make('heart_rate')
                        ->label('Frequência Cardíaca')
                        ->numeric(),
                    TextInput::make('saturation')
                        ->label('Saturação de Oxigênio')
                        ->numeric(),
                    TextInput::make('temperature')
                        ->label('Temperatura Axilar')
                        ->numeric(),
                    TextInput::make('blood_pressure')
                        ->label('Pressão Arterial')
                        ->numeric(),
                    TextInput::make('weight')
                        ->label('Peso')
                        ->numeric(),
                    Select::make('glasgow')
                        ->label('Escala de Glasgow')
                        ->options([3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])
                ])
        ];
    }
}
