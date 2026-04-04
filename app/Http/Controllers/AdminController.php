<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\User;

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

        return view('admin.dashboard', compact('agendamentos', 'pendingAdmins'));
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
}