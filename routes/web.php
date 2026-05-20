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
Route::post('/admin/agendamentos/{id}/efetuar', [AgendamentoController::class, 'efetuar'])->name('agendamentos.efetuar');
Route::post('/admin/agendamentos/{id}/lembrete', [AgendamentoController::class, 'enviarLembrete'])->name('agendamentos.lembrete');

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'client'])->group(function () {

    Route::get('/home', function () {
        return redirect()->route('client.dashboard');
    });

    Route::get('/agendamentos', [AgendamentoController::class, 'index'])
        ->name('agendamentos.index');

    Route::get('/relatorio-gastos', [AgendamentoController::class, 'relatorioGastos'])
        ->name('cliente.relatorios');

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



    Route::get('/client/dashboard', function () {
        return view('dashboard');
    })->name('client.dashboard');
});


Route::get('/dashboard', function () {
    return redirect()->route('client.dashboard');
})->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/relatorios', [AdminController::class, 'relatorios'])->name('admin.relatorios');
    
    // Admin Users Management (Super Admin)
    Route::get('/users/create', [AdminController::class, 'createAdmin'])->name('admin.users.create');
    Route::post('/users/store', [AdminController::class, 'storeAdmin'])->name('admin.users.store');
    
    Route::post('/users/{id}/approve', [AdminController::class, 'approveAdmin'])->name('admin.users.approve');
    Route::delete('/users/{id}/reject', [AdminController::class, 'rejectAdmin'])->name('admin.users.reject');
    Route::delete('/users/{id}', [AdminController::class, 'destroyAdmin'])->name('admin.users.destroy');
    
    // Gerenciamento de Clientes
    Route::get('/clientes', [AdminController::class, 'clientesIndex'])->name('admin.clientes.index');
    Route::post('/clientes', [AdminController::class, 'storeCliente'])->name('admin.clientes.store');
    Route::delete('/clientes/{id}', [AdminController::class, 'destroyCliente'])->name('admin.clientes.destroy');

    // Admin criando dados para Clientes
    Route::get('/clientes/{id}/pets/create', [AdminController::class, 'createPet'])->name('admin.clientes.pets.create');
    Route::post('/clientes/{id}/pets', [AdminController::class, 'storePet'])->name('admin.clientes.pets.store');
    Route::get('/clientes/{id}/agendamentos/create', [AdminController::class, 'createAgendamento'])->name('admin.clientes.agendamentos.create');
    Route::post('/clientes/{id}/agendamentos', [AdminController::class, 'storeAgendamento'])->name('admin.clientes.agendamentos.store');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});




require __DIR__ . '/auth.php';