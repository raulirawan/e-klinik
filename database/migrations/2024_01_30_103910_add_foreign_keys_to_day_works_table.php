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
        Schema::table('day_works', function (Blueprint $table) {
            $table->foreign(['dokter_id'])->references(['id'])->on('users')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('day_works', function (Blueprint $table) {
            $table->dropForeign('day_works_dokter_id_foreign');
        });
    }
};
