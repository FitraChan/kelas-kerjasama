<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MahasiswaQueryService
{
    public function baseQuery(Request $request, $tahun)
    {
        return DB::table('tb_mbc_mahasiswa as a')
            ->leftJoin('tb_status_mahasiswa as f', 'f.nim', '=', 'a.str_nim')
            ->where('f.id_thn_ajaran', $tahun)
            ->select(
                'a.str_nim',
                'a.str_nm_mhs',
                'a.bol_lulus',
                'f.status',
                'a.is_luarnegeri'
            )
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('a.str_nm_mhs', 'like', "%{$request->search}%")
                        ->orWhere('a.str_nim', 'like', "%{$request->search}%");
                });
            })
            ->when($request->status_mahasiswa, function ($q) use ($request) {
                if ($request->status_mahasiswa == 'aktif') {
                    $q->where('a.bol_lulus', 0)->where('f.status', 1);
                } elseif ($request->status_mahasiswa == 'cuti') {
                    $q->where('a.bol_lulus', 0)->where('f.status', 2);
                } elseif ($request->status_mahasiswa == 'nonaktif') {
                    $q->where('a.bol_lulus', 0)->where('f.status', 3);
                } elseif ($request->status_mahasiswa == 'do') {
                    $q->where('a.bol_lulus', 3);
                } elseif ($request->status_mahasiswa == 'lulus') {
                    $q->where('a.bol_lulus', 1);
                }
            });
    }
}
