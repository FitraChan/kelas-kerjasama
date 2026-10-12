<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaranModel extends Model
{
    use HasFactory;
    protected $table = "tb_det_bayar";
    protected $fillable = 
    ['id','id_bayar','id_item','jml_bayar','id_cicilan','keterangan','sisa_bayar','kwajiban_bayar','potongan','id_user'];


     public function bayar()
    {
        return $this->belongsTo(PembayaranModel::class, 'id_bayar', 'id');
    }

    public function itemBayar()
    {
        return $this->belongsTo(ItemBayar::class, 'id_item');
    }
    
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_user');
    }



}
