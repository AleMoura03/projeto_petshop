<?php

use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::middleware(['auth'])->group(function () {
});

Route::middleware(['auth', 'client'])->group(function () {
    Route::delete('pets/bulk-destroy', [PetController::class, 'bulkDestroy'])->name('pets.bulk_destroy');
    Route::resource('pets', PetController::class);
});

Route::get('/admin/agendamentos', [AgendamentoController::class, 'adminIndex'])->name('admin.agendamentos');
Route::post('/admin/agendamentos/{id}/aprovar', [AgendamentoController::class, 'aprovar'])->name('agendamentos.aprovar');
Route::post('/admin/agendamentos/{id}/recusar', [AgendamentoController::class, 'recusar'])->name('agendamentos.recusar');
Route::delete('/admin/agendamentos/bulk-destroy', [AgendamentoController::class, 'adminBulkDestroy'])->name('admin.agendamentos.bulk_destroy');
Route::delete('/admin/agendamentos/{id}', [AgendamentoController::class, 'adminDestroy'])->name('admin.agendamentos.destroy');

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/home', function () {
        return redirect()->route('client.dashboard');
    });

    Route::get('/agendamentos', [AgendamentoController::class, 'index'])
        ->name('agendamentos.index');

    Route::get('/agendamentos/{agendamento}/edit', [AgendamentoController::class, 'edit'])
        ->name('agendamentos.edit');

    Route::put('/agendamentos/{agendamento}', [AgendamentoController::class, 'update'])
        ->name('agendamentos.update');

    Route::get('/agendar', [AgendamentoController::class, 'create'])
        ->name('agendar');

    Route::post('/agendar', [AgendamentoController::class, 'store'])
        ->name('agendar.store');

    Route::post('/agendamentos/{agendamento}/cancelar', [AgendamentoController::class, 'cancelar'])
        ->name('agendamentos.cancelar');

    Route::delete('/agendamentos/bulk-destroy', [AgendamentoController::class, 'bulkDestroy'])
        ->name('agendamentos.bulk_destroy');

    Route::delete('/agendamentos/{agendamento}', [AgendamentoController::class, 'destroy'])
        ->name('agendamentos.destroy');



    Route::get('/client/dashboard', function () {
        return view('dashboard');
    })->name('client.dashboard');
});


Route::get('/dashboard', function () {
    return redirect()->route('client.dashboard');
});

Route::middleware(['auth','admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::post('/users/{id}/approve', [AdminController::class, 'approveAdmin'])->name('admin.users.approve');
    Route::delete('/users/{id}/reject', [AdminController::class, 'rejectAdmin'])->name('admin.users.reject');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});




require __DIR__ . '/auth.php';