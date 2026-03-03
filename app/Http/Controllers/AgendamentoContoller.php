<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendamentoContoller extends Controller
{
    use App\Models\Agendamento;
    use App\Models\Pet;
    use App\Models\Servico;
    use Illuminate\Http\Request;
    use Illuminate\Suport\Facades\Auth;

    public function create(){
        $pets = Pet::where('user_id', Auth::id())->get();
        $servicos = Servico::all();

        return view('agendamento.create', compact('pets', 'servicos'));
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

        return redirect()->route('agendamento.create')
                         ->('sucess', 'Agendamento realizado com sucesso!');
    }
}
