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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable(); 
            $table->string('name')->nullable(); 
            $table->string('contact')->nullable(); 
            $table->string('email')->nullable(); 
            $table->string('cr_number')->nullable(); 
            $table->string('logo')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable(); 
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
        Schema::dropIfExists('settings');
    }
};
