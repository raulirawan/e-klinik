<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('transactions_user_id_foreign');
            $table->unsignedBigInteger('dokter_id')->nullable()->index('transactions_dokter_id_foreign');
            $table->date('booking_date');
            $table->string('day', 100);
            $table->string('time', 50);
            $table->string('code');
            $table->string('status', 100)->comment('PENDING | CANCEL | PAID');
            $table->longText('medical_record')->nullable();
            $table->integer('total_price')->default(0);
            $table->integer('total_point_exchanged')->default(0);
            $table->integer('total_point_earned')->default(0);
            $table->string('payment_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
