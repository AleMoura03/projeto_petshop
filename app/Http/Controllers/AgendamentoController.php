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
            ->where('cliente_excluiu', false)
            ->with('pet', 'servico')
            ->get();

        return view('agendamentos.index', compact('agendamentos'));
    }

    public function store(Request $request)
    {
        $pet = Pet::find($request->pet_id);
        $servico = Servico::find($request->servico_id);

        $porte = strtolower(trim($pet->porte));

        $preco = match ($porte) {
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

        if ($agendamento->user_id != Auth::id()) {
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            return redirect()->route('agendamentos.index')->with('error', 'Não é possível editar um agendamento que já foi processado.');
        }

        $pets = Pet::where('user_id', Auth::id())->get();
        $servicos = Servico::all();

        return view('agendamentos.edit', compact('agendamento', 'pets', 'servicos'));
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->user_id != Auth::id()) {
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            return redirect()->route('agendamentos.index')->with('error', 'Não é possível editar um agendamento que já foi processado.');
        }

        $pet = Pet::findOrFail($request->pet_id);

        if ($pet->user_id != Auth::id()) {
            abort(403);
        }

        $servico = Servico::findOrFail($request->servico_id);

        $porte = strtolower(trim($pet->porte));

        $preco = match ($porte) {
            'mini' => $servico->preco_mini,
            'pequeno' => $servico->preco_pequeno,
            'medio' => $servico->preco_medio,
            'grande' => $servico->preco_grande,
            'gigante' => $servico->preco_gigante,
            default => 0
        };


        $agendamento->pet_id = $request->pet_id;
        $agendamento->servico_id = $request->servico_id;
        $agendamento->data = $request->data;
        $agendamento->hora = $request->hora;
        $agendamento->preco = $preco;

        $agendamento->save();

        return redirect()->route('agendamentos.index');
    }

    public function destroy(Agendamento $agendamento)
    {
        if ($agendamento->user_id != auth()->id()) {
            abort(403);
        }

        $agendamento->cliente_excluiu = true;
        $agendamento->save();

        return redirect()->route('agendamentos.index')
            ->with('success', 'Agendamento removido da sua lista!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('agendamento_ids', []);
        if (!empty($ids)) {
            Agendamento::whereIn('id', $ids)->where('user_id', auth()->id())->update(['cliente_excluiu' => true]);
            return redirect()->route('agendamentos.index')->with('success', count($ids) . ' serviço(s) ocultado(s) da tela.');
        }
        return redirect()->route('agendamentos.index')->with('error', 'Nenhum item selecionado.');
    }

    public function cancelar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->user_id != Auth::id()) {
            abort(403);
        }

        $agendamento->status = 'cancelado_cliente';
        $agendamento->save();

        return redirect()->route('agendamentos.index')
            ->with('success', 'Serviço cancelado com sucesso.');
    }

    public function adminDestroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        
        $agendamento->admin_excluiu = true;
        $agendamento->save();

        return back()->with('success', 'Agendamento removido da sua visualização!');
    }

    public function adminBulkDestroy(Request $request)
    {
        $ids = $request->input('agendamento_ids', []);
        if (!empty($ids)) {
            Agendamento::whereIn('id', $ids)->update(['admin_excluiu' => true]);
            return back()->with('success', count($ids) . ' serviço(s) ocultado(s) do painel.');
        }
        return back()->with('error', 'Nenhum agendamento selecionado.');
    }

    public function adminIndex()
    {
        $agendamentos = Agendamento::with('pet', 'servico', 'user')
            ->where('admin_excluiu', false)    
            ->get();

        return view('admin.agendamentos.index', compact('agendamentos'));
    }

    public function aprovar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $agendamento->status = 'aprovado';
        $agendamento->save();

        return back()->with('success', 'Agendamento aprovado!');
    }

    public function recusar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $agendamento->status = 'recusado';
        $agendamento->save();

        return back()->with('success', 'Agendamento recusado!');
    }
}
