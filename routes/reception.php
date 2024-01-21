<?php

use App\Enums\UserRole;
use App\Livewire\Reception\Attended;
use App\Livewire\Reception\Prohibited;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'recepcao',
    'as' => 'reception.',
    'middleware' => [
        'auth',
        'role:' . UserRole::RECEPTIONIST
    ]
], function () {
    Route::get('/entrada', Prohibited::class)->name('prohibited');
    Route::get('/atendimentos', Attended::class)->name('attended');
});