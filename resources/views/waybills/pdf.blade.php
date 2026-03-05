<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Путевой лист {{ $waybill->number }}</title>
    <style>
        /* Обязательно используем DejaVu Sans для поддержки кириллицы в DOMPDF */
        body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; line-height: 1.3; color: #000; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 15px; text-transform: uppercase; }
        .section-title { font-weight: bold; background-color: #e5e7eb; padding: 5px; border: 1px solid #000; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td, th { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background-color: #f9fafb; text-align: left; }
        .no-border td { border: none; padding: 4px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .sign-box { margin-top: 20px; border-top: 1px solid #000; padding-top: 5px; text-align: center; font-size: 9px; color: #555; }
    </style>
</head>
<body>

<h1>Путевой лист № {{ $waybill->number }}</h1>
<p class="text-center">Статус: <strong>{{ $waybill->status === 'closed' ? 'Закрыт и рассчитан' : 'В рейсе' }}</strong></p>

<div class="section-title">1. Транспортное средство и Водитель</div>
<table>
    <tr>
        <td width="50%">
            <strong>Марка автомобиля:</strong> {{ $waybill->vehicle->model }}<br>
            <strong>Государственный номер:</strong> {{ $waybill->vehicle->plate_number }}<br>
            <strong>Тип топлива:</strong> {{ $waybill->vehicle->fuel_type }}
        </td>
        <td width="50%">
            <strong>Водитель:</strong> {{ $waybill->driver->full_name }}<br>
            <strong>Удостоверение:</strong> {{ $waybill->driver->license_number }}<br>
            <strong>Диспетчер (выписал):</strong> {{ $waybill->user->name }}
        </td>
    </tr>
</table>

<div class="section-title">2. Задание водителю</div>
<table>
    <tr>
        <td width="70%"><strong>Маршрут следования:</strong> {{ $waybill->route }}</td>
        <td width="30%"><strong>Груз:</strong> {{ $waybill->cargo_info ?: 'Без груза' }}</td>
    </tr>
</table>

<div class="section-title">3. Работа автомобиля и расход ГСМ</div>
<table>
    <tr>
        <th width="25%">Показатель</th>
        <th width="25%">При выезде</th>
        <th width="25%">При возвращении</th>
        <th width="25%">Итого за рейс</th>
    </tr>
    <tr>
        <td><strong>Дата и время</strong></td>
        <td>{{ $waybill->departure_time ? $waybill->departure_time->format('d.m.Y H:i') : '-' }}</td>
        <td>{{ $waybill->return_time ? $waybill->return_time->format('d.m.Y H:i') : '-' }}</td>
        <td>
            @if($waybill->departure_time && $waybill->return_time)
                {{ $waybill->departure_time->diffInHours($waybill->return_time) }} ч.
            @endif
        </td>
    </tr>
    <tr>
        <td><strong>Показания одометра</strong></td>
        <td>{{ $waybill->start_km }} км</td>
        <td>{{ $waybill->end_km ?? '-' }} км</td>
        <td><strong>{{ $waybill->end_km ? ($waybill->end_km - $waybill->start_km) : 0 }} км</strong></td>
    </tr>
    <tr>
        <td><strong>Движение топлива</strong></td>
        <td>{{ $waybill->fuel_start }} л.</td>
        <td>
            Остаток: {{ $waybill->fuel_end ?? '-' }} л.<br>
            Заправка: {{ $waybill->fuel_added ?? '0' }} л.
        </td>
        <td>
            Фактический расход:<br>
            <strong>{{ $waybill->fuel_consumed ?? '-' }} л.</strong>
        </td>
    </tr>
</table>

<div class="section-title">4. Расчет нормы (Справочно)</div>
<table>
    <tr>
        <td>Базовая норма ТС: <strong>{{ $waybill->vehicle->base_consumption }} л / 100 км</strong></td>
        <td>
            Нормативный расход за рейс:
            <strong>
                @if($waybill->end_km)
                    {{ number_format((($waybill->end_km - $waybill->start_km) * $waybill->vehicle->base_consumption) / 100, 2) }} л.
                @else
                    -
                @endif
            </strong>
        </td>
    </tr>
</table>

<div class="section-title">5. Отметки и подписи</div>
<table class="no-border" style="margin-top: 15px;">
    <tr>
        <td width="33%" class="text-center">
            Выезд разрешаю. Технически исправен.<br><br><br>
            _______________________<br>
            Механик: {{ $waybill->mechanic_name ?? '_______________' }}
        </td>
        <td width="33%" class="text-center">
            Медосмотр прошел. К рейсу допущен.<br><br><br>
            _______________________<br>
            Медик: {{ $waybill->medic_name ?? '_______________' }}
        </td>
        <td width="33%" class="text-center">
            Автомобиль сдал. Путевой лист закрыт.<br><br><br>
            _______________________<br>
            Водитель: {{ $waybill->driver->full_name }}
        </td>
    </tr>
</table>

</body>
</html>
