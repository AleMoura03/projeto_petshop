<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::where('user_id', auth()->id())->get();

        return view('pets.index', compact('pets'));
    }

    public function create()
    {
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

        return view('pets.create', compact('racas'));
    }

    public function store(Request $request)
    {
        Pet::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'porte' => $request->porte
        ]);

        return redirect()->route('pets.index')
            ->with('success', 'Pet cadastrado com sucesso!');
    }

    public function show(Pet $pet)
    {
        //
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);

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

        return view('pets.edit', compact('pet', 'racas'));
    }

    public function update(Request $request, Pet $pet)
    {
        $pet->update([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'porte' => $request->porte
        ]);

        return redirect()->route('pets.index');
    }

    public function destroy(Pet $pet)
    {
        if ($pet->user_id != auth()->id()) {
            abort(403);
        }

        $pet->delete();

        return redirect()->route('pets.index')
            ->with('success', 'Pet excluído com sucesso!');
    }
}
