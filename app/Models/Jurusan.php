<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'tb_jurusan';
    protected $fillable = [
        'nama_jurusan',
        'singkatan',
        'jumlah_siswa',
        'id_sekolah',
        'id_jenjang',
        'logo',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaMbcModel::class, 'str_jurusan', 'id');
    }
}
