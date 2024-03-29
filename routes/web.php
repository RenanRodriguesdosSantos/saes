<?php

use App\Http\Controllers\LogoutController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Profile;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function() {
    Route::get('/login', Login::class)->name('login');
    // Route::get('/resetar-senha', ResetPassword::class)->name('password.reset');
    Route::get('/esqueceu-senha', ForgotPassword::class)->name('password.forgot');
});

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/perfil', Profile::class)->name('profile');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

require __DIR__.'/reception.php';
require __DIR__.'/screening.php';
require __DIR__.'/appointment.php';
require __DIR__.'/ambulatory.php';