<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class PembayaranModel extends Model
{
    use HasFactory;

    protected $table = "tb_bayar";
    protected $appends = ['bukti_bayar_url'];
    protected $fillable = 
    ['id_tahun','id_bulan','id_semester','no_kwitansi','tgl_bayar','jam_bayar','id_kasir','nim','keterangan','tot_bayar','bukti_bayar','tot_kwajiban','via','bank_penerima','sts_trans','ket_simple'];


    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaMbcModel::class, 'nim', 'str_nim');
        // parameter ke-2 = foreign key di mbc_bayar
        // parameter ke-3 = primary/unique key di siswa
    }

    public function detailBayar()
    {
        return $this->hasMany(DetailPembayaranModel::class, 'id_bayar', 'id');
    }

   protected function buktiBayarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Hapus print_r($filename);
    
                // Cek 1: Pastikan bukti_bayar adalah string yang tidak kosong
                if (!empty($this->bukti_bayar) && is_string($this->bukti_bayar)) {
                     $filename = basename($this->bukti_bayar);
    
                     //print_r($filename);
                     
                     // Cek 2: Pastikan basename() menghasilkan nama file yang valid
                     if (!empty($filename)) {
                         // Parameter route harus sesuai dengan route URI Anda {path}
                         return route('admin.asset.show', ['filename' => $filename]); 
                     }
                }
                
                // Mengembalikan null (atau string kosong) jika validasi gagal
                return null;
            }
        );
    }




}
