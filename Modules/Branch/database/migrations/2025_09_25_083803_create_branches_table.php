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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_name')->nullable(); 
            $table->string('contact')->nullable(); 
            $table->string('email')->nullable(); 
            $table->Longtext('address')->nullable(); // e.g., #F8B4C8
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
        Schema::dropIfExists('branches');
    }
};
