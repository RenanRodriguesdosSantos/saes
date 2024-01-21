<?php

namespace App\Livewire\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePassword extends Component implements HasForms
{
    use InteractsWithForms;

    public array $data;

    public function render()
    {
        return view('livewire.auth.update-password');
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Senha atual')
                    ->placeholder('Senha atual')
                    ->password()
                    ->required()
                    ->currentPassword(),
                TextInput::make('password')
                    ->label('Nova senha')
                    ->placeholder('Nova senha')
                    ->password()
                    ->required()
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->label('Confirmação da nova senha')
                    ->placeholder('Confirmação da nova senha')
                    ->password()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();
        
        /** @var User */
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        Notification::make('update_password')
            ->title('Senha atualizada com sucesso!')
            ->success()
            ->send();
    }
}
