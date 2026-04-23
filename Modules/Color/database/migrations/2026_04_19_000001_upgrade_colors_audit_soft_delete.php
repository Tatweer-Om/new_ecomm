<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            if (! Schema::hasColumn('colors', 'tenant_id')) {
                // Stancl tenant primary keys are string UUIDs, not integers
                $table->string('tenant_id', 36)->nullable()->after('id')->index();
            }
            if (! Schema::hasColumn('colors', 'delete_status')) {
                $table->boolean('delete_status')->default(false)->after('color_code');
            }
            if (! Schema::hasColumn('colors', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('delete_status');
            }
        });

        if (Schema::hasColumn('colors', 'updated_by')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->dropColumn('updated_by');
            });
        }

        Schema::table('colors', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        $legacy = array_values(array_filter(
            ['user_id', 'added_by', 'add_date', 'update_date'],
            fn (string $col) => Schema::hasColumn('colors', $col)
        ));

        if ($legacy !== []) {
            Schema::table('colors', function (Blueprint $table) use ($legacy) {
                $table->dropColumn($legacy);
            });
        }
    }

    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            if (Schema::hasColumn('colors', 'updated_by')) {
                $table->dropColumn('updated_by');
            }
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->string('updated_by', 100)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->date('add_date')->nullable();
            $table->date('update_date')->nullable();
        });

        $drops = array_values(array_filter(
            ['tenant_id', 'delete_status', 'created_by'],
            fn (string $col) => Schema::hasColumn('colors', $col)
        ));

        if ($drops !== []) {
            Schema::table('colors', function (Blueprint $table) use ($drops) {
                $table->dropColumn($drops);
            });
        }
    }
};
