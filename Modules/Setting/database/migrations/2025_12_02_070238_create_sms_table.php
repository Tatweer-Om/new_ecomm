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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->integer('sms_status')->nullable(); 
            $table->text('sms')->nullable();  // long text supported
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
        Schema::dropIfExists('sms');
    }
};
