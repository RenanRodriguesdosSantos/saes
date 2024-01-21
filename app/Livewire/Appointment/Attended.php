<?php

namespace App\Livewire\Appointment;

use App\Enums\Classification;
use App\Models\Appointment;
use App\Models\Service;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Attended extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $status;

    public function mount()
    {
        if ($this->status != 'entrada' && $this->status != 'atendidos') {
            return abort(404);
        }
    }

    public function render()
    {
        return view('livewire.appointment.attended');
    }

    public function table(Table $table) : Table
    {
        if ($this->status == 'entrada') {
            $query = Service::doesntHave('appointments');
        } else {
            $query = Service::has('appointments');
        }

        $query->whereHas('screening');
        $query->latest();

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('patient.cns')
                    ->label('CNS'),
                TextColumn::make('patient.name')
                    ->label('Nome'),
                TextColumn::make('patient.mother')
                    ->label('Mãe'),
                TextColumn::make('patient.cpf')
                    ->label('CPF'),
                TextColumn::make('patient.birth_date')
                    ->label('D. Nascimento')
                    ->date('d/m/Y'),
                TextColumn::make('screening.classification.value')
                    ->label('Classificação')
                    ->formatStateUsing(fn ($state) => Classification::getDescription($state))
                    ->badge()
                    ->color(fn ($state) => 'classification_' . $state)
            ])
            ->actions([
                Action::make('appointment_options')
                    ->label('Opções')
                    ->color('dark')
                    ->form(function (Service $record) {
                        $actions = [];

                       $record->appointments()
                            ->get()
                            ->each(function (Appointment $appointment) use (&$actions) {
                                $actions[] = Actions::make([
                                    FormAction::make("appointment_edit_{$appointment->id}")
                                        ->label('Acessar consulta: ' . $appointment->created_at->format('d/m/Y H:i'))
                                        ->extraAttributes(['style' => 'width: 100%;'])
                                        ->action(function () use ($appointment) {
                                            return redirect()->route('appointment.make', $appointment);
                                        })
                                ]);
                                
                            });

                        $actions[] = Actions::make([
                            FormAction::make('appointment_make')
                                ->label('Iniciar nova consulta')
                                ->color('success')
                                ->extraAttributes(['style' => 'width: 100%;'])
                                ->action(function () use ($record) {
                                    $appointment = $record->appointments()->create([
                                        'doctor_id' => auth()->id(),
                                    ]);
            
                                    return redirect()->route('appointment.make', $appointment);
                                })
                        ]);

                        return $actions;
                    })
                    ->modalSubmitAction(false)
                    ->modalWidth('lg')
            ])
            ->recordAction('appointment_options')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Grid::make(6)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Paciente')
                                    ->columnSpan(2),
                                TextInput::make('mother')
                                    ->label('Nome da mãe')
                                    ->columnSpan(2),
                                TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99')
                                    ->columnSpan(2),
                                TextInput::make('birth_date')
                                    ->label('Data de Nascimento')
                                    ->type('date')
                                    ->columnSpan(2),
                                Select::make('classification')
                                    ->label('Classificação')
                                    ->options(Classification::asSelectArray())
                                    ->columnSpan(2),
                                Fieldset::make('Período')
                                    ->extraAttributes(['class' => 'py-1'])
                                    ->schema([
                                        DateTimePicker::make('start_date')
                                            ->label('Data inicial')
                                            ->seconds(false),
                                        DateTimePicker::make('end_date')
                                            ->label('Data final')
                                            ->seconds(false)
                                    ])
                                    ->columnSpan(4)
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereHas('patient', function(Builder $query) use ($data) {
                            return $query
                                ->when(
                                    $data['name'],
                                    fn (Builder $query, $name): Builder => $query->where('name', 'LIKE', "$name%"),
                                )
                                ->when(
                                    $data['mother'],
                                    fn (Builder $query, $mother): Builder => $query->where('mother', 'LIKE', "$mother%"),
                                )
                                ->when(
                                    $data['cpf'],
                                    fn (Builder $query, $cpf): Builder => $query->where('cpf', 'LIKE', "$cpf%"),
                                )
                                ->when(
                                    $data['birth_date'],
                                    fn (Builder $query, $birthDate): Builder => $query->whereDate('birth_date', $birthDate),
                                )->when(
                                    $data['start_date'] && $data['end_date'],
                                    fn (Builder $query): Builder => $query->whereBetween('created_at',[$data['start_date'], $data['end_date']])
                                );
                        })->when(
                            $data['classification'],
                            function (Builder $query, $classification): Builder {
                                return $query->whereHas('screening', function ($query) use ($classification) {
                                    return $query->whereHas('classification', fn ($query) => $query->where('value', $classification));
                                });
                            }
                        );
                    })
                    ->columnSpanFull()
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->paginationPageOptions(['20', '50', '100']);
    }
}
