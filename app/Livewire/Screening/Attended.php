<?php

namespace App\Livewire\Screening;

use App\Models\Service;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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

    public function render()
    {
        return view('livewire.screening.attended');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query(Service::query())
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
                    ->date('d/m/Y')
            ])
            ->recordUrl(fn ($record) => route('screening.make', $record))
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Paciente'),
                                TextInput::make('mother')
                                    ->label('Nome da mãe'),
                                TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99'),
                                TextInput::make('birth_date')
                                    ->label('Data de Nascimento')
                                    ->type('date'),
                                TextInput::make('cns')
                                    ->label('Cartão Nascional de Saúde'),
                                Fieldset::make('Período')
                                    ->schema([
                                        DateTimePicker::make('start_date')
                                            ->label('Data inicial')
                                            ->seconds(false),
                                        DateTimePicker::make('end_date')
                                            ->label('Data final')
                                            ->seconds(false)
                                    ])
                                    ->columnSpan(1)
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
                                    $data['cns'],
                                    fn (Builder $query, $cns): Builder => $query->where('cns', 'LIKE', "$cns%"),
                                )
                                ->when(
                                    $data['birth_date'],
                                    fn (Builder $query, $birthDate): Builder => $query->whereDate('birth_date', $birthDate),
                                )->when(
                                    $data['start_date'] && $data['end_date'],
                                    fn (Builder $query): Builder => $query->whereBetween('created_at',[$data['start_date'], $data['end_date']])
                                );
                        });
                    })
                    ->columnSpanFull()
            ])
            ->filtersLayout(FiltersLayout::AboveContent);
    }
}