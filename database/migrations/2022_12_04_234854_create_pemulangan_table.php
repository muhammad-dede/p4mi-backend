<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemulanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemulangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_status_pemulangan')->nullable();
            $table->string('nomor')->nullable()->unique();
            $table->unsignedBigInteger('id_penyedia_jasa')->nullable();
            $table->unsignedBigInteger('id_jenis_pengangkutan')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->double('durasi')->default(1)->nullable();
            $table->string('photo_pemulangan')->nullable();
            $table->string('photo_invoice')->nullable();
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->timestamps();

            $table->foreign('id_status_pemulangan')->references('id')->on('status_pemulangan')->onDelete('set null')->onUpdate('set null');
            $table->foreign('id_penyedia_jasa')->references('id')->on('penyedia_jasa')->onDelete('set null')->onUpdate('set null');
            $table->foreign('id_jenis_pengangkutan')->references('id')->on('jenis_pengangkutan')->onDelete('set null')->onUpdate('set null');
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
        Schema::dropIfExists('pemulangan');
    }
}
