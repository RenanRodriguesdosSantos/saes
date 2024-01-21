<?php

namespace App\Livewire\Screening;

use App\Enums\ServiceStatus;
use App\Models\Classification as ModelsClassification;
use App\Models\Flowchart;
use App\Models\Screening;
use App\Models\Service;
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

class Make extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public Service $service;
    public array $data;
    public bool $onlyView = false;

    public function render()
    {
        return view('livewire.screening.make');
    }

    public function mount()
    {
        $screening = $this->service->screening;

        if ($screening) {
            $this->form->fill([
                'description' => $screening->description,
                'flowchart' => $screening->classification->flowchart->id,
                'classification_id' => $screening->classification->id
            ]);
        }
    }

    public function patientInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->service->patient)
            ->columns(2)
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make($this->onlyView ? null : 'Classificação de Risco')
                    ->columns(1)
                    ->schema([
                        Textarea::make('description')
                            ->label('Descrição')
                            ->required()
                            ->rows(5)
                            ->disabled($this->onlyView),
                        Select::make('flowchart')
                            ->label('Fluxograma')
                            ->options(Flowchart::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->disabled($this->onlyView),
                        Select::make('classification_id')
                            ->label('Discriminador')
                            ->reactive()
                            ->options(function (callable $get) {
                                if ($get('flowchart'))  {
                                    return ModelsClassification::where('flowchart_id', $get('flowchart'))->with('discriminator')->get()->pluck('discriminator.name', 'id')->toArray();
                                }

                                return [];
                            })
                            ->disabled($this->onlyView),
                        TextInput::make('classification')
                            ->label(false)
                            ->disabled()
                            ->extraInputAttributes(function (callable $get, callable $set) {
                                if ($classificationId = $get('classification_id')) {
                                    $classification = ModelsClassification::find($classificationId);
                                    $set('classification', config('classification.descriptions.' . $classification->value));
                                    return ['style' => "text-align: center; color: #000 !important; background-color: ". config('classification.colors.' . $classification->value) .";", "id" => "classificationInput"];
                                } else {
                                    $set('classification', '');
                                }
                                return [];
                            })
                        ])
                        ->disabled($this->onlyView)
                    ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {

            if (!$this->service->screening) {
                Screening::create([
                    'description' => $data['description'],
                    'classification_id' => $data['classification_id'],
                    'nurse_id' => auth()->id(),
                    'service_id' => $this->service->id
                ]);

                $this->service->update([
                    'status' => ServiceStatus::SCREENING
                ]);

                Notification::make('screening_created_notification')
                    ->title('Classificação de risco realizada com sucesso!')
                    ->success()
                    ->send();

            } else {
                $this->service->screening->update([
                    'description' => $data['description'],
                    'classification_id' => $data['classification_id']
                ]);

                Notification::make('screening_update_notification')
                    ->title('Classificação de risco atualizada com sucesso!')
                    ->success()
                    ->send();
            }
       });
    }
}
