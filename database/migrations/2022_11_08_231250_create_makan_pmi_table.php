<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakanPmiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makan_pmi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_makan')->nullable()->index();
            $table->uuid('id_pmi')->nullable()->index();
            $table->timestamps();

            $table->foreign('id_makan')->references('id')->on('makan')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('makan_pmi');
    }
}
