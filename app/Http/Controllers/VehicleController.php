<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Вывод списка всех машин
    public function index()
    {
        $vehicles = Vehicle::latest()->get();
        return view('vehicles.index', compact('vehicles'));
    }

    // Сохранение новой машины
    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20|unique:vehicles',
            'fuel_type' => 'required|string|max:50',
            'base_consumption' => 'required|numeric|min:0',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Транспортное средство успешно добавлено!');
    }

    // Удаление машины
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Транспорт удален из автопарка.');
    }
}
