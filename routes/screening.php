<?php

use App\Livewire\Screening\Attended;
use App\Livewire\Screening\Make;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'triagem',
    'as' => 'screening.',
    'middleware' => [
        'auth'
    ]
], function () {
    Route::get('/atendimentos', Attended::class)->name('attended');
    Route::get('/atender/{service}', Make::class)->name('make');
});