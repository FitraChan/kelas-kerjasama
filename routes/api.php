<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\LoginSistem;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KeuanganMbc;



use Illuminate\Support\Facades\DB;





Route::post('/loginApi', [LoginSistem::class, 'loginApi']);

//Route::get('/statusKerja', [StatusKerjaController::class, 'index']);        // GET semua lowongan
//Route::get('/showPekerjaCvAdmin/{id}', [PekerjaController::class, 'showPekerjaCvAdmin']); // Update pekerja
//Route::apiResource('agama', AgamaController::class);








Route::middleware('auth:sanctum')->group(function () {


  Route::post('/getMahasiswaApi', [MahasiswaController::class, 'getMahasiswaApi']);        // GET semua lowongan
    Route::post('/setSemesterNim', [MahasiswaController::class, 'setSemesterNim']);        // GET semua lowongan

  Route::post('/setSemesterMahasiswa', [KeuanganMbc::class, 'setSemesterMahasiswa']);        // GET semua lowongan




});
