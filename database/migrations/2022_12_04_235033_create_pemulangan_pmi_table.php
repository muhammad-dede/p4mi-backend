<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemulanganPmiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemulangan_pmi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemulangan')->nullable()->index();
            $table->unsignedBigInteger('id_pmi')->nullable()->index();

            $table->foreign('id_pemulangan')->references('id')->on('pemulangan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_pmi')->references('id')->on('pmi')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemulangan_pmi');
    }
}
