<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Pet;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
     public function create(){
        $pets = Auth::user()->pets;
        $servicos = Servico::all();

        return view('agendamentos.create', compact('pets', 'servicos'));
    }

    public function store (Request $request){
        $request->validate([
            'pet_id' => 'required',
            'servico_id' => 'required',
            'data' => 'required|date',
            'hora' => 'required'
        ]);

        Agendamento::create([
            'user_id' => Auth::id(),
            'pet_id' => $request->pet_id,
            'servico_id' => $request->servico_id,
            'data' => $request->data,
            'hora' => $request->hora
        ]);

        return redirect()->route('agendar')->with('success', 'Agendamento realizado com sucesso!');
    }
}
