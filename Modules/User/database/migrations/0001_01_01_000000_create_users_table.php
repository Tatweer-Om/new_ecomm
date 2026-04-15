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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact')->nullable();
            $table->string('email')->unique();
            $table->tinyInt('dashboard')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('user')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('booking')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('report')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('customer')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('expense')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('dress')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('laundry')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('setting')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('add_dress')->default(1)->comment('1: access ,2 :not access');
            $table->tinyInt('delete_booking')->default(1)->comment('1: access ,2 :not access');
            $table->unsignedBigInteger('branch_id')->nullable()->comment('Reference to branches table');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('added_by', 100)->nullable();
            $table->date('add_date')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->date('update_date')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
