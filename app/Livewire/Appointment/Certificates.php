<?php

namespace App\Livewire\Appointment;

use App\Enums\CertificateActivity;
use App\Enums\CertificateType;
use App\Enums\DurationType;
use App\Models\Appointment;
use App\Models\Certificate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class Certificates extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Appointment $appointment;

    public function render()
    {
        return view('livewire.appointment.certificates');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query(Certificate::whereHas('appointment', fn (Builder $query) => $query->where('service_id', $this->appointment->service_id)))
            ->columns([
                TextColumn::make('updated_at')
                    ->label('Data e hora')
                    ->date('d/m/Y H:i'),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn ($state) => CertificateType::getDescription($state)),
                TextColumn::make('doctor.name')
                    ->label('Médico')
            ])
            ->headerActions([
                Action::make('certificates_create')
                    ->form($this->getFormSchema())
                    ->label('Adicionar atestado')
                    ->action(function(array $data) {
                        $data['appointment_id'] = $this->appointment->id;
                        $data['doctor_id'] = auth()->id();
                        Certificate::create($data);

                        Notification::make('certificates_created_notification')
                            ->title('Atestado adicionado com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Salvar')
                    ->modalWidth('lg')
            ])
            ->actions([
                Action::make('certificate_edit')
                    ->form($this->getFormSchema())
                    ->label('editar')
                    ->fillForm(function($record) {
                        return $record->toArray();
                    })
                    ->action(function($record, array $data) {
                        $record->update($data);

                        Notification::make('certificates_updated_notification')
                            ->title('Atestado atualizado com sucesso!')
                            ->success()
                            ->send();

                    })->modalSubmitActionLabel('Salvar')
                    ->modalWidth('sm'),
                Action::make('certificate_print')
                    ->label('Imprimir')
                    ->url(fn ($record) => route('appointment.prints.certificate', $record))
            ]);
    }

    private function getFormSchema(): array
    {
        return [
            Select::make('type')
                ->label('Tipo')
                ->required()
                ->reactive()
                ->options(CertificateType::asSelectArray()),
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('activity')
                        ->label('Tipo de Atividade')
                        ->required()
                        ->options(CertificateActivity::asSelectArray())
                        ->columnSpanFull(),
                    TextInput::make('duration')
                        ->label('Duração')
                        ->numeric()
                        ->required(),
                    Select::make('duration_type')
                        ->label('Tipo de duração')
                        ->options(DurationType::asSelectArray())
                        ->required(),
                    DatePicker::make('start_at')
                        ->label('Apartir de:')
                        ->columnSpanFull()
                        ->required(),
                    Toggle::make('show_cids')
                        ->label('Exibir CIDs no atestado')
                        ->columnSpanFull()
                ])
                ->hidden(fn ($get) => $get('type') != CertificateType::NORMAL),
            Section::make()
                ->columns(2)
                ->schema([
                    DateTimePicker::make('start_at')
                        ->label('Entrada:')
                        ->columnSpanFull()
                        ->required(),
                    DateTimePicker::make('end_at')
                        ->label('Saída:')
                        ->columnSpanFull()
                        ->required(),
                ])
                ->hidden(fn ($get) => $get('type') != CertificateType::ATTENDANCE)
        ];
    }
}
