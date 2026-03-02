<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('waybills', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('user_id')->constrained(); // Кто создал (диспетчер)
            $table->foreignId('driver_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->integer('start_km');
            $table->integer('end_km')->nullable();
            $table->decimal('fuel_start', 8, 2);
            $table->decimal('fuel_end', 8, 2)->nullable();
            $table->decimal('fuel_consumed', 8, 2)->nullable(); // Рассчитанный расход
            $table->enum('status', ['draft', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waybills');
    }
};
