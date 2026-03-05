<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::latest()->get();
        return view('drivers.index', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:50|unique:drivers',
            'email' => 'required|email|unique:users,email',
            'is_active' => 'boolean',
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);

        $user->markEmailAsVerified();

        Driver::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'license_number' => $request->license_number,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('drivers.index')->with('success', 'Водитель добавлен. Логин: ' . $request->email . ' | Пароль: password');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->user_id) {
            User::where('id', $driver->user_id)->delete();
        }
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Профиль водителя удален.');
    }
}
