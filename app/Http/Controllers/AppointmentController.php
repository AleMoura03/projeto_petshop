<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Pet;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->id())
            ->with('pet')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pets = Pet::where('user_id', auth()->id())->get();

        return view('appointments.create', compact('pets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exists = Appointment::where('date', $request->date)
            ->where('time', $request->time)->exists();

        if ($exists) {
            return back()->with('error', 'Horário já está ocupado.');
        }

        Appointment::create([
            'user_id' => auth()->id(),
            'pet_id' => $request->pet_id,
            'service' => $request->service,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pendente'
        ]);

        return redirect()->route('client.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
