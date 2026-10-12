<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminAssetController extends Controller
{
    public function show(string $filename)
    {
        // Pastikan path yang diambil berada di dalam direktori private
        $path = 'private/bukti-transaksi/' . $filename; 
    // ^ Path yang digunakan di sini adalah path INTERNAL Laravel storage

    if (!Storage::disk('local')->exists($path)) {
        abort(404, 'File Tidak Ditemukan');
    }

    // Mengambil file hanya setelah lolos cek middleware('auth', 'admin')
    $file = Storage::disk('local')->get($path);
    $type = Storage::disk('local')->mimeType($path);

    // Mengembalikan file ke browser
    return response($file, 200)
        ->header('Content-Type', $type);
    }
}
