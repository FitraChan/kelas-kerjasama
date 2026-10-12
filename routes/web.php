<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\StatusMahasiswaController;

use App\Http\Controllers\AdminAssetController;
use App\Http\Controllers\TahunAjaranController;


// =======================
// 🔐 ROUTE LOGIN & AUTH
// =======================
Route::get('/login', 'App\Http\Controllers\LoginSistem@index')->name('login'); // ✅ tambahkan name('login')
Route::post('/proses_login', 'App\Http\Controllers\LoginSistem@proses_login')->name('proses_login');
Route::get('/logout', 'App\Http\Controllers\LoginSistem@logout')->name('logout');
Route::post('/registrasi', 'App\Http\Controllers\LoginSistem@registrasi')->name('registrasi');

Route::get('/daftar', function () {
    return view('livewire.daftar');
})->name('daftar');


Route::get('admin/asset/{filename}', [AdminAssetController::class, 'show'])
     ->middleware('auth', 'admin') 
     ->name('admin.asset.show');


// =======================
// 🔒 ROUTE YANG PERLU LOGIN
// =======================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', 'App\Http\Controllers\Post@index')->name('home');

    // Post
    Route::resource('post', App\Http\Controllers\Post::class);
    Route::post('post_tab', 'App\Http\Controllers\Post@post_tab')->name('post_tab');

    // Keuangan
    Route::resource('keuangan', App\Http\Controllers\Keuangan::class);
    Route::get('keuangan', 'App\Http\Controllers\Keuangan@index')->name('keuangan');
    Route::get('create_datatable', 'App\Http\Controllers\Keuangan@create_datatable')->name('create_datatable');
    Route::get('create_pdf/{nim}', 'App\Http\Controllers\KeuanganMbc@create_pdf')->name('create_pdf');
    Route::get('create_excel', 'App\Http\Controllers\Keuangan@create_excel')->name('create_excel');

    // Mahasiswa
    Route::get('mahasiswa', 'App\Http\Controllers\Mahasiswa@index')->name('mahasiswa');
    Route::get('mahasiswa/setpw/{nim}', 'App\Http\Controllers\Mahasiswa@setpw')->name('mahasiswa.setpw');
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('data_mhs', 'App\Http\Controllers\Mahasiswa@data_mhs')->name('data_mhs');
    Route::get('data_alumni', 'App\Http\Controllers\Mahasiswa@data_alumni')->name('data_alumni');
    Route::get('alumni', 'App\Http\Controllers\Mahasiswa@alumni')->name('alumni');
    Route::get('hisstatus/{nim}', 'App\Http\Controllers\Mahasiswa@his_status')->name('hisstatus');

    // Nilai
    Route::get('nilai/{nim}', 'App\Http\Controllers\Nilai@index')->name('nilai');

    // Keuangan Mobile
    Route::resource('keuangan_mobile', App\Http\Controllers\KeuanganMobile::class);
    Route::get('create_datatable_mobile', 'App\Http\Controllers\KeuanganMobile@create_datatable_mobile')->name('create_datatable_mobile');
    Route::post('tunggakan_mobile/{id}', 'App\Http\Controllers\KeuanganMobile@tunggakan_mobile')->name('tunggakan_mobile');
    Route::post('detk_mobile', 'App\Http\Controllers\KeuanganMobile@detk_mobile')->name('detk_mobile');
    Route::post('dett_mobile', 'App\Http\Controllers\KeuanganMobile@dett_mobile')->name('dett_mobile');
    Route::post('profil/{id}', 'App\Http\Controllers\KeuanganMobile@profil')->name('profil');

    // Keuangan MBC
    Route::resource('keuangan_mbc', App\Http\Controllers\KeuanganMbc::class);
    Route::post('simpan', 'App\Http\Controllers\KeuanganMbc@simpan')->name('simpan');

    Route::post('simpan_edit', 'App\Http\Controllers\KeuanganMbc@simpan_edit')->name('simpan_edit');


    Route::post('init', 'App\Http\Controllers\KeuanganMbc@settagihan')->name('init');
    Route::post('tagihan_khusus', 'App\Http\Controllers\KeuanganMbc@tagihan_khusus')->name('tagihan_khusus');
    Route::get('create_datatable_mbc', 'App\Http\Controllers\KeuanganMbc@create_datatable_mbc')->name('create_datatable_mbc');
    Route::get('semester/{id}', 'App\Http\Controllers\KeuanganMbc@semester')->name('semester');
    Route::get('detail_mbc/{id}', 'App\Http\Controllers\KeuanganMbc@detail_mbc')->name('detail_mbc');
    Route::get('detail_pembayaran/{id}', 'App\Http\Controllers\KeuanganMbc@detail_pembayaran')->name('detail_pembayaran');
    Route::get('all_transaksi/{id}', 'App\Http\Controllers\KeuanganMbc@all_transaksi')->name('all_transaksi');
    Route::get('all_transaksi_table/{id}', 'App\Http\Controllers\KeuanganMbc@all_transaksi_table')->name('all_transaksi_table');
    Route::get('detail_transaksi/{id}', 'App\Http\Controllers\KeuanganMbc@detail_transaksi')->name('detail_transaksi');
    Route::get('detail_transaksi_table/{id}', 'App\Http\Controllers\KeuanganMbc@detail_transaksi_table')->name('detail_transaksi_table');
    Route::get('tunggakanMbc/{id}', 'App\Http\Controllers\KeuanganMbc@tunggakanMbc')->name('tunggakanMbc');
    Route::post('tambahDetail', 'App\Http\Controllers\KeuanganMbc@tambahDetail')->name('tambahDetail');
    Route::post('hapusDetail', 'App\Http\Controllers\KeuanganMbc@hapusDetail')->name('hapusDetail');
    Route::post('hapusSemester', 'App\Http\Controllers\KeuanganMbc@hapusSemester')->name('hapusSemester');
    Route::post('simpanProsesBayar', 'App\Http\Controllers\KeuanganMbc@simpanProsesBayar')->name('simpanProsesBayar');
    Route::get('coba', 'App\Http\Controllers\KeuanganMbc@coba')->name('coba');
    //Route::get('kwitansi_pdf/{id}', 'App\Http\Controllers\Keuangan@kwitansi_pdf')->name('kwitansi_pdf');
    Route::get('histori_transaksi_pdf/{id}', 'App\Http\Controllers\KeuanganMbc@histori_transaksi_pdf')->name('histori_transaksi_pdf');
    Route::get('reloadCard/{idBayar}', 'App\Http\Controllers\KeuanganMbc@reloadCard')->name('reloadCard');
    Route::get('reloadCardHapus/{idBayar}', 'App\Http\Controllers\KeuanganMbc@reloadCardHapus')->name('reloadCardHapus');
    Route::post('cektrans/{id}', 'App\Http\Controllers\Keuangan@cektrans')->name('cektrans');

    // Master Keuangan MBC
    Route::resource('master_keuangan_mbc', App\Http\Controllers\MasterKeuanganMbc::class);
    Route::get('create_datatable_master_mbc', 'App\Http\Controllers\MasterKeuanganMbc@create_datatable_master_mbc')->name('create_datatable_master_mbc');
    Route::get('showPembayaran/{id}', 'App\Http\Controllers\MasterKeuanganMbc@showPembayaran')->name('showPembayaran');
    Route::post('simpanKategori', 'App\Http\Controllers\MasterKeuanganMbc@simpanKategori')->name('simpanKategori');
    Route::get('hisBayar/{id}', 'App\Http\Controllers\MasterKeuanganMbc@hisBayar')->name('hisBayar');
    Route::get('getMahasiswa/{id}', 'App\Http\Controllers\MasterKeuanganMbc@getMahasiswa')->name('getMahasiswa');
    Route::get('detailBayar/{id}', 'App\Http\Controllers\MasterKeuanganMbc@detailBayar')->name('detailBayar');
    Route::get('getDetailBayar/{id}', 'App\Http\Controllers\MasterKeuanganMbc@getDetailBayar')->name('getDetailBayar');
    Route::post('updateDetailPembayaran', 'App\Http\Controllers\MasterKeuanganMbc@updateDetailPembayaran')->name('updateDetailPembayaran');
    Route::post('hapusTransaksi', 'App\Http\Controllers\MasterKeuanganMbc@hapusTransaksi')->name('hapusTransaksi');

    // Item Bayar
    Route::resource('itemBayar', App\Http\Controllers\ItemBayarController::class);
    Route::get('sisa_tagihan_nim/{id}', 'App\Http\Controllers\KeuanganMbc@sisa_tagihan_nim')->name('sisa_tagihan_nim');
    Route::get('totalBayarAll', 'App\Http\Controllers\KeuanganMbc@totalBayarAll')->name('totalBayarAll');
    Route::post('tagihan_periode_pdf', 'App\Http\Controllers\KeuanganMbc@tagihan_periode_pdf')->name('tagihan_periode_pdf');

    Route::post('importCsv', 'App\Http\Controllers\KeuanganMbc@importCsv')->name('importCsv');

    Route::post('create_pdf_admin', 'App\Http\Controllers\KeuanganMbc@create_pdf_admin')->name('create_pdf_admin');

    Route::resource('status-mahasiswa', StatusMahasiswaController::class);
    Route::post('/status-mahasiswa/update-mass', [StatusMahasiswaController::class, 'updateMass'])
    ->name('status-mahasiswa.update-mass');

    Route::post('updateLn', 'App\Http\Controllers\MahasiswaController@updateLn')->name('updateLn');
    Route::post('updateBolLulus', 'App\Http\Controllers\MahasiswaController@updateBolLulus')->name('updateBolLulus');
    Route::get('getTagihan/{id}', 'App\Http\Controllers\KeuanganMbc@getTagihan')->name('getTagihan');
    
    Route::get('getTagihanKhusus/{id}', 'App\Http\Controllers\KeuanganMbc@getTagihanKhusus')->name('getTagihanKhusus');

    
    Route::post('hapusGambar', 'App\Http\Controllers\KeuanganMbc@hapusGambar')->name('hapusGambar');
    Route::get('autocomplete', 'App\Http\Controllers\MahasiswaController@autocomplete')->name('autocomplete');
    
    Route::get('create_pdf_kuliah_kerja/{id}', 'App\Http\Controllers\KeuanganMbc@create_pdf_kuliah_kerja')->name('create_pdf_kuliah_kerja');
    Route::resource('ipaymu', App\Http\Controllers\IpaymuOrderController::class);
    Route::get('export_pembayaran/{id}', 'App\Http\Controllers\KeuanganMbc@export_pembayaran')->name('export_pembayaran');
    Route::post('simpanDokumen', 'App\Http\Controllers\KeuanganMbc@simpanDokumen')->name('simpanDokumen');
    
    Route::get('download/{id}', 'App\Http\Controllers\KeuanganMbc@download')->name('download');
    Route::delete('hapusDokumen/{id}', 'App\Http\Controllers\KeuanganMbc@hapusDokumen')->name('hapusDokumen');


    Route::get('kwitansi/{id}', 'App\Http\Controllers\KeuanganMbc@kwitansi')->name('kwitansi');














    // Tambahan
    Route::get('/detailBayarKosong', function () {
        return response()->json(['data' => []]);
    })->name('detailBayarKosong');
    Route::resource('thn_ajaran', TahunAjaranController::class);

});
