<?php

use App\Enums\UserRole;
use App\Livewire\Screening\Attended;
use App\Livewire\Screening\Make;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'triagem',
    'as' => 'screening.',
    'middleware' => [
        'auth',
        'role:' . UserRole::NURSE
    ]
], function () {
    Route::get('/atendimentos/{status}', Attended::class)->name('attended');
    Route::get('/atender/{service}', Make::class)->name('make');
});