<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BayarIpaymu extends Model
{
    //
    use HasFactory;

    protected $table = "tb_ipaymu_bayar";
    protected $fillable = 
    ['id_tahun','id_bulan','id_semester','no_kwitansi','tgl_bayar','jam_bayar','id_kasir','nim','keterangan','tot_bayar','bukti_bayar','tot_kwajiban','via','sts_trans','ket_simple','sts_bayar'];


    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaMbcModel::class, 'nim', 'str_nim');
        // parameter ke-2 = foreign key di mbc_bayar
        // parameter ke-3 = primary/unique key di siswa
    }

    public function detailBayar()
    {
        return $this->hasOne(DetBayarIpaymu::class, 'id_bayar', 'id');
    }
    
    
    public function transaction()
    {
        return $this->hasOne(IpaymuTransaction::class, 'id_bayar', 'id');
    }
}
