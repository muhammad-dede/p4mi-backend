<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('po')->nullable()->unique();
            $table->date('tgl_antar')->nullable();
            $table->time('waktu_antar')->nullable();
            $table->string('foto_invoice')->nullable();
            $table->uuid('id_user')->nullable()->index();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('makan');
    }
}
