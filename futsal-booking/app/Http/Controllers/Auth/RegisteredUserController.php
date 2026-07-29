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
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('login', ['mode' => 'register']);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                'email:rfc,dns',
                'unique:'.User::class
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.min' => 'Nama terlalu pendek, masukkan nama lengkap yang benar.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi, tidak boleh angka atau simbol.',
            'email.email' => 'Format email atau domain tidak valid/tidak ditemukan. Masukkan kembali email Anda.',
            'email.unique' => 'Email ini sudah terdaftar. Masukkan kembali email Anda.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
