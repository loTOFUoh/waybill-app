<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waybill;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\User;
use App\Services\FuelCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WaybillController extends Controller
{
    /**
     * Проверка прав доступа к конкретному путевому листу
     */
    private function checkAccess(User $user, Waybill $waybill): void
    {
        $isOwner = $waybill->user_id === $user->id; // Создатель (Диспетчер)
        $isDriver = $user->role === 'driver' && $waybill->driver_id === ($user->driverProfile->id ?? 0); // Водитель из листа

        if ($user->role !== 'admin' && !$isOwner && !$isDriver) {
            abort(403, 'У вас нет доступа к этому документу.');
        }
    }

    // 1. Список путевых листов с учетом ролей
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            $waybills = Waybill::with(['driver', 'vehicle', 'user'])->latest()->get();
        } elseif ($user->role === 'driver') {
            $driverId = $user->driverProfile->id ?? 0;
            $waybills = Waybill::with(['driver', 'vehicle'])->where('driver_id', $driverId)->latest()->get();
        } else {
            // Оператор (диспетчер)
            $waybills = Waybill::with(['driver', 'vehicle'])->where('user_id', $user->id)->latest()->get();
        }

        return view('waybills.index', compact('waybills'));
    }

    // 2. Форма выдачи нового листа (Этап 1)
    public function create(): View
    {
        // Водителям нельзя выписывать листы
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'driver') {
            abort(403, 'Водители не могут выписывать путевые листы.');
        }

        $drivers = Driver::where('is_active', true)->get();
        $vehicles = Vehicle::all();

        return view('waybills.create', compact('drivers', 'vehicles'));
    }

    // 3. Сохранение выписанного листа
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_km' => 'required|integer|min:0',
            'fuel_start' => 'required|numeric|min:0',
            'departure_time' => 'required|date',
            'route' => 'required|string|max:255',
            'cargo_info' => 'nullable|string|max:255',
        ]);

        Waybill::create([
            'number' => 'ПЛ-' . strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'start_km' => $request->start_km,
            'fuel_start' => $request->fuel_start,
            'departure_time' => $request->departure_time,
            'route' => $request->route,
            'cargo_info' => $request->cargo_info,
            'status' => 'issued',
        ]);

        return redirect()->route('waybills.index')->with('success', 'Путевой лист выписан и отправлен водителю!');
    }

    // 4. Форма закрытия листа после рейса (Этап 2)
    public function edit(Waybill $waybill): View
    {
        /** @var User $user */
        $user = Auth::user();
        $this->checkAccess($user, $waybill);

        return view('waybills.edit', compact('waybill'));
    }

    // 5. Расчет ГСМ и закрытие документа
    public function update(Request $request, Waybill $waybill): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->checkAccess($user, $waybill);

        $request->validate([
            'end_km' => 'required|integer|gte:' . $waybill->start_km,
            'fuel_added' => 'nullable|numeric|min:0', // Сколько залили в пути
            'fuel_end' => 'required|numeric|min:0',   // Сколько осталось в баке
            'return_time' => 'required|date|after_or_equal:' . $waybill->departure_time,
            'mechanic_name' => 'required|string|max:255',
            'medic_name' => 'required|string|max:255',
        ]);

        $fuelAdded = $request->fuel_added ?? 0; // Если ничего не ввели, считаем как 0

        // ФАКТИЧЕСКИЙ РАСХОД = (Топливо старт + Заправка) - Топливо финиш
        $fuelConsumedActual = ($waybill->fuel_start + $fuelAdded) - $request->fuel_end;

        $waybill->update([
            'end_km' => $request->end_km,
            'fuel_added' => $fuelAdded,
            'fuel_end' => $request->fuel_end,
            'fuel_consumed' => $fuelConsumedActual,
            'return_time' => $request->return_time,
            'mechanic_name' => $request->mechanic_name,
            'medic_name' => $request->medic_name,
            'status' => 'closed',
        ]);

        return redirect()->route('waybills.index')->with('success', 'Рейс завершен. Фактический расход ГСМ: ' . $fuelConsumedActual . ' л.');
    }

    // 6. Генерация PDF
    public function downloadPdf(Waybill $waybill)
    {
        /** @var User $user */
        $user = Auth::user();
        $this->checkAccess($user, $waybill);

        $waybill->load(['driver', 'vehicle', 'user']);
        $pdf = Pdf::loadView('waybills.pdf', compact('waybill'));

        return $pdf->download('Путевой_лист_' . $waybill->number . '.pdf');
    }
}
