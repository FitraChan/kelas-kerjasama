<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterBayarKuker extends Model
{
    //
    use SoftDeletes;
    protected $table= 'master_bayar';

    protected $fillable = [
        'nama_pembayaran',
        'periode_id',
        'jumlah',
        'bisa_cicilan',
        'jumlah_cicilan',
        'jenis_bayar',
    ];

    /**
     * Get the periode that owns the MasterPembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
   

    /**
     * Get all of the detailBayar for the MasterPembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailBayar()
    {
        return $this->hasMany(DetailBayarKuker::class, 'master_bayar_id', 'id');
    }
}
