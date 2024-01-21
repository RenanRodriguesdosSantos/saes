<?php

namespace App\Livewire\Ambulatory;

use App\Models\Material;
use App\Models\Prescription;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class PrescriptionMaterials extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Prescription $prescription;
    public array $data;

    public function mount()
    {
        $this->form->fill([]);
    }

    public function render()
    {
        return view('livewire.ambulatory.prescription-materials');
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([
                Repeater::make('materials')
                    ->label('Materiais')
                    ->reorderable(false)
                    ->columns(6)
                    ->relationship()
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
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        $data['technician_id'] = auth()->id();
                 
                        return $data;
                    })
            ])
            ->statePath('data')
            ->model($this->prescription);
    }

    public function submit()
    {
        $this->form->getState();
        $this->form->model($this->prescription)->saveRelationships();

         Notification::make('prescription_materials_store_notification')
             ->title('Materiais salvos com sucesso!')
             ->success()
             ->send();
    }
}
