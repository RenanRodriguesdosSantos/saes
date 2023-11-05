<?php

namespace App\Livewire\Appointment;

use App\Enums\ExamStatus;
use App\Enums\ExamType;
use App\Models\Appointment;
use App\Models\AppointmentExam;
use App\Models\Exam;
use App\Models\Recipe;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class Exams extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    private $hasDescription = [];
    public Appointment $appointment;

    public function render()
    {
        return view('livewire.appointment.exams');
    }

    public function table(Table $table) : Table
    {
        return $table
            ->query(AppointmentExam::whereHas('appointment', fn (Builder $query) => $query->where('service_id', $this->appointment->service_id)))
            ->columns([
                TextColumn::make('exam.name')
                    ->label('Exame'),
                TextColumn::make('description')
                    ->label('Descrição'),
                TextColumn::make('doctor.name')
                    ->label('Médico'),
                TextColumn::make('updated_at')
                    ->label('Data e hora')
                    ->date('d/m/Y H:i'),
            ])
            ->headerActions([
                Action::make('exams_create')
                    ->form($this->getFormSchema())
                    ->label('Adicionar exames')
                    ->action(function(array $data) {
                        Exam::type($data['type'])
                            ->whereDoesntHave('appointments', fn (Builder $query) => $query->where('appointments.id', $this->appointment->id))
                            ->get()
                            ->each(function (Exam $exam) use ($data) {
                                if ($data["exam_{$exam->id}"]) {
                                    $note = $data["has_note_{$exam->id}"] ? $data["note_{$exam->id}"] : null;

                                    $this->appointment->exams()
                                        ->attach($exam->id, [
                                            'doctor_id' => auth()->id(),
                                            'status' => ExamStatus::PENDING,
                                            'doctor_note' => $note
                                        ]);

                                }
                            });

                        Notification::make('exams_created_notification')
                            ->title('Exames adicionados com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Salvar')
            ])
            ->actions([
                Action::make('exam_edit')
                    ->form([
                        Textarea::make("note")
                            ->label('Descrição / Justificativa')
                    ])
                    ->label('editar')
                    ->fillForm(function($record) {
                        return $record->toArray();
                    })
                    ->action(function($record, array $data) {
                        $record->update($data);

                        Notification::make('exams_updated_notification')
                            ->title('Exame atualizado com sucesso!')
                            ->success()
                            ->send();

                    })->modalSubmitActionLabel('Salvar')
            ]);
    }

    private function getFormSchema(): array
    {
        return [
            Select::make('type')
                ->label('Tipo')
                ->required()
                ->reactive()
                ->options(ExamType::asSelectArray()),
            Section::make()
                ->columns(2)
                ->schema(function ($get) {
                    $schema = [];

                    Exam::type($get('type'))
                        ->whereDoesntHave('appointments', fn (Builder $query) => $query->where('appointments.id', $this->appointment->id))
                        ->get()
                        ->each(function (Exam $exam) use (&$schema) {
                            $schema[] = Grid::make(1)
                                ->columnSpan(1)
                                ->schema([
                                    Checkbox::make("exam_{$exam->id}")
                                        ->label($exam->name)
                                        ->live()
                                        ->hintAction(
                                            FormAction::make("action_$exam->id")
                                                ->label(false)
                                                ->icon('heroicon-m-pencil-square')
                                                ->action(fn ($set, $get) => $set("has_note_$exam->id", !$get("has_note_$exam->id")))
                                                ->disabled(fn ($get) => !$get("exam_{$exam->id}") || !!$this->appointment->exams()->find($exam->id))
                                        )
                                        ->disabled(fn() => !!$this->appointment->exams()->find($exam->id))
                                        ->default(true),
                                        Hidden::make("has_note_$exam->id")
                                            ->default(false),
                                        Textarea::make("note_$exam->id")
                                            ->label('Descrição / Justificativa')
                                            ->hidden(fn ($get) => !$get("exam_{$exam->id}") || !$get("has_note_$exam->id"))
                                ]);
                        });

                    return $schema;
                })
        ];
    }
}