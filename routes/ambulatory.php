<?php

use App\Livewire\Ambulatory\ExamAttended;
use App\Livewire\Ambulatory\ExamMake;
use App\Livewire\Ambulatory\PrescriptionAttended;
use App\Livewire\Ambulatory\PrescriptionMake;
use App\Livewire\Screening\Attended;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'ambulatorio',
    'as' => 'ambulatory.',
    'middleware' => [
        'auth'
    ]
], function () {
    Route::group([
        'prefix' => 'exames',
        'as' => 'exams.'
    ], function () {
        Route::get('/atendimentos', ExamAttended::class)->name('attended');
        Route::get('/atender/{appointment}', ExamMake::class)->name('make');
    });

    Route::group([
        'prefix' => 'prescricoes',
        'as' => 'prescriptions.'
    ], function () {
        Route::get('/atendimentos', PrescriptionAttended::class)->name('attended');
        Route::get('/atender/{prescription}', PrescriptionMake::class)->name('make');
    });
});