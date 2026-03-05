<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->decimal('fuel_added', 8, 2)->default(0)->after('fuel_start')->comment('Заправлено в пути (л)');
        });
    }

    public function down(): void
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->dropColumn('fuel_added');
        });
    }
};
