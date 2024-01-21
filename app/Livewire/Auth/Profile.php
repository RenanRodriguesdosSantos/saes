<?php

namespace App\Livewire\Auth;

use App\Enums\UserRole;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Livewire\Component;

class Profile extends Component implements HasInfolists, HasForms
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public function render()
    {
        return view('livewire.auth.profile');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record(auth()->user())
            ->schema([
                TextEntry::make('name')
                    ->label('Nome'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('council_number')
                    ->label(function ($record) {
                        switch ($record->first_role->name) {
                            case UserRole::DOCTOR:
                                return 'CRM - ' . $record->councilState->uf;
                                break;
                            case UserRole::NURSE:
                            case UserRole::NURSING_TECHNICIAN:
                                return 'COREN - ' . $record->councilState->uf;
                                break;
                            
                            default:
                                return '';
                                break;
                        }
                    })
                    ->hidden(fn ($record) => $record->first_role->name == UserRole::RECEPTIONIST),
                TextEntry::make('first_role.name')
                    ->label('Papel')
                    ->formatStateUsing(fn ($state) => UserRole::getDescription($state))
            ])
            ->columns();
    }
}
