<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Расширяем ENUM, добавляя статус 'issued'
        DB::statement("ALTER TABLE waybills MODIFY COLUMN status ENUM('draft', 'issued', 'closed') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Откат к старому состоянию (опционально)
        DB::statement("ALTER TABLE waybills MODIFY COLUMN status ENUM('draft', 'closed') NOT NULL DEFAULT 'draft'");
    }
};
