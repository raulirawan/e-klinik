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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->foreign(['medicine_id'])->references(['id'])->on('medicines')->onDelete('NO ACTION');
            $table->foreign(['transaction_id'])->references(['id'])->on('transactions')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign('transaction_details_medicine_id_foreign');
            $table->dropForeign('transaction_details_transaction_id_foreign');
        });
    }
};
