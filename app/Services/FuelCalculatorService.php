<?php

namespace App\Services;

use InvalidArgumentException;

class FuelCalculatorService
{
    /**
     * Расчет расхода ГСМ по формуле
     * @param int $startKm Показания одометра при выезде
     * @param int $endKm Показания одометра при возвращении
     * @param float $baseConsumption Базовая норма расхода на 100 км
     * @param float $coefficient Поправочный коэффициент (например, 1.1 для зимы)
     * @return float
     */
    public function calculate(int $startKm, int $endKm, float $baseConsumption, float $coefficient = 1.0): float
    {
        if ($endKm < $startKm) {
            throw new InvalidArgumentException('Конечный пробег не может быть меньше начального.');
        }

        $distance = $endKm - $startKm;

        // Формула: (Пробег * Норма / 100) * Коэффициент
        $result = ($distance * $baseConsumption / 100) * $coefficient;

        return round($result, 2); // Округляем до 2 знаков после запятой
    }
}
