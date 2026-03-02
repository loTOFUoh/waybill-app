<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Путевой лист</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 14px; color: #333; }
        h1 { text-align: center; font-size: 18px; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .info-table th, .info-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .info-table th { background-color: #f0f0f0; width: 40%; }
        .footer { margin-top: 40px; font-style: italic; font-size: 12px; text-align: right; }
    </style>
</head>
<body>
<h1>Путевой лист № {{ $waybill->number }}</h1>
<p><strong>Дата формирования:</strong> {{ $waybill->created_at->format('d.m.Y H:i') }}</p>

<table class="info-table">
    <tr>
        <th>Водитель</th>
        <td>{{ $waybill->driver->full_name }}</td>
    </tr>
    <tr>
        <th>Транспортное средство</th>
        <td>{{ $waybill->vehicle->model }} (Гос. номер: {{ $waybill->vehicle->plate_number }})</td>
    </tr>
    <tr>
        <th>Диспетчер (отв. лицо)</th>
        <td>{{ $waybill->user->name }}</td>
    </tr>
</table>

<h2 style="font-size: 16px; margin-top: 30px;">Данные о работе и расходе ГСМ:</h2>
<table class="info-table">
    <tr>
        <th>Показания одометра (выезд)</th>
        <td>{{ $waybill->start_km }} км</td>
    </tr>
    <tr>
        <th>Показания одометра (возврат)</th>
        <td>{{ $waybill->end_km }} км</td>
    </tr>
    <tr>
        <th>Пройдено за рейс</th>
        <td><strong>{{ $waybill->end_km - $waybill->start_km }} км</strong></td>
    </tr>
    <tr>
        <th>Остаток топлива (выезд)</th>
        <td>{{ $waybill->fuel_start }} л</td>
    </tr>
    <tr>
        <th>Расход топлива (по норме)</th>
        <td><strong>{{ $waybill->fuel_consumed }} л</strong></td>
    </tr>
    <tr>
        <th>Остаток топлива (возврат)</th>
        <td>{{ $waybill->fuel_end }} л</td>
    </tr>
</table>

<div class="footer">
    Документ сгенерирован автоматически подсистемой CargoWaybill.<br>
    DevOps Pipeline Status: Active
</div>
</body>
</html>
