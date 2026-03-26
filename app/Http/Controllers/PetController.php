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
        return view('pets.create');
    }

    public function store(Request $request)
    {
        Pet::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'age' => $request->age
        ]);

        return redirect()->route('pets.index')
            ->with('success', 'Pet cadastrado com sucesso!');    }

    public function show(Pet $pet)
    {
        //
    }

    public function edit(Pet $pet)
    {
        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $pet->update([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'age' => $request->age
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
