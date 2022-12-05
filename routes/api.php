<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'P4MI';
});

Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/me', [\App\Http\Controllers\API\AuthController::class, 'me']);
    Route::post('/profil/update', [\App\Http\Controllers\API\AuthController::class, 'profilUpdate']);
    Route::post('/password/update', [\App\Http\Controllers\API\AuthController::class, 'passwordUpdate']);
    Route::delete('logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);

    Route::group(['prefix' => 'referensi'], function () {
        Route::get('/provinsi', [\App\Http\Controllers\API\ReferensiController::class, 'provinsi']);
        Route::get('/kota', [\App\Http\Controllers\API\ReferensiController::class, 'kota']);
        Route::get('/status-kedatangan', [\App\Http\Controllers\API\ReferensiController::class, 'statusKedatangan']);
        Route::get('/status-pemulangan', [\App\Http\Controllers\API\ReferensiController::class, 'statusPemulangan']);
        Route::get('/penyedia-jasa', [\App\Http\Controllers\API\ReferensiController::class, 'penyediaJasa']);
        Route::get('/jenis-barang', [\App\Http\Controllers\API\ReferensiController::class, 'jenisBarang']);
        Route::get('/jenis-pengangkutan', [\App\Http\Controllers\API\ReferensiController::class, 'jenisPengangkutan']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [\App\Http\Controllers\API\UserController::class, 'index']);
        Route::post('store', [\App\Http\Controllers\API\UserController::class, 'store']);
        Route::post('update/{id}', [\App\Http\Controllers\API\UserController::class, 'update']);
        Route::delete('destroy/{id}', [\App\Http\Controllers\API\UserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'pmi'], function () {
        Route::get('/', [\App\Http\Controllers\API\PmiController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\API\PmiController::class, 'show']);
        Route::post('store', [\App\Http\Controllers\API\PmiController::class, 'store']);
        Route::post('update/{id}', [\App\Http\Controllers\API\PmiController::class, 'update']);
        Route::delete('destroy/{id}', [\App\Http\Controllers\API\PmiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'makan'], function () {
        Route::get('/', [\App\Http\Controllers\API\MakanController::class, 'index']);
        Route::get('/detail/{id}', [\App\Http\Controllers\API\MakanController::class, 'detail']);
        Route::post('store', [\App\Http\Controllers\API\MakanController::class, 'store']);
        Route::post('update/{id}', [\App\Http\Controllers\API\MakanController::class, 'update']);
        Route::delete('destroy/{id}', [\App\Http\Controllers\API\MakanController::class, 'destroy']);
        Route::post('pmi', [\App\Http\Controllers\API\MakanController::class, 'pmi']);
        Route::post('upload/photo-makan', [\App\Http\Controllers\API\MakanController::class, 'uploadPhotoMakan']);
        Route::post('upload/photo-invoice', [\App\Http\Controllers\API\MakanController::class, 'uploadPhotoInvoice']);
    });

    Route::group(['prefix' => 'pemulangan'], function () {
        Route::get('/', [\App\Http\Controllers\API\PemulanganController::class, 'index']);
        Route::get('/detail/{id}', [\App\Http\Controllers\API\PemulanganController::class, 'detail']);
        Route::post('store', [\App\Http\Controllers\API\PemulanganController::class, 'store']);
        Route::post('update/{id}', [\App\Http\Controllers\API\PemulanganController::class, 'update']);
        Route::delete('destroy/{id}', [\App\Http\Controllers\API\PemulanganController::class, 'destroy']);
        Route::post('pmi', [\App\Http\Controllers\API\PemulanganController::class, 'pmi']);
        Route::post('upload/photo-pemulangan', [\App\Http\Controllers\API\PemulanganController::class, 'uploadPhotoPemulangan']);
        Route::post('upload/photo-invoice', [\App\Http\Controllers\API\PemulanganController::class, 'uploadPhotoInvoice']);
    });
});
