<?php

use App\Livewire\Appointment\Attended;
use App\Livewire\Appointment\Make;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'consulta-medica',
    'as' => 'appointment.',
    'middleware' => [
        'auth'
    ]
], function () {
    Route::get('/atendimentos', Attended::class)->name('attended');
    Route::get('/atender/{appointment}', Make::class)->name('make');
});