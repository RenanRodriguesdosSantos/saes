<?php

use App\Enums\UserRole;
use App\Http\Controllers\PrintsController;
use App\Livewire\Appointment\Attended;
use App\Livewire\Appointment\Make;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'consulta-medica',
    'as' => 'appointment.',
    'middleware' => [
        'auth',
        'role:' . UserRole::DOCTOR
    ]
], function () {
    Route::get('/atendimentos/{status}', Attended::class)->name('attended');
    Route::get('/atender/{appointment}', Make::class)->name('make');
    Route::group([
        'prefix' => 'imprimir',
        'as' => 'prints.',
        'controller' => PrintsController::class
    ], function () {
        Route::get('/receita/{recipe}', 'recipe')->name('recipe');
        Route::get('/atestado/{certificate}', 'certificate')->name('certificate');
        Route::get('/encaminhamento/{forwarding}', 'forwarding')->name('forwarding');
    });
});