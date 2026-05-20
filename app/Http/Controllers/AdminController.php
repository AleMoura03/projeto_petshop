<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\User;
use App\Models\Pet;
use App\Models\Servico;

class AdminController extends Controller
{
    public function dashboard()
    {
        $agendamentos = Agendamento::with('pet', 'servico', 'user')
            ->where('admin_excluiu', false)
            ->get();

        $pendingAdmins = User::where('role', 'admin')
            ->where('is_approved', false)
            ->get();

        $allAdmins = [];
        if (auth()->user()->is_super_admin) {
            $allAdmins = User::where('role', 'admin')
                ->where('id', '!=', auth()->id())
                ->get();
        }

        return view('admin.dashboard', compact('agendamentos', 'pendingAdmins', 'allAdmins'));
    }

    public function approveAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            $user->update(['is_approved' => true]);
        }
        return back()->with('success', 'AUdministrador aprovado com sucesso!');
    }

    public function rejectAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin' && !$user->is_approved) {
            $user->delete();
        }
        return back()->with('success', 'Solicitação de AUdministrador recusada e removida.');
    }

    public function destroyAdmin($id)
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }

        $admin = User::findOrFail($id);

        if ($admin->role !== 'admin' || $admin->is_super_admin) {
            abort(403);
        }

        $admin->delete();

        return back()->with('success', 'Conta de AUdministrador excluída com sucesso!');
    }

    public function createAdmin()
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }
        return view('admin.users.create');
    }

    public function storeAdmin(Request $request)
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        return back()->with('success', 'AUdministrador criado e aprovado com sucesso!');
    }

    public function relatorios(Request $request)
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }

        $query = Agendamento::with('pet', 'servico', 'user');

        $status = $request->input('status', 'aprovado');
        if ($status !== 'todos') {
            $query->where('status', $status);
        }

        if ($request->filled('cliente_id')) {
            $query->where('user_id', $request->cliente_id);
        }

        if ($request->filled('servico_id')) {
            $query->where('servico_id', $request->servico_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }

        if ($request->filled('especie')) {
            $query->whereHas('pet', function($q) use ($request) {
                $q->where('species', $request->especie);
            });
        }

        $agendamentos = $query->orderBy('data', 'desc')->get();
        
        // Faturamento Total apenas de serviços aprovados e efetuados
        $totalFaturamento = $agendamentos->whereIn('status', ['aprovado', 'efetuado'])->sum('preco');

        $clientes = User::where('role', 'cliente')->orderBy('name')->get();
        $servicos = \App\Models\Servico::orderBy('nome')->get();

        return view('admin.relatorios', compact('agendamentos', 'totalFaturamento', 'clientes', 'servicos'));
    }

    public function clientesIndex()
    {
        $clientes = User::where('role', 'cliente')
            ->withCount('pets')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.clientes.index', compact('clientes'));
    }

    public function storeCliente(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'whatsapp' => 'required|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'cliente',
            'whatsapp' => $request->whatsapp,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Conta de cliente criada com sucesso!');
    }

    public function destroyCliente($id)
    {
        $cliente = User::findOrFail($id);

        if ($cliente->role !== 'cliente') {
            abort(403);
        }

        // Delete cascade depends on DB, but eloquent can delete relations if setup.
        // Actually it's better to let foreign keys handle cascade, or just delete the user.
        // Assumes pets and agendamentos have onDelete cascade, or will be orphaned if nullable.
        $cliente->delete();

        return back()->with('success', 'Conta de cliente e seus dados excluídos com sucesso!');
    }

    public function createPet($id)
    {
        $cliente = User::findOrFail($id);
        
        $racas = [
            'SRD - Sem Raça Definida',
            'Shih Tzu',
            'Labrador',
            'Golden Retriever',
            'Bulldog',
            'Poodle',
            'Pastor Alemão',
            'Rottweiler',
            'Yorkshire',
            'Pinscher'
        ];

        return view('admin.clientes.pets.create', compact('cliente', 'racas'));
    }

    public function storePet(Request $request, $id)
    {
        $cliente = User::findOrFail($id);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pets'), $fileName);
            $fotoPath = '/uploads/pets/' . $fileName;
        }

        Pet::create([
            'user_id' => $cliente->id,
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'porte' => $request->porte,
            'foto' => $fotoPath
        ]);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Pet cadastrado com sucesso para o cliente ' . $cliente->name);
    }

    public function createAgendamento($id)
    {
        $cliente = User::findOrFail($id);
        $pets = $cliente->pets;
        
        if ($pets->isEmpty()) {
            return redirect()->route('admin.clientes.index')
                ->with('error', 'O cliente ' . $cliente->name . ' precisa ter pelo menos um pet cadastrado antes de agendar.');
        }

        $servicos = Servico::all();

        return view('admin.clientes.agendamentos.create', compact('cliente', 'pets', 'servicos'));
    }

    public function storeAgendamento(Request $request, $id)
    {
        $cliente = User::findOrFail($id);
        
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required'
        ]);

        $pet = Pet::where('id', $request->pet_id)->where('user_id', $cliente->id)->firstOrFail();
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

        Agendamento::create([
            'user_id' => $cliente->id,
            'pet_id' => $pet->id,
            'servico_id' => $servico->id,
            'data' => $request->data,
            'hora' => $request->hora,
            'preco' => $preco,
            'status' => 'aprovado' // Admin creates it already approved!
        ]);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Agendamento criado e aprovado com sucesso para ' . $cliente->name);
    }
}