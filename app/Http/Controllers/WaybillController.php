<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waybill;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Services\FuelCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class WaybillController extends Controller
{
    // Вывод списка путевых листов
    public function index()
    {
        $user = Auth::user();

        // Админ видит всё, оператор — только свои
        if ($user->role === 'admin') {
            $waybills = Waybill::with(['driver', 'vehicle', 'user'])->latest()->get();
        } else {
            $waybills = Waybill::with(['driver', 'vehicle'])->where('user_id', $user->id)->latest()->get();
        }

        return view('waybills.index', compact('waybills'));
    }

    public function create()
    {
        $drivers = Driver::where('is_active', true)->get();
        $vehicles = Vehicle::all();

        return view('waybills.create', compact('drivers', 'vehicles'));
    }

    public function store(Request $request, FuelCalculatorService $calculator)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_km' => 'required|integer|min:0',
            'end_km' => 'required|integer|gte:start_km',
            'fuel_start' => 'required|numeric|min:0',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        $fuelConsumed = $calculator->calculate(
            $request->start_km,
            $request->end_km,
            $vehicle->base_consumption
        );

        $fuelEnd = $request->fuel_start - $fuelConsumed;

        // Здесь теперь используется реальный ID авторизованного пользователя
        $waybill = Waybill::create([
            'number' => 'ПЛ-' . strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'start_km' => $request->start_km,
            'end_km' => $request->end_km,
            'fuel_start' => $request->fuel_start,
            'fuel_end' => $fuelEnd > 0 ? $fuelEnd : 0,
            'fuel_consumed' => $fuelConsumed,
            'status' => 'closed',
        ]);

        return redirect()->route('waybills.index')->with('success', 'Путевой лист успешно создан!');
    }

    public function downloadPdf(Waybill $waybill)
    {
        // Защита: оператор не может скачать чужой лист
        if (Auth::user()->role !== 'admin' && $waybill->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому документу.');
        }

        $waybill->load(['driver', 'vehicle', 'user']);
        $pdf = Pdf::loadView('waybills.pdf', compact('waybill'));
        return $pdf->download('Путевой_лист_' . $waybill->number . '.pdf');
    }
}
