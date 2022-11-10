<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pmi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_paspor')->nullable();
            $table->string('nama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->unsignedInteger('id_provinsi')->nullable()->index();
            $table->unsignedInteger('id_kota')->nullable()->index();
            $table->string('no_telp')->nullable();
            $table->string('negara_tempat_bekerja')->nullable();
            $table->year('tahun_bekerja')->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->unsignedInteger('id_status_kedatangan')->nullable()->index();
            $table->text('masalah')->nullable();
            $table->text('tuntutan')->nullable();
            $table->string('foto_pmi')->nullable();
            $table->string('foto_paspor')->nullable();
            $table->uuid('id_user')->nullable()->index();
            $table->timestamps();

            $table->foreign('id_provinsi')->references('id')->on('provinsi')->onDelete('set null')->onUpdate('set null');
            $table->foreign('id_kota')->references('id')->on('kota')->onDelete('set null')->onUpdate('set null');
            $table->foreign('id_status_kedatangan')->references('id')->on('status_kedatangan')->onDelete('set null')->onUpdate('set null');
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
        Schema::dropIfExists('pmi');
    }
}
