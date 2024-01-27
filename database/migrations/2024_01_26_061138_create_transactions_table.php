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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->noActionOnDelete();
            $table->foreignId('dokter_id')->nullable()->constrained('users')->noActionOnDelete();

            $table->date('booking_date');
            $table->string('day', 100);
            $table->string('time', 50);
            $table->string('code');
            $table->string('status', 100)->comment('PENDING | CANCEL | PAID');

            $table->longText('medical_record')->nullable();
            $table->integer('total_price')->default(0);
            $table->integer('total_point_exchanged')->default(0);
            $table->integer('total_point_earned')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
