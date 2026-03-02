<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\FuelCalculatorService;
use InvalidArgumentException;

class FuelCalculatorTest extends TestCase
{
    public function test_calculates_fuel_consumption_correctly(): void
    {
        $calculator = new FuelCalculatorService();

        // Сценарий 1: Пробег 100 км, норма 29.5 (КАМАЗ), лето (коэф. 1.0)
        $result = $calculator->calculate(1000, 1100, 29.5, 1.0);
        $this->assertEquals(29.5, $result);

        // Сценарий 2: Пробег 200 км, норма 10.0, зима (коэф. 1.1)
        $resultWinter = $calculator->calculate(5000, 5200, 10.0, 1.1);
        $this->assertEquals(22.0, $resultWinter); // (200 * 10 / 100) * 1.1 = 22
    }

    public function test_throws_exception_on_invalid_mileage(): void
    {
        $calculator = new FuelCalculatorService();

        // Ожидаем, что сервис выбросит ошибку, если конечный пробег меньше начального
        $this->expectException(InvalidArgumentException::class);
        $calculator->calculate(5000, 4900, 10.0);
    }
}
