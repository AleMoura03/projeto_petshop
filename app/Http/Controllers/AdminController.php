<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AdminController extends Controller
{
    public function dashboard()
    {
        $appointments = Appointment::all();

        return view('admin.dashboard', compact('appointments'));
    }
}