<?php

use App\Http\Controllers\AgendamentoController;

Route::middleware('auth')->group(function (){
    Route::get('/agendar', [AgendamentoController::class, 'create'])->name('agendamento.create');
    Route::post('/agendar', [AgendamentoController::class, 'store'])->name('agendamento.store');
});