<?php

use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::middleware(['auth'])->group(function () {
});

Route::middleware(['auth'])->group(function () {
    Route::resource('pets', PetController::class);
});

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');


    Route::get('/agendar', [AgendamentoController::class, 'create'])
        ->name('agendar');

    Route::post('/agendar', [AgendamentoController::class, 'store'])
        ->name('agendar.store');

    Route::get('/admin/dashboard', function (){
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/client/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');
});


Route::get('/dashboard', function () {
    return redirect()->route('client.dashboard');
});

Route::middleware(['auth','admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class,'dashboard'])->name('admin.dashboard');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});




require __DIR__ . '/auth.php';