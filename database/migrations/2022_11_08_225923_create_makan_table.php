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
            $table->id();
            $table->string('no_po')->nullable()->unique();
            $table->unsignedBigInteger('id_penyedia_jasa')->nullable();
            $table->unsignedBigInteger('id_jenis_barang')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->enum('waktu', ['P', 'S', 'M'])->nullable();
            $table->double('durasi')->default(1)->nullable();
            $table->string('photo_makan')->nullable();
            $table->string('photo_invoice')->nullable();
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->timestamps();

            $table->foreign('id_penyedia_jasa')->references('id')->on('penyedia_jasa')->onDelete('set null')->onUpdate('set null');
            $table->foreign('id_jenis_barang')->references('id')->on('jenis_barang')->onDelete('set null')->onUpdate('set null');
            $table->foreign('id_user')->references('id')->on('user')->onDelete('set null')->onUpdate('set null');
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
