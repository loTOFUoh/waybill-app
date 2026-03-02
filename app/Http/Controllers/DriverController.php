<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

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
            'is_active' => 'boolean',
        ]);

        Driver::create([
            'full_name' => $request->full_name,
            'license_number' => $request->license_number,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('drivers.index')->with('success', 'Водитель успешно добавлен в систему!');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Профиль водителя удален.');
    }
}
