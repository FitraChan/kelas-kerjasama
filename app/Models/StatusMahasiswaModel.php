<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class StatusMahasiswaModel extends Model
{
    use HasFactory,SoftDeletes;
   // public $timestamps = false;
    protected $table = "tb_status_mahasiswa";
    protected $dates = ['deleted_at']; // penting


     protected $fillable = [
        'id',
        'nim',
        'status',
        'keterangan',
        'id_thn_ajaran',
        
    ];

     public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaMbcModel::class, 'nim', 'str_nim');
    }
    
}
