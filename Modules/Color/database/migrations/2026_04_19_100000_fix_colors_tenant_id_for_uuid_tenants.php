<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stancl stores tenant id as a string UUID; bigint column truncates and triggers SQLSTATE 1265.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('colors', 'tenant_id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `colors` MODIFY `tenant_id` VARCHAR(36) NULL');
        } elseif ($driver === 'mariadb') {
            DB::statement('ALTER TABLE `colors` MODIFY `tenant_id` VARCHAR(36) NULL');
        }
        // SQLite: tenant DBs are usually MySQL; skip if not applicable
    }

    public function down(): void
    {
        if (! Schema::hasColumn('colors', 'tenant_id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE `colors` MODIFY `tenant_id` BIGINT UNSIGNED NULL');
        }
    }
};
