<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBayar extends Model
{
    use HasFactory;

    // protected $table = "mbc_mahasiswa";
    // protected $fillable = 

    // ['id','str_nim','str_nm_mhs','str_jurusan','str_angkatan','str_alamat','str_hp_mhs','str_imel_mhs','str_campus','bol_pjj','bol_karyawan','bol_jimbaran','bol_lulus','str_kd_kelas','str_nm_prodi','bol_sts_kelas'];


    public $timestamps = false;
    protected $table = "tb_itembayar";
     protected $fillable = 
    ['nama_item','id_kategori','id_kat_periode','keterangan','def_value','str_angkatan'];
   // protected $primaryKey='str_nim';



}
