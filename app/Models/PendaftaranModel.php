<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PendaftaranModel extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = 'pendaftarans';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat',
        'no_hp',
        'email',
        'agama',
        'status',
        'asal_sekolah',
        'jurusan',
        'thn_lulus',
        'nem',
        'program_id',
        'lokasi_id',
        'foto',
        'periode_id',
        'user_id',
        'usia',
        'kota',
        'provinsi',
        'negara',
        'almat_ktp',
        'no_ktp',
        'pernikahan',
        'profesi',
        'jml_saudara',
        'saudara_ke',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'darurat_tlp',
        'darurat_nama',
        'darurat_hubungan',
        'darurat_alamat',
        'pengalaman',
        'bidang_kerja',
        'gaji_terakhir',
        'keahlian_kusus',
        'sim',
        'bpjs',
        'laptop',
        'mampu_laptop',
        'mampu_inggris',
        'mampu_jepang',
        'hobi',
        'kepribadian',
        'tato',
        'tinggi_badan',
        'berat_badan',
        'ukuran_baju',
        'kondisi_mata',
        'alergi_obat',
        'buta_warna',
        'gol_darah',
        'penyakit_kronis',
        'status_pendaftar',
        'facebook',
        'instagram',
        'tiktok',
        'linkedin',
        'twitter',
        'prestasi',
        'status_wawancara',
        'status_kesehatan',
        'pendidikan_terakhir',
        'status_berangkat',
    ];

    protected $dates = [
        'tgl_lahir',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
