<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('waybills', function (Blueprint $table) {
            $table->dateTime('departure_time')->nullable()->after('vehicle_id')->comment('Время выезда по графику');
            $table->dateTime('return_time')->nullable()->after('departure_time')->comment('Время возвращения');
            $table->string('route')->nullable()->after('return_time')->comment('Маршрут движения');
            $table->string('cargo_info')->nullable()->after('route')->comment('Наименование груза');
            $table->string('mechanic_name')->nullable()->after('fuel_end')->comment('ФИО механика');
            $table->string('medic_name')->nullable()->after('mechanic_name')->comment('ФИО медика');

            $table->integer('end_km')->nullable()->change();
            $table->decimal('fuel_end', 8, 2)->nullable()->change();
            $table->decimal('fuel_consumed', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('waybills', function (Blueprint $table) {
            $table->dropColumn(['departure_time', 'return_time', 'route', 'cargo_info', 'mechanic_name', 'medic_name']);
        });
    }
};
