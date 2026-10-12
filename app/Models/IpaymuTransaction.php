<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class IpaymuTransaction extends Model
{
    //
    use HasFactory;

    protected $table = "tb_ipaymu_transaction";
    protected $fillable = 
    [  
        'id_bayar',
        'ipaymu_ref',
        'session_id',
        'amount',
        'fee',
        'status',
        'payment_method',
        'payment_channel',
        'payment_url',
        'ipaymu_response',
        'keterangan',
        'paid_at',
    ];


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
}
