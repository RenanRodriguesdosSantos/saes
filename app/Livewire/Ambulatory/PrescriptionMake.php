<?php

namespace App\Livewire\Ambulatory;

use App\Enums\MedicinePresentation;
use App\Enums\PrescriptionStatus;
use App\Models\MedicinePrescription;
use App\Models\Prescription;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class PrescriptionMake extends Component implements HasForms, HasActions, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithActions;
    use InteractsWithInfolists;

    public Prescription $prescription;
    public array $data;
    public int $tab = 1;

    public function mount()
    {
        $data = [];

        $this->prescription
            ->medicines()
            ->get()
            ->each(function (MedicinePrescription $item) use (&$data) {
                $data["amount_{$item->id}"] = $item->amount;
                $data["presentation_{$item->id}"] = MedicinePresentation::getDescription($item->medicine_apresentation);
                $data["doctor_note_{$item->id}"] = $item->doctor_note;
                $data["status_{$item->id}"] = $item->status;
                $data["technician_note_{$item->id}"] = $item->technician_note;
            });

        $this->form->fill($data);
    }

    public function render()
    {
        return view('livewire.ambulatory.prescription-make');
    }

    public function patientInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->prescription->appointment->service->patient)
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
            ->modalContent(view('actions.screening', ['appointment' => $this->prescription->appointment]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Fechar');
    }

    public function form(Form $form) : Form
    {
        return $form
            ->columns(2)
            ->schema(function () {
                $schema = [];

                $this->prescription
                    ->medicines()
                    ->get()
                    ->each(function (MedicinePrescription $item) use (&$schema) {
                        $schema[] = Fieldset::make($item->medicine->name)
                            ->columns(10)
                            ->schema([
                                TextInput::make("amount_{$item->id}")
                                    ->label('Quantidade')
                                    ->disabled(),
                                TextInput::make("presentation_{$item->id}")
                                    ->label('Apresentação')
                                    ->disabled(),
                                Textarea::make("doctor_note_{$item->id}")
                                    ->label('Observações do médico')
                                    ->rows(1)
                                    ->columnSpan(3)
                                    ->disabled(),
                                Select::make("status_{$item->id}")
                                    ->label('Status')
                                    ->columnSpan(2)
                                    ->required()
                                    ->options(PrescriptionStatus::asSelectArray()),
                                TextInput::make("technician_note_{$item->id}")
                                    ->label('Observações')
                                    ->columnSpan(3)
                            ]);
                    });

                return $schema;
            })
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            $this->prescription
                    ->medicines()
                    ->get()
                    ->each(function ($item) use ($data) {
                        if ($data["status_{$item->id}"] != $item->status || $data["technician_note_{$item->id}"] != $item->technician_note) {
                            $item->update([
                                'technician_id' => auth()->id(),
                                'technician_note' => $data["technician_note_{$item->id}"],
                                'status' => $data["status_{$item->id}"]
                            ]);
                        }
                    });


            Notification::make('prescription_store_notification')
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
