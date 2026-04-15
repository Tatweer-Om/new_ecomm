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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('color_name')->nullable();
            $table->string('color_name_ar')->nullable();
            $table->string('color_code', 10)->nullable(); // e.g., #F8B4C8
            $table->integer('user_id')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->date('add_date')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->date('update_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
