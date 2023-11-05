<?php

namespace App\Livewire\Ambulatory;

use App\Enums\MedicinePresentation;
use App\Enums\PrescriptionStatus;
use App\Models\Material;
use App\Models\MedicinePrescription;
use App\Models\Prescription;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
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

class PrescriptionMaterials extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Prescription $prescription;
    public array $data;

    public function mount()
    {
        // $data = [];

        // $this->prescription
        //     ->items()
        //     ->get()
        //     ->each(function (MedicinePrescription $item) use (&$data) {
        //         $data["amount_{$item->id}"] = $item->amount;
        //         $data["presentation_{$item->id}"] = MedicinePresentation::getDescription($item->medicine_apresentation);
        //         $data["doctor_note_{$item->id}"] = $item->doctor_note;
        //         $data["status_{$item->id}"] = $item->status;
        //         $data["technician_note_{$item->id}"] = $item->technician_note;
        //     });

        // $this->form->fill($data);
    }

    public function render()
    {
        return view('livewire.ambulatory.prescription-materials');
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([
                Repeater::make('items')
                    ->label('Materiais')
                    ->reorderable(false)
                    ->columns(6)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Quantidade')
                            ->required()
                            ->numeric(),
                        Select::make('material_id')
                            ->label('Material')
                            ->columnSpan(3)
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return Material::where('name', 'LIKE', "%$search%")
                                    ->limit(20)->pluck('name', 'id')->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return Material::find($value)?->name;
                            }),
                        TextInput::make('note')
                            ->label('Observação')
                            ->columnSpan(2)
                    ])
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            $this->prescription
                    ->items()
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
}
