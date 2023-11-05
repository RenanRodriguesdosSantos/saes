<?php

namespace App\Livewire\Ambulatory;

use App\Enums\ExamStatus;
use App\Models\Appointment;
use App\Models\Exam;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
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

class ExamMake extends Component implements HasForms, HasActions, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithActions;
    use InteractsWithInfolists;

    public Appointment $appointment;
    public array $data;

    public function mount()
    {
        $data = [];

        $this->appointment
            ->examItems()
            ->get()
            ->each(function ($exam) use (&$data) {
                $data["status_{$exam->exam_id}"] = $exam->status;
                $data["note_{$exam->exam_id}"] = $exam->technician_note;
            });

        $this->form->fill($data);
    }

    public function render()
    {
        return view('livewire.ambulatory.exam-make');
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

    public function form(Form $form) : Form
    {
        return $form
            ->columns(2)
            ->schema(function () {
                $schema = [];

                $this->appointment
                    ->exams()
                    ->get()
                    ->each(function (Exam $exam) use (&$schema) {
                        $schema[] = Fieldset::make($exam->name)
                            ->columnSpan(1)
                            ->schema([
                                Select::make("status_{$exam->id}")
                                    ->label('Status')
                                    ->required()
                                    ->options(ExamStatus::asSelectArray()),
                                TextInput::make("note_{$exam->id}")
                                    ->label('Observações')
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
            $this->appointment
                    ->examItems()
                    ->get()
                    ->each(function ($exam) use ($data) {
                        if ($data["status_{$exam->exam_id}"] != $exam->status || $data["note_{$exam->exam_id}"] != $exam->technician_note) {
                            $exam->update([
                                'technician_id' => auth()->id(),
                                'technician_note' => $data["note_{$exam->exam_id}"],
                                'status' => $data["status_{$exam->exam_id}"]
                            ]);
                        }
                    });


            Notification::make('exam_store_notification')
                ->title('Atendimento salvo com sucesso!')
                ->success()
                ->send();
       });
    }
}
