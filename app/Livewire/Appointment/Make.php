<?php

namespace App\Livewire\Appointment;

use App\Enums\Forwarding;
use App\Enums\ServiceStatus;
use App\Models\Appointment;
use App\Models\Diagnosis;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Make extends Component implements HasForms, HasActions, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithActions;
    use InteractsWithInfolists;

    public Appointment $appointment;
    public array $data;
    public int $tab = 1;

    public function mount()
    {
        $this->data = [
            'diagnoses' => null
        ];

        $this->form->fill([
            'symptoms' => $this->appointment->symptoms,
            'results' => $this->appointment->results,
            'conduct' => $this->appointment->conduct,
            'forwarding' => $this->appointment->forwarding,
            'diagnoses' => $this->appointment->diagnoses->pluck('id')
        ]);
    }

    public function render()
    {
        return view('livewire.appointment.make');
    }

    public function patientInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->appointment->service->patient)
            ->columns(4)
            ->schema([
                TextEntry::make('name')
                    ->label('Nome'),
                TextEntry::make('mother')
                    ->label('Mãe'),
                TextEntry::make('birth_date')
                    ->label('Data de Nascimento')
                    ->date('d/m/Y'),
                TextEntry::make('cns')
                    ->label('CNS')
            ]);
    }

    public function showScreeningAction(): Action
    {
        return Action::make('showScreening')
            ->label('Classificação de Risco')
            ->modalContent(view('actions.screening'));
    }

    public function showRecipesAction(): Action
    {
        return Action::make('showRecipes')
            ->label('Receitas')
            ->modalContent(view('actions.recipes'))
            ->slideOver();
            
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([
                Fieldset::make('Laudo técnico e justificativa de atendimento')
                    ->schema([
                        Textarea::make('symptoms')
                            ->label('Principais sinais de sintomas clínicos')
                            ->required(),
                        Textarea::make('results')
                            ->label('Principais resultados de provas diagnósticas')
                            ->required(),
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
                            }),
                        Textarea::make('conduct')
                            ->label('Conduta Terapeutica')
                            ->required(),
                        Select::make('forwarding')
                            ->label('Encaminhamento')
                            ->options(Forwarding::asSelectArray())
                            ->required()
                    ])
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            $this->appointment->update($data);
            $this->appointment->diagnoses()->sync($data['diagnoses']);

            $this->appointment->service->update([
                'status' => ServiceStatus::APPOINTMENT
            ]);

            Notification::make('appoitment_update_notification')
                ->title('Atendimento salvo com sucesso!')
                ->success()
                ->send();
       });
    }

    public function setTab(int $value)
    {
        $this->tab = $value;
    }
}
