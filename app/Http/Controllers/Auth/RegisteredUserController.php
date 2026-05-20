<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role') === 'admin' ? 'admin' : 'cliente';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($role === 'cliente') {
            $rules['whatsapp'] = ['required', 'string', 'max:20'];
        }

        $request->validate($rules);

        $is_approved = $role === 'admin' ? false : true;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'is_approved' => $is_approved,
            'whatsapp' => $role === 'cliente' ? $request->whatsapp : null,
        ]);

        event(new Registered($user));

        if ($role === 'admin') {
            return redirect()->route('login')->with('status', 'Status: Sua conta de AUdministrador foi solicitada e aguarda aprovação de nossos gestores!');
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
