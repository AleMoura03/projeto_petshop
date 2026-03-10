<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
        public function index()
    {
        
    }

    public function create()
    {
        //
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

        return redirect()->route('pets.index');
    }

    public function show(Pet $pet)
    {
        //
    }

    public function edit(Pet $pet)
    {
        //
    }

    public function update(Request $request, Pet $pet)
    {
        //
    }

    public function destroy(Pet $pet)
    {
        //
    }
}
