<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Pet;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    public function create()
    {
        $pets = Auth::user()->pets;
        $servicos = Servico::all();

        return view('agendamentos.create', compact('pets', 'servicos'));
    }

    public function index()
    {
        $agendamentos = Agendamento::where('user_id', Auth::id())
            ->with('pet', 'servico')
            ->get();

        return view('agendamentos.index', compact('agendamentos'));
    }

    public function store(Request $request)
    {
        $pet = Pet::find($request->pet_id);
        $servico = Servico::find($request->servico_id);


        $preco = match ($pet->porte) {
            'mini' => $servico->preco_mini,
            'pequeno' => $servico->preco_pequeno,
            'medio' => $servico->preco_medio,
            'grande' => $servico->preco_grande,
            'gigante' => $servico->preco_gigante,
            default => 0
        };

        Agendamento::create([
            'user_id' => Auth::id(),
            'pet_id' => $request->pet_id,
            'servico_id' => $request->servico_id,
            'data' => $request->data,
            'hora' => $request->hora,
            'preco' => $preco,
            'status' => 'pendente'
        ]);

        return redirect()->route('agendar')->with('success', 'Agendamento realizado com sucesso!');
    }

    public function edit($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $pets = Pet::where('user_id', Auth::id())->get();
        $servicos = Servico::all();

        return view('agendamentos.edit', compact('agendamento', 'pets', 'servicos'));
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $agendamento->update([
            'pet_id' => $request->pet_id,
            'servico_id' => $request->servico_id,
            'data' => $request->data,
            'hora' => $request->hora
        ]);

        return redirect()->route('agendamentos.index');
    }

    public function destroy(Agendamento $agendamento)
    {
        if ($agendamento->user_id != auth()->id()) {
            abort(403);
        }

        $agendamento->delete();

        return redirect()->route('agendamentos.index')
            ->with('success', 'Agendamento cancelado!');
    }
}
