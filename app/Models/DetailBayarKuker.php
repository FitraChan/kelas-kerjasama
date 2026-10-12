<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailBayarKuker extends Model
{
    //
    use SoftDeletes;

    protected $table = 'detail_pembayarans';

    protected $fillable = [
        'user_id',
        'master_bayar_id',
        'nama_bayar',
        'qty',
        'no_rek',
        'nominal',
        'dibayarkan',
        'nama_bank',
        'atas_nama',
        'image',
        'jenis_bayar',
        'reference_id',
        'session_id',
        'payment_url',
        'keterangan',
        'status',
        'status_verif',
        'ipaymu_response',
        'cicilan',
    ];

    /**
     * Get the masterBayar that owns the DetailPembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masterBayar()
    {
        return $this->belongsTo(MasterBayarKuker::class, 'master_bayar_id', 'id');
    }

    /**
     * Get the pendaftar that owns the DetailPembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pendaftar()
    {
        return $this->belongsTo(PendaftaranModel::class, 'user_id', 'id');
    }
}
