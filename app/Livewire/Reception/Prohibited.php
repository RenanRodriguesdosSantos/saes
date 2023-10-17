<?php

namespace App\Livewire\Reception;

use App\Enums\Gender;
use App\Enums\ServiceStatus;
use App\Models\County;
use App\Models\Patient;
use App\Models\Prohibited as ModelsProhibited;
use App\Models\Service;
use App\Models\State;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Prohibited extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function render()
    {
        return view('livewire.reception.prohibited');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Patient::query())
            ->columns([
                TextColumn::make('cns')
                    ->label('CNS'),
                TextColumn::make('name')
                    ->label('Nome'),
                TextColumn::make('mother')
                    ->label('Mãe'),
                TextColumn::make('cpf')
                    ->label('CPF'),
                TextColumn::make('birth_date')
                    ->label('D. Nascimento')
                    ->date('d/m/Y'),
                TextColumn::make('naturalness.name')
                    ->label('Naturalidade')
            ])
            ->recordAction('open_service')
            ->actions([
                EditAction::make()
                    ->form($this->getFormSchema())
                    ->modalHeading('Editar paciente')
                    ->beforeFormFilled(function ($record) {
                        $record->state_id = $record->county->state_id;
                        $record->state_naturalness_id = $record->naturalness->state_id;
                        return $record;
                    })
                    ->action(function ($record, $data) {
                        $record->update($data);
                    }),
                Action::make('open_service')
                    ->label(false)
                    ->requiresConfirmation()
                    ->modalHeading('Abrir ficha')
                    ->modalDescription('Deseja realmente abrir ficha de atendimento para esse paciente?')
                    ->action(function (Patient $record) {
                        DB::transaction(function () use ($record) {
                            $prohibited = ModelsProhibited::create([
                                'receptionist_id' => auth()->id()
                            ]);
    
                            Service::create([
                                'patient_id' => $record->id,
                                'prohibited_id' => $prohibited->id,
                                'status' => ServiceStatus::PROHIBITED
                            ]);
                        });
                    })
            ])
            ->headerActions([
                
            ])
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
                                    ->mask('000.000.000-00'),
                                DatePicker::make('birth_date')
                                    ->label('Data de Nascimento'),
                                TextInput::make('cns')
                                    ->label('Cartão Nascional de Saúde'),
                            ])
                    ])
                    ->columnSpanFull()
                    ->query(function (Builder $query, array $data): Builder {
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
                            );
                    })
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->emptyStateHeading('Nenhum paciente encontrado')
            ->emptyStateDescription(null)
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Cadastrar paciente')
                    ->modalHeading('Novo paciente')
                    ->modalSubmitActionLabel('Salvar')
                    ->createAnother(false)
                    ->form($this->getFormSchema())
                    ->action(function (array $data) {
                        Patient::create($data);
                    })
            ]);
    }

    private function getFormSchema() {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->columnSpanFull()
                        ->required(),
                    TextInput::make('mother')
                        ->label('Mãe')
                        ->required(),
                    TextInput::make('father')
                        ->label('Pai'),
                    DatePicker::make('birth_date')
                        ->label('Data de Nascimento')
                        ->required(),
                    Select::make('gender')
                        ->options(Gender::asSelectArray())
                        ->label('Sexo')
                        ->required(),
                    TextInput::make('phone')
                        ->label('Telefone')
                        ->mask('(99) 99999-9999'),
                    TextInput::make('cpf')
                        ->label('CPF')
                        ->mask('999.999.999-99'),
                    TextInput::make('cns')
                        ->label('Cartão Nascional de Saúde')
                        ->mask('999 9999 9999 9999'),
                    TextInput::make('rg')
                        ->label('RG'),
                    TextInput::make('profession')
                        ->label('Profissão'),
                    Select::make('ethnicity_id')
                        ->relationship('ethnicity', 'name')
                        ->required()
                        ->label('Etnia'),
                    Select::make('state_naturalness_id')
                        ->label('UF de Nascimento')
                        ->required()
                        ->reactive()
                        ->options(State::all()->pluck('name', 'id')->toArray()),
                    Select::make('naturalness_id')
                        ->label('Naturalidade')
                        ->relationship('naturalness', 'name')
                        ->options(function (callable $get) {
                            if ($get('state_naturalness_id'))  {
                                return County::where('state_id', $get('state_naturalness_id'))->get()->pluck('name', 'id')->toArray();
                            }

                            return [];
                        }),
                    Fieldset::make('Endereço')
                        ->schema([
                            TextInput::make('place')
                                ->label('Logradouro')
                                ->required(),
                            TextInput::make('neighborhood')
                                ->label('Bairro')
                                ->required(),
                            TextInput::make('residence_number')
                                ->label('Número de residência'),
                            TextInput::make('complement')
                                ->label('Complemento'),
                            Select::make('state_id')
                                ->label('UF de Residência')
                                ->reactive()
                                ->options(State::all()->pluck('name', 'id')->toArray())
                                ->required(),
                            Select::make('county_id')
                                ->label('Município de Residência')
                                ->relationship('county', 'name')
                                ->required()
                                ->options(function (callable $get) {
                                    if ($get('state_id'))  {
                                        return County::where('state_id', $get('state_id'))->get()->pluck('name', 'id')->toArray();
                                    }
    
                                    return [];
                                }),
                        ]),
                ])
        ];
    }
}
