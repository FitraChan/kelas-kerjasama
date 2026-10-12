<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class MahasiswaMbcModel extends Model
{
    use HasFactory,HasApiTokens,SoftDeletes;
    

    protected $table = 'tb_mbc_mahasiswa';
    protected $dates = ['deleted_at']; // penting

    protected $fillable = [
        'str_nim',
        'str_nm_mhs',
        'str_jurusan',
        'str_angkatan',
        'str_alamat',
        'str_hp_mhs',
        'str_imel_mhs',
        'str_campus',
        'bol_pjj',
        'bol_karyawan',
        'bol_jimbaran',
        'bol_lulus',
        'str_kd_kelas',
        'str_nm_prodi',
        'bol_sts_kelas',
        'id_kuliah_kerja',
    ];

    // accessor untuk status aktif
    public function getStatusKelasAttribute()
    {
        return $this->bol_sts_kelas == 1 ? 'Aktif' : 'Tidak Aktif';
    }
    
     public function pendaftaran()
    {
        return $this->belongsTo(
            PendaftaranModel::class,
            'id_kuliah_kerja', // FK di tb_mbc_mahasiswa
            'id'               // PK di pendaftarans
        );
    }
}
