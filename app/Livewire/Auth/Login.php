<?php

namespace App\Livewire\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?array $data = [];

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->email()
                    ->placeholder('Email')
                    ->required()
                    ->extraInputAttributes(['name' => 'email']),
                TextInput::make('password')
                    ->label('Senha')
                    ->placeholder('Senha')
                    ->password()
                    ->required()
                    ->extraInputAttributes(['name' => 'password']),
                Checkbox::make('remember')
                    ->label('Lembrar de mim')
                    ->extraInputAttributes(['name' => 'remember'])
            ])
            ->statePath('data');
    }

    public function authenticate()
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'data.email' => __('Muitas tentativas de login. Por favor, aguarde :seconds segundos para tentar novamente.', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data = $this->form->getState();

        if (! Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $data['remember'])) {
            throw ValidationException::withMessages([
                'data.email' => 'Email ou senha incorretos',
            ]);
        }

        session()->regenerate();
    }
}
