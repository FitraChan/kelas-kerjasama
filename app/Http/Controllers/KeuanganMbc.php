<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keuangan_model;
use App\Models\Status_model;
use App\Models\PembayaranModel;
use App\Models\ItemBayar;
use App\Models\StatusMahasiswaModel;
use App\Models\DetailPembayaranModel;
use App\Models\Jurusan;
use App\Models\MataKuliahModel;
use App\Models\DetailBayarKuker;
use App\Models\MasterBayarKuker;
use Illuminate\Support\Facades\Storage;

use App\Models\MahasiswaMbcModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\LogModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Exports\MahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class KeuanganMbc extends Controller
{
  private $fpdf;
  //public $log;
  public function __construct() {}
  public function index(Request $request)
  {


    $angkatan = MahasiswaMbcModel::select('str_angkatan')->whereRaw('str_angkatan > 2017')->groupBy('str_angkatan')->orderBy('str_angkatan', 'asc')->get();
    $jurusan = Jurusan::get();




    $item_bayar = DB::table('tb_itembayar')->get();
    return view('livewire.keuangan_mbc.index', compact('angkatan', 'jurusan', 'item_bayar'));
  }

  public function create_datatable_mbc(Request $request)
  {

    $cari  = $request->cari ?? '';
    $tahun_ajaran = $request->tahun_ajaran ?? null;
    $status = $request->status_mahasiswa ?? null;
    $years = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();

    $tahun =  $years->thn_ajaran;

    $id_tahun =  $years->thn_ajaran;

    $hasil = MahasiswaMbcModel::withoutGlobalScopes()
      ->from('tb_mbc_mahasiswa as a')
      ->leftJoin('tb_status_mahasiswa as f', 'f.nim', '=', 'a.str_nim')
      ->where('f.id_thn_ajaran', $id_tahun)
      ->select(
        'a.str_nim',
        'a.str_nm_mhs',
        'f.status',
        'a.bol_lulus',
        'a.is_luarnegeri',
      )
      ->whereNull('a.deleted_at') // 🔥 INI PENTING
      ->when($cari, function ($query, $cari) {
        $query->where(function ($q) use ($cari) {
          $q->where('a.str_nm_mhs', 'like', '%' . $cari . '%')
            ->orWhere('a.str_nim', 'like', '%' . $cari . '%');
        });
      })

      ->when($status, function ($query, $status) use ($tahun) {


        if ($status == 'aktif') {
          $query->where('a.bol_lulus', 0)
            ->where('status', 1)
            ->where('f.id_thn_ajaran', $tahun);
        } elseif ($status == 'cuti') {
          $query->where('a.bol_lulus', 0)
            ->where('status', 2)
            ->where('f.id_thn_ajaran', $tahun);
        }
        elseif ($status == 'nonaktif') {
          $query->where('a.bol_lulus', 0)
            ->where('status', 3)
            ->where('f.id_thn_ajaran', $tahun);
        }
        elseif ($status == 'do') {
          $query->where('a.bol_lulus', 3);
        } elseif ($status == 'lulus') {
          $query->where('a.bol_lulus', 1);
        }
      });

    return datatables()->of($hasil)
      ->addIndexColumn()
      ->addColumn('action', function ($row) {
        $btn = '<button class="btn btn-sm btn-info" onclick="statusTunggakan(\'' . $row->str_nim . '\')">Tunggakan</button>';
        return $btn;
      })

      ->make(true);
  }

  public function semester($nim)
  {
    // code...
    $smt = DB::table('tb_status_mahasiswa')->where('nim', $nim)->first();
    
    $pro = MahasiswaMbcModel::where('str_nim', $nim)->first();

     if (($pro->str_angkatan >= 2022 && $pro->str_angkatan < 2023) && $pro->str_angkatan != 2019) {

        if ($pro->str_jurusan == 001) { //'S1-SISTEM KOMPUTER'
        
          //   echo "S1-SISTEM KOMPUTER"; 
          
        

        $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SK')->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
          
        $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SK')->where('nama_item', 'LIKE', '%DPP%')->first();
          
        $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SK')->where(function ($q) {
          $q->where('nama_item', 'like', '%Jaket%')
            ->orWhere('nama_item', 'like', '%Kaos%')
            ->orWhere('nama_item', 'like', '%Almamater%');
        })->first();



        } elseif ($pro->str_jurusan  == 003) { //'S1-SISTEM INFORMASI'
          //   echo "S1-SISTEM INFORMASI";
          
           
           
        $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
          
        $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->where('nama_item', 'LIKE', '%DPP%')->first();
          
        $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->where(function ($q) {
          $q->where('nama_item', 'like', '%Jaket%')
            ->orWhere('nama_item', 'like', '%Kaos%')
            ->orWhere('nama_item', 'like', '%Almamater%');
        })->first();
           
           
           
        } elseif ($pro->str_jurusan == 005) { // 'S1-BISNIS DIGITAL'
         // echo "S1-BISNIS DIGITAL";
         // $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SB')->get();
         
            $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SB')->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
              
            $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SB')->where('nama_item', 'LIKE', '%DPP%')->first();
              
            $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SB')->where(function ($q) {
              $q->where('nama_item', 'like', '%Jaket%')
                ->orWhere('nama_item', 'like', '%Kaos%')
                ->orWhere('nama_item', 'like', '%Almamater%');
            })->first();
        } else {
         // $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
            $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
              
            $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('nama_item', 'LIKE', '%DPP%')->first();
              
            $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where(function ($q) {
              $q->where('nama_item', 'like', '%Jaket%')
                ->orWhere('nama_item', 'like', '%Kaos%')
                ->orWhere('nama_item', 'like', '%Almamater%');
            })->first();
        }

      } elseif ($pro->str_angkatan == 2019) {
       // $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
       
            $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
              
            $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('nama_item', 'LIKE', '%DPP%')->first();
              
            $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where(function ($q) {
              $q->where('nama_item', 'like', '%Jaket%')
                ->orWhere('nama_item', 'like', '%Kaos%')
                ->orWhere('nama_item', 'like', '%Almamater%');
            })->first();
      }
      elseif ($pro->str_angkatan == 2021) {
      //  $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', 'SI')->where('str_angkatan', $pro->str_angkatan)->get();
        
            $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
              
            $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->where('nama_item', 'LIKE', '%DPP%')->first();
              
            $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', 'SI')->where('str_angkatan', $pro->str_angkatan)->where(function ($q) {
              $q->where('nama_item', 'like', '%Jaket%')
                ->orWhere('nama_item', 'like', '%Kaos%')
                ->orWhere('nama_item', 'like', '%Almamater%');
            })->first();
      } 
      
      else {
        //$daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
        
            $daftar = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('nama_item', 'LIKE', '%Pendaftaran%')->first();
              
            $dpp = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('nama_item', 'LIKE', '%DPP%')->first();
              
            $jaket = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where(function ($q) {
              $q->where('nama_item', 'like', '%Jaket%')
                ->orWhere('nama_item', 'like', '%Kaos%')
                ->orWhere('nama_item', 'like', '%Almamater%');
            })->first();
        
        
      }
      
      $all[] = [
                'pendaftaran' => $daftar->def_value, 
                'dpp' =>  $dpp->def_value, 
                'jaket' => $jaket->def_value,
                'id_pendaftaran' => $daftar->id, 
                'id_dpp' =>  $dpp->id, 
                'id_jaket' => $jaket->id,
                'semester' => $smt->id_semester
                ];
      return   $all;

    //echo json_encode($smt->id_semester);
  }


  public function simpan(Request $request, LogModel $log)
  {

    $serial = $_SERVER['HTTP_USER_AGENT'];
    $userId = Auth::id();

    // item bayar pendaftaran, dpp ,semesteran

    // $sisa_tagihan_semesteran = $this->sisa_tagihan_nim($request->nim);
    // $this->validate([
    //   //  'semester' => 'required',
    //   'tgl_trans' => 'required',
    //   'image' => 'required',
    // ]);

    Validator::make($request->all(), [
      'tgl_trans' => 'required',
      'image' => 'required',
    ]);
    $first_semester = DB::table('tb_bayar')
      ->where('nim', $request->nim)
      ->orderBy('id_semester', 'asc')->first();

    $smt = $first_semester->id_semester;



    $semesteran = $request->semesteran;
    $pendaftaran = $request->pendaftaran;
    $dpp = $request->dpp;
    $jaket = $request->jaket;
    $final_eksekutif = $request->final_eksekutif;

    $renim = $request->renim;
    $pembayaran_ta = $request->pembayaran_ta;
    
    $sks_diakui = $request->sks_diakui;
  




    $mbc_pay;
    $gambar = $request->file('image');
    $imageName = 'profile_pengguna_' . Str::random(5) . '.' . $gambar->extension();
    // $request->file('image')->move(public_path() . '/images/', $imageName);
    $rute = $gambar->storeAs('bukti-transaksi', $imageName, 'private');
    $absolutePath = storage_path('app/private/' . $rute);


    //if($sisa_tagihan_semesteran <= $request->semesteran  ){
    if ( ($pendaftaran > 0 || $pendaftaran != '') || ($dpp > 0  || $dpp != '') || ($jaket > 0 || $jaket != '') || ($final_eksekutif > 0 || $final_eksekutif != '') || ($renim > 0 || $renim != '') || ($pembayaran_ta > 0 || $pembayaran_ta != '') || ($sks_diakui > 0 || $sks_diakui != '') ) {
      if ($request->file('image') != null) {
        $mbc_pay =  PembayaranModel::create(
          [
            'nim' => $request->nim,
            'id_tahun' => date('Y'),
            'id_bulan' => date('m'),
            'id_semester' => $smt,

           // 'id_semester' => '04-09',
            'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
            'id_kasir' => $userId,
            'via' => $request->via,
            'bank_penerima' => $request->bank_penerima,
            'bukti_bayar' => $rute,
            'no_kwitansi' => $request->no_kwitansi,
            'keterangan' => $request->comments,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ]
        );

        // $this->log->log('tb_bayar', 'insert', json_encode($request->all()), $serial, Auth::user()->email);

        Log::info('User login', ['user_id' => auth()->id(), 'email' => Auth::user()->email, 'serial' => $serial, 'table' => 'tb_bayar', 'data' => $request->all(), 'aksi' => 'create', 'url' => url('simpan')]);
        $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode(['data' => $request->all()]),
          'serial' => url('simpan') // Assuming you want to log the serial number
        ];
        $log->create($logs2);
      }
    }



    $mhs = MahasiswaMbcModel::where('str_nim', $request->nim)->first();



    $newRecordID = DB::getPdo()->lastInsertId();

    if ($pendaftaran > 0 || $pendaftaran != '') {

      $daftar = 0;
      if (strpos($request->pendaftaran, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->pendaftaran);
        $daftar = substr($str, 0, -2);
      } else {
        $daftar = $request->pendaftaran;
      }


      $id_item_p = DB::table('tb_itembayar')
        ->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%Pendaftaran%')
        ->first();


      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_p->id,
          'jml_bayar' => $daftar,
          'id_user' => auth()->user()->id,
        ]
      );

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_p->id,
          'jml_bayar' => $daftar
        ]),
        'serial' => url('simpan') // Assuming you want to log the serial number
      ];
      $log->create($logs);
    }
    
    
    


    if ($pembayaran_ta > 0 || $pembayaran_ta != '') {

      $bayar_ta = 0;
      if (strpos($request->pembayaran_ta, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->pembayaran_ta);
        $bayar_ta = substr($str, 0, -2);
      } else {
        $bayar_ta = $request->pembayaran_ta;
      }


      $id_item_ta = DB::table('tb_itembayar')
        //->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%Bimbingan TA%')
        ->first();


      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_ta->id,
          'jml_bayar' => $bayar_ta,
          'id_user' => auth()->user()->id,
        ]
      );

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_ta->id,
          'jml_bayar' => $bayar_ta
        ]),
        'serial' => url('simpan') // Assuming you want to log the serial number
      ];
      $log->create($logs);
    }

    if ($renim > 0 || $renim != '') {

      $ganti_nim = 0;
      if (strpos($request->renim, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->renim);
        $ganti_nim = substr($str, 0, -2);
      } else {
        $ganti_nim = $request->renim;
      }


      $id_item_r = DB::table('tb_itembayar')
        //->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%renim%')
        ->first();


      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_r->id,
          'jml_bayar' => $ganti_nim,
          'id_user' => auth()->user()->id,
        ]
      );

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_r->id,
          'jml_bayar' => $ganti_nim
        ]),
        'serial' => url('simpan') // Assuming you want to log the serial number
      ];
      $log->create($logs);
    }

    if ($jaket > 0 || $jaket != '') {

      $jaket_final = 0;

      if (strpos($request->jaket, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->jaket);
        $jaket_final = substr($str, 0, -2);
      } else {
        $jaket_final = $request->jaket;
      }


      $id_item_j = DB::table('tb_itembayar')
        ->where('str_angkatan', $mhs->str_angkatan)
        ->where(function ($q) {
          $q->where('nama_item', 'like', '%Jaket%')
            ->orWhere('nama_item', 'like', '%Kaos%')
            ->orWhere('nama_item', 'like', '%Almamater%');
        })
        ->first();


      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_j->id,
          'jml_bayar' => $jaket_final,
          'id_user' => auth()->user()->id,
        ]
      );

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_j->id,
          'jml_bayar' => $jaket_final
        ]),
        'serial' => url('simpan') // Assuming you want to log the serial number
      ];
      $log->create($logs);
    }

    if ($dpp > 0 || $dpp != '') {
      $dpp_final = 0;
      if (strpos($dpp, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $dpp);
        $dpp_final = substr($str, 0, -2);
      } else {
        $dpp_final = $dpp;
      }



      $id_item_d = DB::table('tb_itembayar')
        ->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%dpp%')
        ->first();

      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_d->id,
          'jml_bayar' => $dpp_final,
          'id_user' => auth()->user()->id,
        ]
      );

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_d->id,
          'jml_bayar' => $dpp_final
        ]),
        'serial' => url('simpan') // Assuming you want to log the serial number
      ];
      $log->create($logs);
    }
    
    
     
    
    if ($sks_diakui > 0 || $sks_diakui != '') {

      $sks = 0;
      if (strpos($request->sks_diakui, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->sks_diakui);
        $sks = substr($str, 0, -2);
      } else {
        $sks = $request->sks_diakui;
      }


      $id_item_sks = DB::table('tb_itembayar')
        //->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%SKS Di akui%')
        ->first();


      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_sks->id,
          'jml_bayar' => $sks,
          'id_user' => auth()->user()->id,
        ]
      );

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_sks->id,
          'jml_bayar' => $sks,
          'id_user' => auth()->user()->id,
        ]),
        'serial' => url('simpan') // Assuming you want to log the serial number
      ];
      $log->create($logs);
    } //  if ($sks_diakui > 0 || $sks_diakui != '') {

    //===========================final Eksekutif

    if ($final_eksekutif > 0 || $final_eksekutif != '') {
      $final_eksekutif_final = 0;
      if (strpos($final_eksekutif, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $final_eksekutif);
        $final_eksekutif_final = substr($str, 0, -2);
      } else {
        $final_eksekutif_final = $final_eksekutif;
      }



      $id_item_d = DB::table('tb_itembayar')
        // ->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%final_eksekutif%')
        ->where('keterangan', '210030026')
        ->first();

      DB::table('tb_det_bayar')->insert(
        [
          'id_bayar' => $mbc_pay->id,
          'id_item' => $id_item_d->id,
          'jml_bayar' => $final_eksekutif_final,
          'id_user' => auth()->user()->id,
        ]
      );
    }

    //==========================final Eksekutif


    if ($semesteran > 0 || $semesteran != '') {

      $semesteran_final = 0;

      if (strpos($semesteran, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $semesteran);
        $semesteran_final = substr($str, 0, -2);
      } else {

        $semesteran_final = $semesteran;
      }


      $id_item_s = DB::table('tb_itembayar')
        ->where('str_angkatan', $mhs->str_angkatan)
        ->where('nama_item', 'like', '%semesteran%')
        ->first();


      $semester = [];

      //if($request->semester != 0){
      $tunggakan_semester = DB::table('tb_bayar')->select(
        'id_semester',
      )
        ->where('tb_bayar.nim', $request->nim)
        ->orderBy('id_semester', 'asc')
        ->groupBy('id_semester')
        ->get();

      foreach ($tunggakan_semester as $sem) {
        if (!empty($sem->id_semester)) {
          $semester[] = $sem->id_semester;
        }
      }
      //   }else{

      //       $semester = $request->semester;
      //   }

      $status = $this->cekTunggakanKhusus($request->nim);

      //   if ($status == 'belum_lunas') {
      //     return back()->with('error', 'Terdapat Tunggakan Khusus untuk Almamater dan Pendaftaran. Mohon lunasi terlebih dahulu tunggakan tersebut.');
      //   }
      $this->prosesKelebihanBayar2(
        $request,
        $id_item_s,
        $semester,
        $mbc_pay->id ?? null,
        $semesteran_final,
        $semesteran_final,
        $userId,
        $rute,
        $no = 0
      );
    }



    if (($pendaftaran > 0 || $pendaftaran != '') || ($dpp > 0  || $dpp != '') || ($jaket > 0 || $jaket != '') || ($final_eksekutif > 0 || $final_eksekutif != '') || ($sks_diakui > 0 || $sks_diakui != '')) {

      $this->updatekwajiban($mbc_pay->id);
      $this->updatebayar($mbc_pay->id);
    }

    // } // end of if($sisa_tagihan_semesteran <= $request->semesteran  ){


      return back()->with('success', 'Berhasil');


  }


 public function prosesKelebihanBayar2(
    $request,
    $id_item_s,
    $smt_array,
    $newRecordID,
    $sisa_pembayaran,
    $semesteran_final,
    $userId,
    $imageName,
    $no
  ) {
    try {

      return DB::transaction(function () use (
        $request,
        $id_item_s,
        $smt_array,
        $newRecordID,
        $sisa_pembayaran,
        $semesteran_final,
        $userId,
        $imageName,
        $no
      ) {

        $smt = $smt_array;

        if ($sisa_pembayaran > 0 && empty($smt[$no])) {

          $semester = [$this->cekSemesterTerakhirLunas($request->nim)];

          $this->prosesKelebihanBayar2(
            $request,
            $id_item_s,
            $semester,
            '',
            $sisa_pembayaran,
            '',
            $userId,
            $imageName,
            0
          );

          return;
        }

        $tunggakan_sem = DB::table('tb_bayar')->select(
          'id_semester',
          DB::raw('SUM(kwajiban_bayar) as SUMK2'),
          DB::raw('SUM(potongan) as SUMP'),
          DB::raw('SUM(jml_bayar) as SUMB')
        )
          ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
          ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
          ->where('tb_bayar.id_semester', $smt[$no])
          ->where('d.id_kategori', 2)
          ->where('tb_bayar.nim', $request->nim)
          ->groupBy('id_semester')
          ->first();

        $sisa_tagihan_semesteran_next =
          ($tunggakan_sem->SUMK2 ?? 0)
          - ($tunggakan_sem->SUMP ?? 0)
          - ($tunggakan_sem->SUMB ?? 0);

        $bayar_untuk_next = min(
          $sisa_pembayaran,
          max(0, $sisa_tagihan_semesteran_next)
        );

        if ($bayar_untuk_next == 0) {

          $this->prosesKelebihanBayar2(
            $request,
            $id_item_s,
            $smt,
            '',
            $sisa_pembayaran,
            $bayar_untuk_next,
            $userId,
            $imageName,
            $no + 1
          );

          return;
        }

        $insertBayar = PembayaranModel::create([
          'nim' => $request->nim,
          'id_tahun' => date('Y'),
          'id_bulan' => date('m'),
          'id_semester' => sprintf("%02d", $smt[$no]),
          'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
          'id_kasir' => $userId,
          'via' => $request->via,
          'bank_penerima' => $request->bank_penerima,
          'bukti_bayar' => asset('storage/app/private/' . $imageName),
          'no_kwitansi' => $request->no_kwitansi,
          'ket_simple' => $request->comments,
          'created_at' => now(),
          'updated_at' => now(),
        ]);

        LogModel::create([
          'tanggal' => now(),
          'tabel' => 'tb_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode($insertBayar),
          'serial' => url('simpan')
        ]);

        DB::table('tb_det_bayar')->insert([
          'id_bayar' => $insertBayar->id,
          'id_item' => $id_item_s->id,
          'jml_bayar' => $bayar_untuk_next,
          'id_user' => auth()->user()->id,
        ]);

        LogModel::create([
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $insertBayar->id,
            'id_item' => $id_item_s->id,
            'jml_bayar' => $bayar_untuk_next
          ]),
          'serial' => url('simpan')
        ]);

        $this->updatekwajiban($insertBayar->id);
        $this->updatebayar($insertBayar->id);

        $kelebihan_sisa = $sisa_pembayaran - $bayar_untuk_next;

        if ($kelebihan_sisa > 0) {
          $this->prosesKelebihanBayar(
            $request,
            $id_item_s,
            $smt,
            '',
            $kelebihan_sisa,
            $bayar_untuk_next,
            $userId,
            $imageName,
            $no + 1
          );
        }
      });
    } catch (\Throwable $e) {

      Log::error('Error prosesKelebihanBayar2', [
        'nim' => $request->nim ?? null,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
      ]);

      throw $e; // penting agar rollback & error tidak silent
    }
  }


  public function cekTunggakanKhusus($nim)
  {
    $tunggakan_khs = DB::table('tb_bayar')
      ->select(
        'id_item',
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP'),
        DB::raw('SUM(jml_bayar) as SUMB')
      )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
      ->where('tb_bayar.nim', $nim)
      ->where('d.id_kategori', 1)
      ->whereRaw('LOWER(d.nama_item) NOT LIKE ?', ['%dpp%'])
      ->whereNull('ket_simple')
      ->groupBy('id_item')
      ->havingRaw('SUM(kwajiban_bayar) - COALESCE(SUM(potongan),0) > SUM(jml_bayar)')
      ->get();

    // Cek apakah ada data tunggakan
    if ($tunggakan_khs->isNotEmpty()) {
      $status = "belum_lunas";
    } else {
      $status = "lunas";
    }

    return $status;
  }





  public function tunggakanMbc($nim)
  {


    $data['baris'] = DB::table('tb_bayar')->select(
      'id_semester',
      //DB::raw('max(tb_bayar.id) as id_bayar'),
      DB::raw('sum(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')

      ->where('tb_bayar.nim', $nim)


      ->groupBy('id_semester')->get();

    $data['bank'] = DB::table('tb_bank')->orderBy('nama_bank', 'asc')->get();
        $data['bank_penerima'] = DB::table('tb_bank')->whereIn('id',[1,5,12,28])->orderBy('nama_bank', 'asc')->get();


    $data['nim'] = $nim;

    $mhs = MahasiswaMbcModel::where('str_nim', $nim)->first();

    $data['nama'] = $mhs->str_nm_mhs ?? 0;
    
    
    $data['dokumen'] = DB::table('tb_dokumen')->where('nim',$nim)->get();


    $data['item_bayar'] = DB::table('tb_itembayar')
      ->select('nama_item',DB::raw('MAX(id) as id'))    
      ->where('id_kategori', 1)
      ->where('str_angkatan',$mhs->str_angkatan)
      ->orWhere('id',64)
      ->groupBy('nama_item')
      ->get();
    return view('livewire.tunggakan_mbc.index', $data);
  }

  public function reloadCard($id_bayar)
  {

    $id = DB::table('tb_bayar')->where('id', $id_bayar)->first();
    $nim = $id->nim;
    $data['baris'] = DB::table('tb_bayar')->select(
      'id_semester',
      //DB::raw('max(tb_bayar.id) as id_bayar'),
      DB::raw('sum(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')

      ->where('tb_bayar.nim', $nim)


      ->groupBy('id_semester')->get();


    $data['nim'] = $nim;

    $mhs = MahasiswaMbcModel::where('str_nim', $nim)->first();
    $data['bank'] = DB::table('tb_bank')->orderBy('nama_bank', 'asc')->get();
    $data['bank_penerima'] = DB::table('tb_bank')->whereIn('id',[1,5,12,28])->orderBy('nama_bank', 'asc')->get();

    $data['nama'] = $mhs->str_nm_mhs;


    return view('livewire.tunggakan_mbc.tab-siswa', $data);
  }

  public function reloadCardHapus($nim)
  {


    $data['baris'] = DB::table('tb_bayar')->select(
      'id_semester',
      //DB::raw('max(tb_bayar.id) as id_bayar'),
      DB::raw('sum(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')

      ->where('tb_bayar.nim', $nim)


      ->groupBy('id_semester')->get();


    $data['nim'] = $nim;

    $mhs = MahasiswaMbcModel::where('str_nim', $nim)->first();

    $data['nama'] = $mhs->str_nm_mhs;
    
     $data['bank'] = DB::table('tb_bank')->orderBy('nama_bank', 'asc')->get();
        $data['bank_penerima'] = DB::table('tb_bank')->whereIn('id',[1,5,12,28])->orderBy('nama_bank', 'asc')->get();


    return view('livewire.tunggakan_mbc.tab-siswa', $data);
  }




  function coba()
  {


    //  $data = DB::table('tb_bayar')->where('sts_trans',2)->get();

    //  foreach($data as $row){

    //  DB::table('tb_mbc_mahasiswa')->where('str_nim',$row->nim)
    //  ->update(['is_luarnegeri' => 2]);

    //  }





  }

  public function updatekwajiban($id = null)
  {

    //    echo"<pre>";
    // print_r($id);
    // echo"</pre>";
    $det_bayar =  DB::table('tb_det_bayar')->where('id_bayar', $id)->get();

    foreach ($det_bayar as $key) {


      $item = DB::table('tb_itembayar')->where('id', $key->id_item)->first();
      $sisa = $this->sisa_tagihan($id, $item->id);


      // $tot =  $item->def_value - $key->jml_bayar - $key->potongan;
      DB::table('tb_det_bayar')->where('id_bayar', $id)->where('id_item', $item->id)->update(['sisa_bayar' => $sisa]);
    }

    $total =  DB::table('tb_det_bayar')->select(DB::raw('sum(kwajiban_bayar) - sum(potongan)  as tot_kwajiban'))->where('id_bayar', $id)->first();
    DB::table('tb_bayar')->where('id', $id)->update(['tot_kwajiban' => $total->tot_kwajiban]);
  }

  public function sisa_tagihan($id_bayar, $item)
  {

    $nim = PembayaranModel::where('id', $id_bayar)->first();

    $baris = DB::table('tb_bayar')->select(
      'nim',
      'id_item',
      'nama_item',
      DB::raw('SUM(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
      ->groupBy('tb_bayar.nim')
      ->groupBy('tb_det_bayar.id_item')
      ->groupBy('tb_itembayar.nama_item')
      ->having('tb_bayar.nim', $nim->nim)
      ->having('id_item', $item)
      ->orderBy('id_item', 'asc')->first();

    $hasil = $baris->SUMK2 - $baris->SUMP - $baris->SUMB;

    return $hasil;
  }



  public function updatebayar($id = null)
  {
    // $total=$this->db->query('select sum(jml_bayar) as tot_bayar from tb_det_bayar where id_bayar='.$id)->row_array()['tot_bayar'];
    // $data=array('tot_bayar'=>$total);
    // return $this->Sibaru_model->update('tb_bayar',$data,$id);

    $total = DB::table('tb_det_bayar')->select(DB::raw('sum(jml_bayar) as tot_bayar'))->where('id_bayar', $id)->first();

    DB::table('tb_bayar')->where('id', $id)->update(['tot_bayar' => $total->tot_bayar]);
  }



  public function updatetotalbayar($id)
  {

    $total = DB::table('tb_det_bayar')->select(DB::raw('sum(kwajiban_bayar) - sum(potongan) as tot_bayar'))->where('id_bayar', $id)->first();

    DB::table('tb_bayar')->where('id', $id)->update(['tot_kwajiban' => $total->tot_bayar]);
  }
  
  public function create_pdf_excel(Request $request)
  {

     $tahun = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();

        return Excel::download(
            new MahasiswaExport($request, $tahun->thn_ajaran),
            'mahasiswa_filtered.xlsx'
        );

   


  
  }



  public function create_pdf_admin(Request $request)
  {

    $years = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();
    //$status = $request->status_mahasiswa ?? null;
    $tahun_now =  $years->thn_ajaran;
    $query = DB::table('tb_mbc_mahasiswa as a')
      //   ->leftJoin('tb_bayar as b', 'b.nim', '=', 'a.str_nim')
      //   ->leftJoin('tb_det_bayar as c', 'b.id', '=', 'c.id_bayar')
      //   ->leftJoin('tb_itembayar as d', 'c.id_item', '=', 'd.id')
      ->leftJoin('tb_status_mahasiswa as f', 'f.nim', '=', 'a.str_nim')
      // ->whereIn('d.id_kategori', [1, 2])
      ->where('f.id_thn_ajaran', $tahun_now)
      ->whereNull('a.deleted_at')
      ->selectRaw("
        COUNT(DISTINCT a.str_nim) AS total_semua,
        COUNT(DISTINCT CASE WHEN a.bol_lulus = 0 AND f.status = 1 THEN a.str_nim END) AS total_aktif,
        COUNT(DISTINCT CASE WHEN a.bol_lulus = 0 AND f.status = 2 THEN a.str_nim END) AS total_cuti,
        COUNT(DISTINCT CASE WHEN a.bol_lulus = 0  AND f.status = 3 THEN a.str_nim END) AS total_tidak_aktif,
        COUNT(DISTINCT CASE WHEN a.bol_lulus = 3  THEN a.str_nim END) AS do,

        COUNT(DISTINCT CASE WHEN a.bol_lulus = 1 THEN a.str_nim END) AS total_lulus
        

    ")
      ->first();

    $data['total_aktif'] =  $query->total_aktif;
    $data['total_cuti'] = $query->total_cuti;
    $data['total_tidak_aktif'] = $query->total_tidak_aktif;
    
    $data['do'] = DB::table('tb_mbc_mahasiswa as a')->selectRaw("COUNT(DISTINCT CASE WHEN a.bol_lulus = 3 THEN a.str_nim END) AS do")->first()->do;

    $data['total_lulus'] = $query->total_lulus;
    $data['total'] = $query->total_semua;
    $data['md'] = DB::table('tb_mbc_mahasiswa as a')->selectRaw("COUNT(DISTINCT CASE WHEN a.bol_lulus = 5 THEN a.str_nim END) AS md")->first()->md;

    $data['daftar_do'] = DB::table('tb_mbc_mahasiswa as a')
    ->select('a.str_nim', 'a.str_nm_mhs')
    ->where('a.bol_lulus', 3)
    ->whereNull('a.deleted_at')
    ->orderBy('a.str_nm_mhs')
    ->get();

// Daftar mahasiswa MD (bol_lulus = 5)
$data['daftar_md'] = DB::table('tb_mbc_mahasiswa as a')
    ->select('a.str_nim', 'a.str_nm_mhs')
    ->where('a.bol_lulus', 5)
    ->whereNull('a.deleted_at')
    ->orderBy('a.str_nm_mhs')
    ->get();


    $bayarAll =  $this->totalBayarAll($request, $tahun_now);
    $data['reg'] = MahasiswaMbcModel::where('bol_lulus', 0)
      ->where('is_luarnegeri', 1)
      ->count('id');
    $data['ln'] = MahasiswaMbcModel::where('bol_lulus', 0)
      ->where('is_luarnegeri', 2)
      ->count('id');
    $data['all'] = MahasiswaMbcModel::whereIn('is_luarnegeri', [1, 2])->where('bol_lulus', 0)
      ->count();;


    $data['mhs'] = $bayarAll;

    $data['status_mahasiswa'] = $request->status_mahasiswa ?? null;
    
    
    $status_mahasiswa = $request->status_mahasiswa;
    
    $jurusan = null;
    
    if($status_mahasiswa == '001' || $status_mahasiswa == '002' || $status_mahasiswa == '003' || $status_mahasiswa == '004' || $status_mahasiswa == '005' || $status_mahasiswa == '201'){
        
      $jur =    DB::table('tb_jurusan')->where('kd_prodi', $status_mahasiswa)->first();
        
        $jurusan = $jur->nama_jurusan;
    }


    $data['tahun_ajaran'] = $tahun_now;

    $data['jurusan'] = $jurusan;

   


    //   return view('livewire.tagihan_admin_pdf.index', $data);
    $pdf = Pdf::loadView('livewire.tagihan_admin_pdf.index', $data);

    // Download file
    return $pdf->download('tagihan_admin.pdf');
  }

  public function totalBayarAll($request, $tahun_now)
  {


    $sisa = [];
    $status = $request->status_mahasiswa ?? null;
    $query = MahasiswaMbcModel::withoutGlobalScopes()

      ->from('tb_mbc_mahasiswa as a')
      //  ->where('bol_lulus', 0)

      ->leftJoin('tb_bayar as b', 'b.nim', '=', 'a.str_nim')
      ->leftJoin('tb_det_bayar as c', 'b.id', '=', 'c.id_bayar')
      ->leftJoin('tb_itembayar as d', 'c.id_item', '=', 'd.id')
      ->leftJoin('tb_status_mahasiswa as f', 'f.nim', '=', 'a.str_nim')

      ->select(
        'a.str_nim',
        DB::raw('MAX(a.is_luarnegeri) as is_luarnegeri'),

        DB::raw('MAX(f.id_thn_ajaran) as str_thn_ajaran'),
        DB::raw('MAX(a.bol_sts_kelas) as bol_sts_kelas'),
        DB::raw('MAX(a.str_angkatan) as str_angkatan'),
        DB::raw('MAX(str_nm_prodi) as str_nm_prodi'),
        DB::raw('MAX(str_nm_mhs) as str_nm_mhs'),
        DB::raw('MAX(f.id_semester) as id_semester'),
        DB::raw('SUM(jml_bayar) as SUMB'),
      )->orderBy('a.str_nim','asc')
      ->when($status, function ($q) use ($status, $tahun_now) {
        // $q->whereRaw('YEAR(b.tgl_bayar) = ?', [$tahun]);

        if ($status == 'aktif') {
          $q->where('bol_lulus', 0)
            ->where('status', 1)
            ->where('f.id_thn_ajaran', $tahun_now);
        } elseif ($status == 'cuti') {
          $q->where('bol_lulus', 0)
            ->where('status', 2)
            ->where('f.id_thn_ajaran', $tahun_now);
        }
        
         elseif ($status == 'nonaktif') {
         $q->where('bol_lulus', 0)
            ->where('status', 3)
            ->where('f.id_thn_ajaran', $tahun_now);
        }
        elseif ($status == 'do') {
          $q->where('bol_lulus', 3);
        } elseif ($status == 'lulus') {
          $q->where('bol_lulus', 1);
        }
        elseif ($status == 'reg') {
          $q->where('a.is_luarnegeri', 1);
        }
        elseif ($status == 'ln') {
          $q->where('a.is_luarnegeri', 2);
        }
        
        elseif ($status == '005') {
          $q->where('a.str_jurusan', '005');
        }
         elseif ($status == '001') {
          $q->where('a.str_jurusan', '001');
        }
         elseif ($status == '003') {
          $q->where('a.str_jurusan', '003');
        }
         elseif ($status == '004') {
          $q->where('a.str_jurusan', '004');
        }
        elseif ($status == '002') {
          $q->where('a.str_jurusan', '002');
        }
        elseif ($status == '004') {
          $q->where('a.str_jurusan', '201');
        }
      })
      // ->where('a.str_angkatan', '>', '2017')
      //  ->whereIn('d.id_kategori', [1, 2])
      // ->whereIn('a.bol_sts_kelas', [5, 7, 0, 1])
      //->whereIn('a.bol_sts_kelas', [5, 7])
      // ->whereNotNull('a.is_luarnegeri')   
      ->orderBy('str_nm_mhs','asc')
      ->groupBy('a.str_nim')
      ->get();


    foreach ($query as $row) {

      // if($row->SUMB > 0 || $row->str_angkatan < 2025){

      $tag = DB::table('tb_bayar')->select(
        'nim',
        'id_item',
        'nama_item',
        DB::raw('SUM(tot_bayar) as SUMT1'),
        DB::raw('SUM(tot_kwajiban) as SUMK1'),
        DB::raw('SUM(jml_bayar) as SUMB'),
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP')
      )
        ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
        ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
        ->groupBy('tb_bayar.nim')
        ->groupBy('tb_det_bayar.id_item')
        ->groupBy('tb_itembayar.nama_item')
        ->having('tb_bayar.nim', $row->str_nim)
        ->whereRaw('id_kategori != 2')
        ->orderBy('id_item', 'asc')->get();

      $sem = DB::table('tb_bayar')->select(
        'nim',
        'id_semester',
        'id_item',
        'nama_item',
        DB::raw('MAX(id_kategori) as id_kategori'),
        DB::raw('SUM(tot_bayar) as SUMT1'),
        DB::raw('SUM(tot_kwajiban) as SUMK1'),
        DB::raw('SUM(jml_bayar) as SUMB'),
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP')
      )
        ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
        ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
        ->groupBy('tb_bayar.nim')
        ->groupBy('tb_det_bayar.id_item')
        ->groupBy('tb_itembayar.nama_item')
        ->groupBy('id_semester')
        ->having('tb_bayar.nim', $row->str_nim)
        ->where('id_kategori', 2)
        ->orderBy('id_item', 'asc')->get();
      $totB = 0.0;
      $totK2 = 0.0;
      $totP = 0.0;
      foreach ($tag as $baris) {
        $sumb = floatval($baris->SUMB ?? 0);
        $sumk2 = floatval($baris->SUMK2 ?? 0);
        $sump = floatval($baris->SUMP ?? 0);

        $totB += $sumb;
        $totK2 += $sumk2;
        $totP += $sump;
      }

      foreach ($sem as $baris2) {

        $sumbSem = floatval($baris2->SUMB ?? 0);
        $sumk2Sem = floatval($baris2->SUMK2 ?? 0);
        $sumpSem = floatval($baris2->SUMP ?? 0);

        $totB += $sumbSem;
        $totK2 += $sumk2Sem;
        $totP += $sumpSem;
      }

      $sisa[] = [

        'total_bayar' => $totB,
        'kewajiban' => $totK2,
        'sisa' => $totK2 - $totP - $totB,
        'nim' => $row->str_nim,
        'nama' => $row->str_nm_mhs,
        'tahun_ajaran' => $row->str_thn_ajaran,
        'status' => $row->is_luarnegeri,


      ];
    } // end foreach ($query as $row) {


    $result = $sisa;



    return $result;
  }

  function kehadiran($nim)
  {
    $thn =  DB::table('tb_status_mahasiswa')->where('nim', $nim)->orderBy('str_thn_ajaran', 'desc')->first();

    if (!$thn) {
      // Kalau tidak ada data, langsung balikin 0 atau null
      return 0;
    }

    $ids  = DB::table('C_ABSENSI_DETAIL')->where('str_nim', $nim)->where('str_thn_ajaran', $thn->str_thn_ajaran)->get();
    $total = 0;
    $hadir = 0;

    foreach ($ids as $row) {
      for ($i = 1; $i <= 16; $i++) {
        $kolom = "str_ke_$i";
        if (isset($row->$kolom) && $row->$kolom !== null && $row->$kolom !== '') {
          $total++;
          if ($row->$kolom === "H") {
            $hadir++;
          }
        }
      }
    }

    $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

    return $persentase;
  }

  public function mahasiswaAktif($nim)
  {
     $thn = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();

    $status = DB::table('tb_status_mahasiswa')
      ->where('nim', $nim)
      ->where('id_thn_ajaran', $thn->thn_ajaran)
      ->first();

    return $status->status ?? 0;   
  }

  public function sisa_tagihan_nim($nim)
  {


    $sem = DB::table('tb_bayar')->select(
      'nim',
      'id_semester',
      'id_item',
      'nama_item',
      DB::raw('MAX(id_kategori) as id_kategori'),

      DB::raw('SUM(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
      ->groupBy('tb_bayar.nim')
      ->groupBy('tb_det_bayar.id_item')
      ->groupBy('tb_itembayar.nama_item')
      ->groupBy('id_semester')
      ->having('tb_bayar.nim', $nim)
      ->where('id_kategori', 2)
      ->orderBy('id_item', 'asc')->get();


    $totK2 = 0.0;
    $totB = 0.0;



    foreach ($sem as $baris2) {

      $sumkSem = floatval($baris2->SUMK2 ?? 0);
      $sumbSem = floatval($baris2->SUMB ?? 0);

      $totK2 += $sumkSem;
      $totB += $sumbSem;
    }

    $total_all = $totK2 - $totB;

    return $total_all;
  }




  public function create_pdf($nim)
  {


    //$data['hadir'] = $this->kehadiran($nim);
    $data['thn_ajaran'] = DB::table('tb_status_mahasiswa')->where('nim', $nim)->orderBy('id_thn_ajaran', 'desc')->first();
    $aktif = $this->mahasiswaAktif($nim);

   
    
               $status = [
            1 => 'Aktif',
            2 => 'Cuti',
            3 => 'Tidak Aktif',
            4 => 'Lulus'
        ];
        
        $data['aktif'] = $status[$aktif] ?? '-';

    $tag = DB::table('tb_bayar')->select(
      'nim',
      //  'id_item',
      'jml_bayar',
      'tb_bayar.tgl_bayar',
      DB::raw('MAX(id_item) as id_item'),
      DB::raw('MAX(nama_item) as nama_item'),
      DB::raw('MAX(id_semester) as id_semester'),

      DB::raw('MAX(ket_simple) as ket_simple'),


      DB::raw('SUM(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP'),
      DB::raw('MAX(users.name) as nama_user')

    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
      ->leftJoin('users', 'tb_det_bayar.id_user', '=', 'users.id')

      ->groupBy('tb_bayar.nim')
      // ->groupBy('tb_det_bayar.id_item')
      ->groupBy(
        'tb_itembayar.nama_item',
        'tb_det_bayar.jml_bayar',
        'tb_bayar.tgl_bayar'
      )
      ->having('tb_bayar.nim', $nim)
      ->where('id_kategori', 1)
      ->orderBy('id_item', 'asc')->get();

    $sem = DB::table('tb_bayar')->select(
      'nim',
      //  'id_semester',

      'tb_bayar.tgl_bayar',

      DB::raw('MAX(id_semester) as id_semester'),
      DB::raw('MAX(id_item) as id_item'),
      DB::raw('MAX(a.str_angkatan) as str_angkatan'),
      DB::raw('MAX(ket_simple) as ket_simple'),
      DB::raw('MAX(nama_item) as nama_item'),
      DB::raw('MAX(id_kategori) as id_kategori'),
      DB::raw('SUM(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),

      DB::raw('MAX(jml_bayar) as jml_bayar'),

      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP'),
     //DB::raw('MAX(tb_det_bayar.id_user) as id_user'),
     DB::raw('MAX(users.name) as nama_user')


    )
      // ->whereRaw('jml_bayar > 0')    
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
      ->join('tb_mbc_mahasiswa as a', 'a.str_nim', '=', 'tb_bayar.nim')
      ->leftJoin('users', 'tb_det_bayar.id_user', '=', 'users.id')


      ->groupBy('tb_bayar.nim')
      //->groupBy('tb_det_bayar.id_item')
      // ->groupBy('tb_itembayar.nama_item')
      ->groupBy(
        'tb_bayar.nim',
        'tb_det_bayar.jml_bayar',
        'tb_bayar.tgl_bayar',
        'id_semester'
      )
      ->having('tb_bayar.nim', $nim)
      ->where('id_kategori', 2)
      // ->orderBy('id_item', 'asc')
      ->orderBy('id_semester', 'asc')->get();

    $data['atas'] = MahasiswaMbcModel::where('str_nim', $nim)->first();
    $data['isi'] = $tag;
    $data['sem'] = $sem;

    //$data['angkatan'] = MahasiswaMbcModel::select('str_angkatan')->whereRaw('str_angkatan > 2021')->orderBy('str_angkatan', 'asc')->groupBy('str_angkatan')->get();

    $ids = MahasiswaMbcModel::where('bol_lulus', 0)
      ->where('str_angkatan', '>', 2023)
      ->where(function ($q) {
        $q->where('bol_sts_kelas', 5)
          ->orWhere('bol_sts_kelas', 7);
      })
      ->select('str_angkatan', DB::raw('COUNT(str_nim) as jumlah'))
      ->groupBy('str_angkatan')
      ->pluck('jumlah', 'str_angkatan');

    $data['akt'] = $ids;
  //  $data['semesterGroup'] = $sem->groupBy('id_semester');
//     $data = [
//     'semesterGroup'  => $isi->groupBy('semester'), // ⬅️ KUNCI
// ];


    return view('livewire.tagihan_pdf', $data);
   // $pdf = Pdf::loadView('livewire.tagihan_pdf', $data)->setPaper('a4', 'landscape');;

    // Download file
    // return $pdf->download('tagihan_' . $nim . '.pdf');
  }
  
   public function create_pdf_kuliah_kerja($nim)
   {
       
    $data['atas'] = MahasiswaMbcModel::where('str_nim', $nim)->first();
   
    $data['pdf'] = DB::table('pendaftarans as a')
    ->select(
        'b.str_nim',
        'd.nama_pembayaran',
        'c.created_at',
        DB::raw('SUM(c.nominal) as nominal'),
        DB::raw('SUM(c.dibayarkan) as dibayarkan')
    )
    ->leftJoin('tb_mbc_mahasiswa as b', 'a.id', '=', 'b.id_kuliah_kerja')
    ->leftJoin('detail_pembayarans as c', 'c.user_id', '=', 'a.id')
    ->leftJoin('master_bayar as d', 'd.id', '=', 'c.master_bayar_id')
    ->where('b.str_nim', $nim)
    ->where('c.status','berhasil')
    ->groupBy(
        'b.str_nim',
        'd.nama_pembayaran',
        'c.created_at',               
    )
    ->get();
    
    
     //  return view('livewire.tagihan_pdf_kuker', $data);
    
    $pdf = Pdf::loadView('livewire.tagihan_pdf_kuker', $data);

    // Download file
     return $pdf->download('tagihan_kuliah_kerja' . $nim . '.pdf');


       
   }

  public function histori_transaksi_pdf($nim)
  {
    $tag = DB::table('tb_bayar')->select(
      'nim',
      'id_semester',
      'tgl_bayar',
      'id_item',
      'nama_item',
      DB::raw('SUM(tot_bayar) as SUMT1'),
      DB::raw('SUM(tot_kwajiban) as SUMK1'),
      DB::raw('SUM(jml_bayar) as SUMB'),
      DB::raw('SUM(kwajiban_bayar) as SUMK2'),
      DB::raw('SUM(potongan) as SUMP'),
      DB::raw('SUM(sisa_bayar) as SB')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar', 'tb_det_bayar.id_item', '=', 'tb_itembayar.id')
      ->groupBy('tb_bayar.nim')
      ->groupBy('tb_det_bayar.id_item')
      ->groupBy('tb_itembayar.nama_item')
      ->groupBy('tgl_bayar')
      ->groupBy('id_semester')
      ->having('tb_bayar.nim', $nim)
      ->orderBy('id_item', 'asc')
      ->orderBy('tgl_bayar', 'asc')->get();



    $data['atas'] = MahasiswaMbcModel::where('str_nim', $nim)->first();
    $data['isi'] = $tag;

    return view('livewire.tagihan_histori_pdf', $data);
  }

  function cekTagihanSemester($nim)
  {
    $data = DB::table('tb_bayar as b')
      ->leftJoin('tb_det_bayar as d', 'b.id', '=', 'd.id_bayar')
      ->select(
        'b.nim',
        'b.id_semester',
        DB::raw('SUM(kwajiban_bayar - COALESCE(potongan, 0)) as total_kewajiban'),
        DB::raw('COALESCE(SUM(d.jml_bayar), 0) as total_bayar')
      )
      ->where('b.nim', $nim)
      ->groupBy('b.nim', 'b.id_semester')
      //->havingRaw('SUM(kwajiban_bayar - potongan) > COALESCE(SUM(d.jml_bayar), 0)')
      ->havingRaw('SUM(kwajiban_bayar - COALESCE(potongan, 0)) > COALESCE(SUM(d.jml_bayar), 0)')
      ->first();

    return $data->id_semester ?? null;
  }


  function cekSemesterTerakhirLunas($nim)
  {


    // 1️⃣ Ambil semester terakhir yang pernah ditagihkan
    $semester_terakhir = DB::table('tb_bayar')
      ->where('nim', $nim)
      ->max('id_semester');

    if (!$semester_terakhir) {
      return [
        'status' => 'tidak_ada_tagihan',
        'id_semester' => null
      ];
    }

    // 2️⃣ Hitung total kewajiban, potongan, dan pembayaran pada semester itu
    $data = DB::table('tb_bayar')
      ->select(
        DB::raw('SUM(kwajiban_bayar) as total_kwajiban'),
        DB::raw('SUM(potongan) as total_potongan'),
        DB::raw('SUM(jml_bayar) as total_bayar')
      )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
      ->where('d.id_kategori', 2)
      ->where('tb_bayar.nim', $nim)
      ->where('id_semester', $semester_terakhir)
      ->first();

    // 3️⃣ Cek apakah sudah lunas
    $total_kwajiban = ($data->total_kwajiban ?? 0) - ($data->total_potongan ?? 0);
    $total_bayar = $data->total_bayar ?? 0;

    if ($total_bayar >= $total_kwajiban) {
      $status = 'lunas';

      print_r('lunas');
      $smt =  $this->setSemesterBaru($nim, intval($semester_terakhir) + 1);

      return $smt;
    } else {
      $status = 'belum_lunas';
      // print_r('belum_lunas');

      $smt = $this->cekTagihanSemester($nim);

      return $smt;
    }
  }

  public function semesterTagihanTerbesar($nim)
  {
    $data = DB::table('tb_bayar')
      ->select(

        'id_semester',
        DB::raw('SUM(kwajiban_bayar) - SUM(potongan) AS total_tagihan'),
        DB::raw('MAX(id_item) as id_item')
      )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
      ->where('d.id_kategori', 2)
      ->where('tb_bayar.nim', $nim)
      ->groupBy('id_semester')
      ->orderByDesc('total_tagihan')
      ->first(); // hanya ambil yang terbesar

    return ['tagihan' => $data->total_tagihan, 'id_item' => $data->id_item] ?? 0;
  }


  public function setSemesterBaru($nim, $smt)
  {


    if ($smt < 10) {
      $sem = '0' . $smt;
    } else {
      $sem = $smt;
    }

    $bayar = array(
      'id_semester' => $sem,
      'nim' => $nim,
      'id_bulan' => date('m'),
      'id_tahun' => date('Y'),
    );

    //$cek = DB::table('tb_bayar')->leftJoin('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')->where('tb_det_bayar.id_item', 4)->where('nim', $nim)->where('id_semester', $sem)->first();
    // if (empty($cek->id)) {
    $save = DB::table('tb_bayar')->insert($bayar);

    $pro = MahasiswaMbcModel::where('str_nim', $nim)->first();
    $data = null;

    $data = $this->semesterTagihanTerbesar($nim);
    $nbayar = DB::table('tb_bayar')->orderBy('id', 'desc')->first();
    // foreach ($data as $rows) {
    $isi = array(
      'id_bayar' => $nbayar->id,
      'id_item' => $data['id_item'],
      'kwajiban_bayar' => $data['tagihan']
    );

    DB::table('tb_det_bayar')->insert($isi);

    $this->updatetotalbayar($nbayar->id);
    //  }
    return $smt;
  }

  public function getTagihan($nim)
  {

    $pro = MahasiswaMbcModel::where('str_nim', $nim)->first();
    $data = null;
    if (($pro->str_angkatan >= 2022 && $pro->str_angkatan < 2023) && $pro->str_angkatan != 2019) {
      //echo"masuk";
      if ($pro->str_jurusan == 001) { //'S1-SISTEM KOMPUTER'
        //   echo"S1-SISTEM KOMPUTER";
        $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->first();
      } elseif ($pro->str_jurusan   == 003) { //'S1-SISTEM INFORMASI'
        //   echo"S1-SISTEM INFORMASI";
        $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->first();
      } elseif ($pro->str_jurusan == 004) { // 'S1-TEKNOLOGI INFORMASI'
        //   echo"S1-TEKNOLOGI INFORMASI";
        $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->first();
      } elseif ($pro->str_jurusan == 005) { // 'S1-BISNIS DIGITAL'
        // echo"S1-BISNIS DIGITAL";
        $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->first();
      }
    } elseif ($pro->str_angkatan == 2019) {
      $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', '2019')->first();
    } else {
      $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->first();
    }

    $all[] = ['nilai' => $data->def_value, 'id' => $data->id];
    return   $all;
  }
  
  

  // set pembayaran semesteran
  function settagihan(Request $request)
  {

    $nim = $request->nim2;

    //$smt = $request->semester_now;

    $dari = $request->dari;
    $sampai = $request->sampai;




    for ($smt = $dari; $smt <= $sampai; $smt++) {


      // print_r($smt);

      //  $sem;
      // if ($smt < 10) {
      //   $sem = '0' . $smt;
      // } else {
      //   $sem = $smt;
      // }
      $sem = str_pad($smt, 2, '0', STR_PAD_LEFT);

      $bayar = array(
        'id_semester' => $sem,
        'nim' => $nim,
        'id_bulan' => date('m'),
        'id_tahun' => date('Y'),
      );

      $semesterAwal = DB::table('tb_bayar')
        ->where('nim', $nim)
        ->min('id_semester');

      $cek = DB::table('tb_bayar')
        ->leftJoin('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
        ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
        ->where('d.id_kategori', 2)
        ->where('nim', $nim)
        ->where('id_semester', $semesterAwal)
        ->first();
      // if (empty($cek->id)) {
      $save = DB::table('tb_bayar')->insert($bayar);

      //     $pro = MahasiswaMbcModel::where('str_nim', $nim)->first();
      //     $data = null;
      //     if ($pro->str_angkatan < 2023 && $pro->str_angkatan != 2019) {
      //       //echo"masuk";
      //       if ($pro->str_jurusan == 001) { //'S1-SISTEM KOMPUTER'
      //  // echo"S1-SISTEM KOMPUTER";
      //         $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->where('keterangan','SI')->first();
      //       } elseif ($pro->str_jurusan   == 003) { //'S1-SISTEM INFORMASI'
      //  // echo"S1-SISTEM INFORMASI";
      //         $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->where('keterangan','SI')->first();
      //       }
      //       elseif ($pro->str_jurusan == 004) { // 'S1-TEKNOLOGI INFORMASI'
      //  // echo"S1-TEKNOLOGI INFORMASI";
      //         $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->where('keterangan','SI')->first();
      //       }
      //       elseif ($pro->str_jurusan == 005) { // 'S1-BISNIS DIGITAL'
      //  // echo"S1-BISNIS DIGITAL";
      //         $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->first();
      //       }
      //     } elseif ($pro->str_angkatan == 2019) {
      //       $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', '2019')->first();
      //     } else {
      //       $data = DB::table('tb_itembayar')->where('id_kategori', 2)->where('str_angkatan', $pro->str_angkatan)->first();
      //     }
      $nbayar = DB::table('tb_bayar')->orderBy('id', 'desc')->first();
      // foreach ($data as $rows) {
      $isi = array(
        'id_bayar' => $nbayar->id,
        'id_item' => $request->id_item,
        'kwajiban_bayar' => $request->biaya,
        'id_user' => auth()->user()->id,
      );

      DB::table('tb_det_bayar')->insert($isi);

      $logs2 = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $nbayar->id,
          'id_item' => $request->id_item,
          'kwajiban_bayar' => $request->biaya,
        ]),
        'serial' => url('settagihan') // Assuming you want to log the serial number
      ];
      LogModel::create($logs2);

      $this->updatetotalbayar($nbayar->id);
      //}// end foreach ($data as $rows)
      // } else {

      //   $this->setSemesterBaru($nim, $smt);
      // } // end of if (empty($cek->id)) {



    } // end for($smt=$dari; $smt<=$sampai; $smt++){
    //  if ($mbc_pay) {
    return back()->with('success', 'Berhasil');


    //  }// end foreach ($row as $key)

  }
  
  
  
  public function getTagihanKhusus($nim)
  {
      
    
     $nim = $request->nim3;

    $item_bayar = $request->item_bayar;



    $smt = $request->smt_now_tk;


    $sem = str_pad($smt, 2, '0', STR_PAD_LEFT);

    $bayar = array(
      'id_semester' => $sem,
      'nim' => $nim,
    );

    $cek = DB::table('tb_bayar')
      ->leftJoin('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->leftJoin('tb_itembayar', 'tb_itembayar.id', '=', 'tb_det_bayar.id_item')
      ->where('tb_itembayar.id_kategori', 1)
      ->where('nim', $nim)
      ->where('id_semester', $sem)->first();


    if (empty($cek->id)) {
      $save = DB::table('tb_bayar')->insert($bayar);
      $pro = MahasiswaMbcModel::where('str_nim', $nim)->first();
      $data = null;

    if (($pro->str_angkatan >= 2022 && $pro->str_angkatan < 2023) && $pro->str_angkatan != 2019) {

        //   echo"masuk sini";

        //print_r($pro->str_jurusan);

        if ($pro->str_jurusan == 001) { //'S1-SISTEM KOMPUTER'
          //'S1-SISTEM KOMPUTER'
          //   echo "S1-SISTEM KOMPUTER";

          $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SK')->get();
          // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();

          //$data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
        } elseif ($pro->str_jurusan  == 003) { //'S1-SISTEM INFORMASI'
          //'S1-SISTEM INFORMASI'
          //   echo "S1-SISTEM INFORMASI";

           $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->get();

          //   $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();

         // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
        } elseif ($pro->str_jurusan == 005) { // 'S1-BISNIS DIGITAL'
          // 'S1-BISNIS DIGITAL'
         // echo "S1-BISNIS DIGITAL";

          $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SB')->get();
        } else {

          $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
        }
        // else {

        //   $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
        // }
      } elseif ($pro->str_angkatan == 2019) {
        $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
        // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
      }
      elseif ($pro->str_angkatan == 2021) {
        $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', 'SI')->where('str_angkatan', $pro->str_angkatan)->get();
        // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
      } 
      
      else {
        //$data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
        $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
      }


      // $data = DB::table('tb_itembayar')->where('id_kategori',1)->get();

      $nbayar = DB::table('tb_bayar')->orderBy('id', 'desc')->first();
      foreach ($data as $rows) {
        $isi = array(
          'id_bayar' => $nbayar->id,
          'id_item' => $rows->id,
          'kwajiban_bayar' => $rows->def_value,
          'id_user' => auth()->user()->id,
        );

        DB::table('tb_det_bayar')->insert($isi);

        $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $nbayar->id,
            'id_item' => $rows->id,
            'kwajiban_bayar' => $rows->def_value
          ]),
          'serial' => url('tagihan_khusus') // Assuming you want to log the serial number
        ];
        LogModel::create($logs2);
      } // end foreach ($data as $rows)

      $this->updatetotalbayar($nbayar->id);
    }

    return back()->with('success', 'Berhasil');
     
  }



  public function tagihan_khusus(Request $request)
  {
     $nim = $request->nim3;

    // $item_bayar = $request->item_bayar;



     $smt = $request->smt_now_tk;


     $sem = str_pad($smt, 2, '0', STR_PAD_LEFT);

    $bayar = array(
      'id_semester' => $sem,
      'nim' => $nim,
    );

    $cek = DB::table('tb_bayar')
      ->leftJoin('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->leftJoin('tb_itembayar', 'tb_itembayar.id', '=', 'tb_det_bayar.id_item')
      ->where('tb_itembayar.id_kategori', 1)
      ->where('nim', $nim)
      ->where('id_semester', $sem)->first();


    if (empty($cek->id)) {
      $save = DB::table('tb_bayar')->insert($bayar);
      $pro = MahasiswaMbcModel::where('str_nim', $nim)->first();
      $data = null;

    // if (($pro->str_angkatan >= 2022 && $pro->str_angkatan < 2023) && $pro->str_angkatan != 2019) {

    //     //   echo"masuk sini";

    //     //print_r($pro->str_jurusan);

    //     if ($pro->str_jurusan == 001) { //'S1-SISTEM KOMPUTER'
    //       //'S1-SISTEM KOMPUTER'
    //       //   echo "S1-SISTEM KOMPUTER";

    //       $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SK')->get();
    //       // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();

    //       //$data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
    //     } elseif ($pro->str_jurusan  == 003) { //'S1-SISTEM INFORMASI'
    //       //'S1-SISTEM INFORMASI'
    //       //   echo "S1-SISTEM INFORMASI";

    //       $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SI')->get();

    //       //   $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();

    //      // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
    //     } elseif ($pro->str_jurusan == 005) { // 'S1-BISNIS DIGITAL'
    //       // 'S1-BISNIS DIGITAL'
    //      // echo "S1-BISNIS DIGITAL";

    //       $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->where('keterangan', 'SB')->get();
    //     } else {

    //       $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
    //     }
    //     // else {

    //     //   $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
    //     // }
    //   } elseif ($pro->str_angkatan == 2019) {
    //     $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
    //     // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
    //   }
    //   elseif ($pro->str_angkatan == 2021) {
    //     $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', 'SI')->where('str_angkatan', $pro->str_angkatan)->get();
    //     // $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
    //   } 
      
    //   else {
    //     //$data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '180030787')->get();
    //     $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('str_angkatan', $pro->str_angkatan)->get();
    //   }


      // $data = DB::table('tb_itembayar')->where('id_kategori',1)->get();

     

       

      $nbayar = DB::table('tb_bayar')->orderBy('id', 'desc')->first();

// final eksekutif
      //   $data = DB::table('tb_itembayar')->where('id_kategori', 1)->where('keterangan', '210030026')->first();

      // $isi = array(
      //     'id_bayar' => $nbayar->id,
      //     'id_item' => $data->id,
      //     'kwajiban_bayar' => $data->def_value,
      //     'id_user' => auth()->user()->id,
      //   );
        
      //   DB::table('tb_det_bayar')->insert($isi);  

    
    if(!empty($request->id_pendaftaran)){
        
      if (strpos($request->pendaftaran, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->pendaftaran);
        $daftar = substr($str, 0, -2);
      } else {
        $daftar = $request->pendaftaran;
      }
        $isi = array(
          'id_bayar' => $nbayar->id,
          'id_item' => $request->id_pendaftaran,
          'kwajiban_bayar' => $daftar,
          'id_user' => auth()->user()->id,
        );
        
        DB::table('tb_det_bayar')->insert($isi);                        
    }


     
    
     if(!empty($request->id_dpp)){
         
     if (strpos($request->dpp, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->dpp);
        $dpp = substr($str, 0, -2);
      } else {
        $dpp = $request->dpp;
      }
         
        $isi = array(
          'id_bayar' => $nbayar->id,
          'id_item' => $request->id_dpp,
          'kwajiban_bayar' => $dpp,
          'id_user' => auth()->user()->id,
        );
        
        DB::table('tb_det_bayar')->insert($isi);
        
    }
    
     if(!empty($request->id_jaket)){
         
     if (strpos($request->jaket, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->jaket);
        $jaket = substr($str, 0, -2);
      } else {
        $jaket = $request->jaket;
      }
         
         
        $isi = array(
          'id_bayar' => $nbayar->id,
          'id_item' => $request->id_jaket,
          'kwajiban_bayar' => $jaket,
          'id_user' => auth()->user()->id,
        );
        
        DB::table('tb_det_bayar')->insert($isi);
        
    }
    
    
     $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode($isi),
          'serial' => url('tagihan_khusus') // Assuming you want to log the serial number
        ];
        LogModel::create($logs2);

       

       
    //  } // end foreach ($data as $rows)

      $this->updatetotalbayar($nbayar->id);
    }

    return back()->with('success', 'Berhasil');
  }

  public function tambahDetail(Request $request)
  {
     $kewajibanFinal = 0;
      if (strpos($request->kewajiban, "Rp") === 0) {
        $str = preg_replace('/[^0-9]/', '', $request->kewajiban);
        $kewajibanFinal = substr($str, 0, -2);
      } else {
        $kewajibanFinal = $request->kewajiban;
      }

    $isi = [
      'id_item' => $request->id_item,
      'id_bayar' => $request->id_bayar,
      'kwajiban_bayar' => $kewajibanFinal,
      'id_user' => auth()->user()->id,
    ];

    DB::table('tb_det_bayar')->insert($isi);

    $nbayar = DB::table('tb_bayar')->where('id', $request->id_bayar)->first();
    $this->updatetotalbayar($nbayar->id);
    
     $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_item' => $request->id_item,
      'id_bayar' => $request->id_bayar,
      'kwajiban_bayar' => $kewajibanFinal,
          ]),
          'serial' => url('tambahDetail') // Assuming you want to log the serial number
        ];
        LogModel::create($logs2);
    // return back()->with('success', 'Berhasil menambah detail pembayaran');


  }

  public  function hapusDetail(Request $request)
  {
    $id = $request->id;
    $id_bayar = $request->id_bayar;

    DB::table('tb_det_bayar')->where('id', $id)->delete();
    $this->updatetotalbayar($id_bayar);
  }


  function detail_pembayaran($nim)
  {

    $data['nim_mahasiswa'] = $nim;


    return view('livewire.detail_pembayaran', $data);
  }

  public function detail_mbc(Request $request, $nim)
  {

    $hasil = DB::table('v_tb_bayar')->where('nim', $nim)
      ->orderBy('tgl_bayar', 'desc')
      ->get();
    return datatables()->of($hasil)
      ->addIndexColumn()->addColumn('action', function ($row) {
        $btn = '<button class="btn btn-sm btn-success" id="btn-detail" data="' . $row->id . '" onclick="all_pembayaran(' . $row->id . ')">Detail</button>
              ';
        return $btn;
      })
      ->make(true);
  }

  public function all_transaksi(Request $request, $nim)
  {
    $nim_mahasiswa = $nim;
    $status = Status_model::pluck('ket_status', 'id');

    $angkatan = MahasiswaMbcModel::select('str_angkatan')->whereRaw('str_angkatan >= 2017')->groupBy('str_angkatan')->orderBy('str_angkatan', 'asc')->get();
    $jurusan = DB::table('prodi')->get();

    $item_bayar = DB::table('tb_itembayar')->get();
    return view('livewire.detail_transaksi', compact('status', 'angkatan', 'jurusan', 'item_bayar', 'nim_mahasiswa'));
  }

  public function all_transaksi_table(Request $request, $nim)
  {
    $hasil = MahasiswaMbcModel::where('str_nim', $nim)->get();
    return datatables()->of($hasil)
      ->addIndexColumn()->addColumn('action', function ($row) {


        $btn = '<button class="btn btn-sm btn-success" id="btn-detail" data="' . $row->str_nim . '" onclick="bayar(' . $row->str_nim . ')">Bayar</button>

      <button class="btn btn-sm btn-success" id="btn-detail" data="' . $row->str_nim . '" onclick="init(' . $row->str_nim . ')">Tagihan Per Semester</button>



      <button class="btn btn-sm btn-success" id="btn-detail" data="' . $row->str_nim . '" onclick="tagihan_khusus(' . $row->str_nim . ')">Tagihan Khusus</button>



      ';
        return $btn;
      })
      ->make(true);
  }

  public function detail_transaksi(Request $request, $id)
  {
    $id_bayar = $id;
    return view('livewire.all_transaksi', compact('id_bayar'));
  }


  public function detail_transaksi_table(Request $request, $id)
  {


    $hasil =  DetailPembayaranModel::select('id_bayar', 'jml_bayar', 'kwajiban_bayar', 'potongan', 'id_item', 'nama_item', 'sisa_bayar')
      ->leftJoin('tb_itembayar', 'tb_itembayar.id', '=', 'id_item')
      ->where('id_bayar', $id)->get();

    return datatables()->of($hasil)
      ->addIndexColumn()->addColumn('action', function ($row) {


        $btn = '<button class="btn btn-sm btn-success" id="btn-detail"  onclick="edit(' . $row->id_bayar . ',' . $row->id_item . ')">Edit</button>

                        ';
        return $btn;
      })
      ->make(true);
  }

  public function print_kwitansi(Request $request, $id)
  {
    $id_bayar = $id;

    $profil = DB::table('v_tb_bayar')->where('id', $id)->first();

    $angkatan = MahasiswaMbcModel::where('str_nim', $profil->nim)->first();



    $smt = DB::table('tb_status_mahasiswa')->where('nim', $profil->nim)->first();

    $jumlah = DB::table('tb_bayar')
      ->select(DB::raw('max(jml_bayar) as jml_bayar'), 'id_item', 'id_semester', 'nama_item')->where('id_bayar', $id)
      ->leftJoin('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->leftJoin('tb_itembayar', 'tb_itembayar.id', '=', 'tb_det_bayar.id_item')
      ->groupBy('id_item')
      ->groupBy('nama_item')
      ->groupBy('id_semester')->get();


    return view('livewire.kwitansi_mbc', compact('id_bayar', 'jumlah', 'profil', 'smt', 'angkatan'));
  }

  public function edit_transaksi($id_bayar, $id_item, Request $request)
  {

    $jumlah = DB::table('tb_bayar')
      ->select('jml_bayar', 'id_item', 'id_semester', 'nama_item')
      ->where('id_bayar', $id_bayar)
      ->where('id_item', $id_item)
      ->leftJoin('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->leftJoin('tb_itembayar', 'tb_itembayar.id', '=', 'tb_det_bayar.id_item')
      ->first();

    echo json_encode($jumlah);
  }


  public function simpan_edit(Request $request)
  {
    DB::beginTransaction();

    try {

      $userId   = Auth::id();
      $id_bayar = $request->id_bayar;
      $id_item  = $request->id_item;

      $smt = $request->semester;
      $sem = ($smt < 10) ? '0' . $smt : $smt;


      $bayar = DB::table('tb_bayar')
        ->where('id', $id_bayar)
        ->first();

      $item_bayar = DB::table('tb_itembayar')
        ->where('id', $id_item)
        ->first();


        $det_bayar = DB::table('tb_det_bayar')
        ->where('id_bayar', $id_bayar)
        ->where('id_item', $id_item)
        ->first();

      
      


      // ===============================
      // UPDATE tb_bayar (image / semester)
      // ===============================
      // if ($request->file('image')) {

      //     $image     = $request->file('image');
      //     $imageName = 'profile_pengguna_' . Str::random(5) . '.' . $image->extension();
      //     $image->move(public_path('images'), $imageName);



      // ===============================
      // FORMAT JUMLAH BAYAR
      // ===============================
      $jmlBayarFinal = 0;
      if (strpos($request->jml_bayar, "Rp") === 0) {
        $str = preg_replace('/[^0-9]/', '', $request->jml_bayar);
        $jmlBayarFinal = substr($str, 0, -2);
      } else {
        $jmlBayarFinal = $request->jml_bayar;
      }

      // ===============================
      // FORMAT KEWAJIBAN
      // ===============================
      $kewajibanFinal = 0;
      if (strpos($request->kewajiban, "Rp") === 0) {
        $str = preg_replace('/[^0-9]/', '', $request->kewajiban);
        $kewajibanFinal = substr($str, 0, -2);
      } else {
        $kewajibanFinal = $request->kewajiban;
      }

      // ===============================
      // POTONGAN (%)
      // ===============================
      $potongan = 0;
      if ($request->potongan > 0) {
        $potongan = ($request->potongan / 100) * $kewajibanFinal;
      }


        if($kewajibanFinal != $det_bayar->kwajiban_bayar){

            DB::table('tb_det_bayar')
            ->where('id_bayar', $id_bayar)
            ->where('id_item', $id_item)
            ->update(
              [

                'jml_bayar' => $jmlBayarFinal,
                'kwajiban_bayar' => $kewajibanFinal,
                'potongan' => $potongan,
                'id_user' => $userId,
              ]
            );

          $this->updatekwajiban($id_bayar);
          $this->updatebayar($id_bayar);

          DB::commit(); // ✅ WAJIB

          return response()->json([
              'status' => 'success',
              'message' => 'Berhasil diupdate'
          ]);
        }


      if ($item_bayar->id_kategori == 1 || $potongan > 0) {
        DB::table('tb_det_bayar')
          ->where('id_bayar', $id_bayar)
          ->where('id_item', $id_item)
          ->update(
            [

              'jml_bayar' => $jmlBayarFinal,
              'kwajiban_bayar' => $kewajibanFinal,
              'potongan' => $potongan,
              'id_user' => $userId,
            ]
          );

        $this->updatekwajiban($id_bayar);
        $this->updatebayar($id_bayar);

         DB::commit(); // ✅ WAJIB

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil diupdate'
        ]);
      }

      // ===============================
      // AMBIL ITEM SEMESTERAN
      // ===============================
      $id_item_s = DB::table('tb_itembayar')
        ->where('nama_item', 'like', '%semesteran%')
        ->first();

      // ===============================
      // LIST SEMESTER YANG SUDAH ADA
      // ===============================
      $semester = [];


      // $tunggakan_semester = DB::table('tb_bayar')
      //   ->select('id_semester')
      //   ->where('nim', $bayar->nim)
      //   ->groupBy('id_semester')
      //   ->orderBy('id_semester', 'asc')
      //   ->get();

      $tunggakan_semester = DB::table('tb_bayar as b')
      ->leftJoin('tb_det_bayar as d', 'b.id', '=', 'd.id_bayar')
      ->select(
        'b.nim',
        'b.id_semester',
        DB::raw('SUM(kwajiban_bayar - COALESCE(potongan, 0)) as total_kewajiban'),
        DB::raw('COALESCE(SUM(d.jml_bayar), 0) as total_bayar')
      )
      ->where('b.nim', $bayar->nim)
      ->groupBy('b.nim', 'b.id_semester')
      ->havingRaw('SUM(kwajiban_bayar - COALESCE(potongan, 0)) > COALESCE(SUM(d.jml_bayar), 0)')->get();

      foreach ($tunggakan_semester as $semRow) {
        if (!empty($semRow->id_semester)) {
          $semester[] = $semRow->id_semester;
        }
      }

      //  print_r('semester '. $jmlBayarFinal . "<br />");



      // ===============================
      // DATA BAYAR
      // ===============================


      // ===============================
      // PROSES KELEBIHAN BAYAR
      // ===============================
      $this->prosesKelebihanBayarEdit(
        $request,
        $id_item_s,
        $semester,
        null,
        $jmlBayarFinal,
        $jmlBayarFinal,
        $userId,
        '',
        0,
        $bayar->nim,
        $bayar->via,
        $bayar->tgl_bayar,
        $sem,

      );

      // ===============================
      // DELETE DETAIL & HEADER
      // ===============================

      $cek_bayar =    DB::table('tb_det_bayar')
        ->where('id_bayar', $id_bayar)
        ->where('id_item', $id_item)->first();
      if ($cek_bayar->jml_bayar > 0) {
        DB::table('tb_det_bayar')
          ->where('id_bayar', $id_bayar)
          ->where('id_item', $id_item)
          ->delete();

        DB::table('tb_bayar')
          ->where('id', $id_bayar)
          ->delete();
      }

      DB::commit();

      return response()->json([
        'status'  => 'success',
        'message' => 'Berhasil'
      ]);
    } catch (\Throwable $e) {

      DB::rollBack();

      return response()->json([
        'status'  => 'error',
        'message' => 'Terjadi kesalahan',
        'error'   => $e->getMessage() // hapus di production jika perlu
      ], 500);
    }
  }

 public function prosesKelebihanBayarEdit($request, $id_item_s, $smt_array, $newRecordID, $sisa_pembayaran, $semesteran_final, $userId, $imageName, $no, $nim, $via, $tgl_bayar,$sem_single)
  {

    $smt =  $smt_array;

  //  print_r('sisa_pembayaran ' . $sisa_pembayaran . "<br />");

   if (empty($smt[$no])) {

      $insertBayar = PembayaranModel::create([
            'nim' => $nim,
            'id_tahun' => date('Y'),
            'id_bulan' => date('m'),
            'id_semester' => $sem_single,
            'tgl_bayar' => $tgl_bayar,
            'id_kasir' => $userId,
            'via' => $via,
            // 'bukti_bayar' => asset('storage/app/private/' . $imageName),
            // 'no_kwitansi' => $request->no_kwitansi,
            // 'keterangan' => $request->comments,
            'created_at' => now(),
            'updated_at' => now(),
          ]);

            DB::table('tb_det_bayar')->insert([
            'id_bayar' => $insertBayar->id,
            'id_item' => $id_item_s->id,
            'jml_bayar' => $sisa_pembayaran,
            'id_user' => auth()->user()->id,
          ]);

           $this->updatekwajiban($insertBayar->id);
           $this->updatebayar($insertBayar->id);
     

      return;
    }


    // if ($sisa_pembayaran > 0 && empty($smt[$no])) {
    //   //return;
    //   $semester = [$this->cekSemesterTerakhirLunas($request->nim)];

    //   $this->prosesKelebihanBayarEdit(
    //     $request,
    //     $id_item_s,
    //     $semester,
    //     '',
    //     $sisa_pembayaran,
    //     '',
    //     $userId,
    //     $imageName,
    //     $no = 0,
    //     $nim,
    //     $via,
    //     $tgl_bayar,
    //     $sem_single,
    //   );

    //   return;
    // }

    $tunggakan_sem = 0;

    $tunggakan_sem = DB::table('tb_bayar')->select(
      'id_semester',
      DB::raw('SUM(kwajiban_bayar) as SUMK3'),
      DB::raw('SUM(potongan) as SUMP'),
      DB::raw('SUM(jml_bayar) as SUMB')
    )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')

      ->where('tb_bayar.id_semester',  $smt[$no])
      ->where('d.id_kategori', 2)
      ->where('tb_bayar.nim', $nim)
      ->groupBy('id_semester')
      ->first();

    //  print_r('semester '. $smt[$no] . "<br />");



    $sisa_tagihan_semesteran_next = ($tunggakan_sem->SUMK3 ?? 0) - ($tunggakan_sem->SUMP ?? 0) - ($tunggakan_sem->SUMB ?? 0);

    //print_r('sisa_tagihan_semesteran_next ' . $sisa_tagihan_semesteran_next . "<br />");


    // Tentukan berapa yang dibayar untuk semester berikutnya
    $bayar_untuk_next = min($sisa_pembayaran, max(0, $sisa_tagihan_semesteran_next));

    echo"<pre>";
     print_r('bayar_untuk_next smester ' . $bayar_untuk_next . ' = min(' . $sisa_pembayaran . ',' . $sisa_tagihan_semesteran_next . ") <br />");
    echo"</pre>";


   

   
    if ($bayar_untuk_next == 0) {

      $this->prosesKelebihanBayarEdit(
        $request,
        $id_item_s,
        $smt,
        '',
        $sisa_pembayaran,
        $bayar_untuk_next,
        $userId,
        '',
        $no + 1,
        $nim,
        $via,
        $tgl_bayar,
        $sem_single
      );

      return;
    }
    // $rute = $request->file('image')->storeAs('bukti-transaksi', $imageName, 'private');        

    $insertBayar = PembayaranModel::create([
      'nim' => $nim,
      'id_tahun' => date('Y'),
      'id_bulan' => date('m'),
      'id_semester' => sprintf("%02d", $smt[$no]),
      'tgl_bayar' => $tgl_bayar,
      'id_kasir' => $userId,
      'via' => $via,
      // 'bukti_bayar' => asset('storage/app/private/' . $imageName),
      // 'no_kwitansi' => $request->no_kwitansi,
      // 'keterangan' => $request->comments,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    $logs = [
      'tanggal' => now(),
      'tabel' => 'tb_bayar',
      'aksi' => 'create',
      'user' => auth()->user()->id,
      'ip' => $request->ip(),
      'keterangan' => json_encode([
        'nim' => $nim,
        'id_tahun' => date('Y'),
        'id_bulan' => date('m'),
        'id_semester' => sprintf("%02d", $smt[$no]),
        'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
        'id_kasir' => $userId,
        'via' => $via,
        //'bukti_bayar' => asset('storage/app/private/' . $imageName),
        'no_kwitansi' => $request->no_kwitansi,
        'keterangan' => $request->comments,
        'created_at' => now(),
        'updated_at' => now(),
      ]),
      'serial' => url('simpan') // Assuming you want to log the serial number
    ];
    LogModel::create($logs);

    // print_r('bayar_untuk_next ' . $bayar_untuk_next . "<br />");
    //print_r('bayar_untuk_next ' . $bayar_untuk_next .' = min('.$sisa_tagihan_semesteran.','.$sisa_tagihan_semesteran_next.") <br />");
    DB::table('tb_det_bayar')->insert([
      'id_bayar' => $insertBayar->id,
      'id_item' => $id_item_s->id,
      'jml_bayar' => $bayar_untuk_next,
      'id_user' => auth()->user()->id,
    ]);

    $logs2 = [
      'tanggal' => now(),
      'tabel' => 'tb_det_bayar',
      'aksi' => 'create',
      'user' => auth()->user()->id,
      'ip' => $request->ip(),
      'keterangan' => json_encode([
        'id_bayar' => $insertBayar->id,
        'id_item' => $id_item_s->id,
        'jml_bayar' => $bayar_untuk_next
      ]),
      'serial' => url('simpan') // Assuming you want to log the serial number
    ];
    LogModel::create($logs2);



    // Update semester berikutnya
    $this->updatekwajiban($insertBayar->id);
    $this->updatebayar($insertBayar->id);

    // Hitung kelebihan yang masih tersisa setelah membayar semester berikutnya
    $kelebihan_sisa = $sisa_pembayaran - $bayar_untuk_next;

    echo"<pre>";
    print_r('kelebihan_sisa  = ' . $kelebihan_sisa . ' = ' . $sisa_pembayaran . ' - ' . $bayar_untuk_next . "<br />");
        echo"</pre>";

    // Jika masih ada kelebihan, lanjutkan ke semester selanjutnya
    // dan semesteran array nya masih
    if ($kelebihan_sisa > 0) {
      $this->prosesKelebihanBayarEdit(
        $request,
        $id_item_s,
        $smt,
        '',
        $kelebihan_sisa,
        $bayar_untuk_next,
        $userId,
        '',
        $no + 1,
        $nim,
        $via,
        $tgl_bayar,
        $sem_single
      );

      // Jika masih ada kelebihan, lanjutkan ke DPP
      // dan semesteran array nya kosong
    }

    // elseif($kelebihan_sisa > 0 && empty($smt[$no])){


    // }
  }

  public function tagihan_periode_pdf(Request $request)
  {

    $tahun = $request->tahun; // <-- tambahkan ini
    $data['tahun'] = $tahun;

    $query = MahasiswaMbcModel::withoutGlobalScopes()
      ->from('tb_mbc_mahasiswa as a')
      ->leftJoin('tb_bayar as b', 'b.nim', '=', 'a.str_nim')
      ->leftJoin('tb_det_bayar as c', 'b.id', '=', 'c.id_bayar')
      ->leftJoin('tb_itembayar as d', 'c.id_item', '=', 'd.id')
      // ->leftJoin('tb_status_mahasiswa as e', 'e.nim', '=', 'a.str_nim')
      ->select(
        'b.nim',
        DB::raw('DATE(b.tgl_bayar) as tgl_bayar'),
        'c.id as id_det_bayar',
        DB::raw('MAX(a.bol_sts_kelas) as bol_sts_kelas'),

        DB::raw('MAX(nama_item) as nama_item'),

        DB::raw('MAX(a.str_hp_mhs) as str_hp_mhs'),
        DB::raw('MAX(a.str_angkatan) as str_angkatan'),
        DB::raw('MAX(str_nm_prodi) as str_nm_prodi'),
        DB::raw('MAX(a.str_nm_mhs) as str_nm_mhs'),
        DB::raw('MAX(b.id_semester) as id_semester'),
        DB::raw('SUM(tot_bayar) as SUMT1'),
        DB::raw('SUM(tot_kwajiban) as SUMK1'),
        DB::raw('max(jml_bayar) as SUMB'),
        DB::raw('SUM(jml_bayar) as jumlahBayar'),



        DB::raw('max(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP')
      )
      ->when($tahun, function ($q) use ($tahun) {
         $q->whereRaw('YEAR(b.tgl_bayar) = ?', [$tahun]);
// kalau ini berdasar angkatan bisa muncul semua di laporan 
     //           $q->where('a.str_angkatan', $tahun);

      })
          ->whereNull('a.deleted_at') // 🔥 INI PENTING
      ->groupBy('b.nim')
      ->groupBy(DB::raw('DATE(b.tgl_bayar)'))
      ->groupBy('c.id')
      // ->groupBy('a.str_angkatan')

      ->whereIn('d.id_kategori', [1, 2])
      ->orderBy('b.nim', 'asc')->orderBy('tgl_bayar', 'asc')->get();


    $tot = MahasiswaMbcModel::withoutGlobalScopes()
      ->from('tb_mbc_mahasiswa as a')
      ->leftJoin('tb_bayar as b', 'b.nim', '=', 'a.str_nim')
      ->leftJoin('tb_det_bayar as c', 'b.id', '=', 'c.id_bayar')
      ->leftJoin('tb_itembayar as d', 'c.id_item', '=', 'd.id')
      ->select(
        'b.nim',
        DB::raw('DATE(b.tgl_bayar) as tgl_bayar'),

        DB::raw('SUM(tot_bayar) as SUMT1'),
        DB::raw('SUM(tot_kwajiban) as SUMK1'),
        DB::raw('max(jml_bayar) as SUMB'),
        DB::raw('SUM(jml_bayar) as jumlahBayar'),
        DB::raw('max(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP')
      )
        ->whereNull('a.deleted_at') // 🔥 INI PENTING
       ->when($tahun, function ($q) use ($tahun) {

        // kalau ini berdasar angkatan bisa muncul semua di laporan hilangkan  $q->whereRaw('YEAR(b.tgl_bayar) = ?', [$tahun]);

         $q->whereRaw('YEAR(b.tgl_bayar) = ?', [$tahun]);
   
      })
      ->groupBy('b.nim')
      ->groupBy(DB::raw('DATE(b.tgl_bayar)'))
      ->groupBy('c.id')

      ->whereIn('d.id_kategori', [1, 2])
      ->orderBy('b.nim', 'asc')
      ->orderBy('tgl_bayar', 'asc')->get();


    $data['mhs'] = $query;

    $data['tot'] = $tot;


  //   return view('livewire.tagihan_periode_pdf.index', $data);


    $pdf = Pdf::loadView('livewire.tagihan_periode_pdf.index', $data);

    // Download file
    return $pdf->download('tagihan_periode.pdf');
  }


  public  function hapusSemester(Request $request)
  {
    $semester = $request->semester_id;
    $nim = $request->nim;

    $bayar = PembayaranModel::where('nim', $nim)->where('id_semester', $semester)->get();

    foreach ($bayar as $row) {

      DetailPembayaranModel::where('id_bayar', $row->id)->delete();
      PembayaranModel::where('id', $row->id)->delete();
    }

    $orphans = DB::table('tb_det_bayar')
      ->whereNotIn('id_bayar', function ($query) {
        $query->select('id')->from('tb_bayar');
      })
      //->limit(100) // optional
      ->delete();
  }

  public function simpanProsesBayar(Request $request, LogModel $log)
  {

    $userId = Auth::id();

    // item bayar pendaftaran, dpp ,semesteran

    // $sisa_tagihan_semesteran = $this->sisa_tagihan_nim($request->nim);
    Validator::make($request->all(), [
      'tgl_trans' => 'required',
      'image' => 'required',
    ]);

    $image = $request->file('image');
    $imageName = 'profile_pengguna_' . Str::random(5) . '.' . $image->extension();
    //$request->file('image')->move(public_path() . '/images/', $imageName);



    $pembayaran = $request->bayar;


    if ($pembayaran > 0 || $pembayaran != '') {

      $bayar = 0;
      if (strpos($request->bayar, "Rp") === 0) {
        $str = preg_replace('/[^0-9 -]/', '', $request->bayar);
        $bayar = substr($str, 0, -2);
      } else {
        $bayar = $request->bayar;
      }


      $id_item = [];

      $tunggakan_khs = DB::table('tb_bayar')->select(
        'id_item',
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP'),
        DB::raw('SUM(jml_bayar) as SUMB')
      )
        ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
        ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')

        ->where('tb_bayar.nim', $request->nim)
        ->where('d.id_kategori', 1)
        ->whereRaw('LOWER(d.nama_item) NOT LIKE ?', ['%dpp%'])
        ->groupBy('id_item')
        ->get();



      $total_kwajiban = $tunggakan_khs->sum('SUMK2');
      $total_potongan = $tunggakan_khs->sum('SUMP');
      $total_bayar    = $tunggakan_khs->sum('SUMB');

      $sisa_tagihan = $total_kwajiban - $total_potongan - $total_bayar;
      $first_semester = DB::table('tb_bayar')
        ->where('nim', $request->nim)
        ->orderBy('id_semester', 'asc')->first();

      $rute = $image->storeAs('bukti-transaksi', $imageName, 'private');

      //  echo "<pre>";
      //   print_r('sisa_tagihan '.$sisa_tagihan);
      //   echo "</pre>";


      if ($sisa_tagihan > 0) {
        foreach ($tunggakan_khs as $row) {

          $id_item[] = $row->id_item;
        }


        $this->prosesBayarTagihanKhususPertama($request, $id_item, $bayar, $userId, $rute, $no = 0, $first_semester->id_semester);
      } else {
        $mhs = MahasiswaMbcModel::where('str_nim', $request->nim)->first();

        $id_item_s = DB::table('tb_itembayar')
          ->where('str_angkatan', $mhs->str_angkatan)
          ->where('nama_item', 'like', '%semesteran%')
          ->first();

        $semester = [];

        // $tunggakan_sem = DB::table('tb_bayar')->select(
        //   'id_semester',
        // )
        //   ->where('tb_bayar.nim', $request->nim)
        //   ->groupBy('id_semester')
        //   ->get();

        $tahun_ajaran = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();


        $smt = DB::table('tb_status_mahasiswa')->where('nim', $request->nim)->where('id_thn_ajaran', $tahun_ajaran->id)->first();

        $jml_smt = (int) $smt->id_semester;




        for ($i = 1; $i <= $jml_smt; $i++) {

          $semester[] = $i;
        }




        $this->prosesKelebihanBayar(
          $request,
          $id_item_s,
          $semester,
          '',
          $bayar,
          '',
          $userId,
          $rute,
          $noSem = 0
        );
      }
    }


    return back()->with('success', 'Berhasil');
  }



  private function prosesKelebihanBayar($request, $id_item_s, $smt_array, $newRecordID, $sisa_pembayaran, $semesteran_final, $userId, $imageName, $no)
  {
    try {
      DB::transaction(function () use ($request, $id_item_s, $smt_array, $newRecordID, $sisa_pembayaran, $semesteran_final, $userId, $imageName, $no) {

        $smt = $smt_array;

        // echo"<pre>";    
        // print_r('smt' . $smt);
        // echo"</pre>";

        // Jika semester berikutnya kosong → arahkan ke DPP
        if ($sisa_pembayaran > 0 && empty($smt[$no])) {

          print_r('masuk dpp');
          $this->tunggakanDpp(
            $request,
            $userId,
            $imageName,
            $sisa_pembayaran,
            $id_item_s,
            $no
          );
          return;
        }

        // Cek tunggakan semester sekarang
        $tunggakan_sem = DB::table('tb_bayar')
          ->select(
            'id_semester',
            DB::raw('SUM(kwajiban_bayar) as SUMK2'),
            DB::raw('SUM(potongan) as SUMP'),
            DB::raw('SUM(jml_bayar) as SUMB')
          )
          ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
          ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
          ->where('tb_bayar.id_semester', $smt[$no])
          ->where('d.id_kategori', 2)
          ->where('tb_bayar.nim', $request->nim)
          ->groupBy('id_semester')
          ->first();

        $sisa_tagihan_semesteran_next = ($tunggakan_sem->SUMK2 ?? 0)
          - ($tunggakan_sem->SUMP ?? 0)
          - ($tunggakan_sem->SUMB ?? 0);

        // echo"<pre>";    
        // print_r('sisa_pembayaran' . $sisa_pembayaran);
        // echo"</pre>";    

        $bayar_untuk_next = min($sisa_pembayaran, max(0, $sisa_tagihan_semesteran_next));

        echo "<pre>";
        print_r('bayar_untuk_next' . $bayar_untuk_next . '= min(' . $sisa_pembayaran . ',' . max(0, $sisa_tagihan_semesteran_next) . ')');
        echo "</pre>";


        // Jika semester ini tidak ada tagihan, lanjutkan ke semester berikutnya
        if ($bayar_untuk_next == 0) {
          $this->prosesKelebihanBayar(
            $request,
            $id_item_s,
            $smt,
            '',
            $sisa_pembayaran,
            $bayar_untuk_next,
            $userId,
            $imageName,
            $no + 1
          );
          return;
        }

        // Simpan ke tabel tb_bayar
        $insertBayar = PembayaranModel::create([
          'nim' => $request->nim,
          'id_tahun' => date('Y'),
          'id_bulan' => date('m'),
          'id_semester' => sprintf("%02d", $smt[$no]),
          'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
          'id_kasir' => $userId,
          'via' => $request->via,
          'bank_penerima' => $request->bank_penerima,
          'bukti_bayar' => asset('storage/app/private/' . $imageName),
          'no_kwitansi' => $request->no_kwitansi,
          'ket_simple' => $request->comments,
          'created_at' => now(),
          'updated_at' => now(),
        ]);


        $logs = [
          'tanggal' => now(),
          'tabel' => 'tb_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'nim' => $request->nim,
            'id_tahun' => date('Y'),
            'id_bulan' => date('m'),
            'id_semester' => sprintf("%02d", $smt[$no]),
            'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
            'id_kasir' => $userId,
            'via' => $request->via,
            'bank_penerima' => $request->bank_penerima,
            'bukti_bayar' => asset('storage/app/private/' . $imageName),
            'no_kwitansi' => $request->no_kwitansi,
            'ket_simple' => $request->comments,
            'created_at' => now(),
            'updated_at' => now(),
          ]),
          'serial' => url('simpanProsesBayar') // Assuming you want to log the serial number
        ];
        LogModel::create($logs);

        // Simpan detail bayar
        DB::table('tb_det_bayar')->insert([
          'id_bayar' => $insertBayar->id,
          'id_item' => $id_item_s->id,
          'jml_bayar' => $bayar_untuk_next,
          'id_user' => auth()->user()->id,
        ]);

        $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $insertBayar->id,
            'id_item' => $id_item_s->id,
            'jml_bayar' => $bayar_untuk_next,
          ]),
          'serial' => url('simpanProsesBayar') // Assuming you want to log the serial number
        ];
        LogModel::create($logs2);

        // Update total kewajiban & pembayaran
        $this->updatekwajiban($insertBayar->id);
        $this->updatebayar($insertBayar->id);

        // Hitung kelebihan
        $kelebihan_sisa = $sisa_pembayaran - $bayar_untuk_next;
        print_r($kelebihan_sisa);
        // Jika masih ada sisa, lanjut ke semester berikutnya
        if ($kelebihan_sisa > 0) {
          $this->prosesKelebihanBayar(
            $request,
            $id_item_s,
            $smt,
            '',
            $kelebihan_sisa,
            $bayar_untuk_next,
            $userId,
            $imageName,
            $no + 1
          );
        }
      });
    } catch (\Exception $e) {
      // Simpan ke log_error di database

      // Log juga ke file laravel.log
      Log::error('Transaksi prosesKelebihanBayar gagal', [
        'nim' => $request->nim ?? null,
        'user_id' => $userId,
        'error' => $e->getMessage(),
      ]);
    }
  }



  public function tunggakanDpp($request, $userId, $imageName, $sisa_pembayaran, $id_item_s, $semester_next)
  {
    try {
      DB::transaction(function () use ($request, $userId, $imageName, $sisa_pembayaran, $id_item_s, $semester_next) {

        $item = DB::table('tb_bayar')->select(
          'id_item',
          DB::raw('SUM(kwajiban_bayar) as SUMK2'),
          DB::raw('SUM(potongan) as SUMP'),
          DB::raw('SUM(jml_bayar) as SUMB')
        )
          ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
          ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
          ->where('tb_bayar.id_semester', 1)
          ->where('tb_bayar.nim', $request->nim)
          ->whereRaw('LOWER(d.nama_item) LIKE ?', ['%dpp%'])
          ->groupBy('id_item')
          ->first();

        // Jika DPP belum ada sama sekali, keluar dengan aman
        if (!$item) {

          $semester = [$this->cekSemesterTerakhirLunas($request->nim)];

          $this->prosesKelebihanBayar(
            $request,
            $id_item_s,
            $semester,
            '',
            $sisa_pembayaran,
            '',
            $userId,
            $imageName,
            $no = 0
          );
          return;
        }

        $sisa_tagihan = ($item->SUMK2 ?? 0) - ($item->SUMP ?? 0) - ($item->SUMB ?? 0);
        $bayar_untuk_next = min($sisa_pembayaran, $sisa_tagihan);

        //    print_r('bayar_untuk_next '.$bayar_untuk_next);

        // Jika DPP sudah lunas → lanjut ke semester yang belum ada pembayaran
        if ($bayar_untuk_next == 0) {


          $semester = [$this->cekSemesterTerakhirLunas($request->nim)];

          $this->prosesKelebihanBayar(
            $request,
            $id_item_s,
            $semester,
            '',
            $sisa_pembayaran,
            '',
            $userId,
            $imageName,
            $no = 0
          );

          return;
        }

        // Simpan pembayaran DPP baru
        $insertBayar = PembayaranModel::create([
          'nim' => $request->nim,
          'id_tahun' => date('Y'),
          'id_bulan' => date('m'),
          'id_semester' => sprintf("%02d", 1),
          'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
          'id_kasir' => $userId,
          'via' => $request->via,
          'bank_penerima' => $request->bank_penerima,
          'bukti_bayar' => asset('storage/app/private/' . $imageName),
          'no_kwitansi' => $request->no_kwitansi,
          'ket_simple' => $request->comments,
          'created_at' => now(),
          'updated_at' => now(),
        ]);


        $logs = [
          'tanggal' => now(),
          'tabel' => 'tb_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'nim' => $request->nim,
            'id_tahun' => date('Y'),
            'id_bulan' => date('m'),
            'id_semester' => sprintf("%02d", 1),
            'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
            'id_kasir' => $userId,
            'via' => $request->via,
            'bank_penerima' => $request->bank_penerima,
            'bukti_bayar' => asset('storage/app/private/' . $imageName),
            'no_kwitansi' => $request->no_kwitansi,
            'keterangan' => $request->comments,
            'created_at' => now(),
            'updated_at' => now(),
          ]),
          'serial' => url('simpanProsesBayar') // Assuming you want to log the serial number
        ];
        LogModel::create($logs);

        // Detail DPP
        DB::table('tb_det_bayar')->insert([
          'id_bayar' => $insertBayar->id,
          'id_item' => $item->id_item,
          'jml_bayar' => $bayar_untuk_next,
        ]);


        $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $insertBayar->id,
            'id_item' => $item->id_item,
            'jml_bayar' => $bayar_untuk_next,
          ]),
          'serial' => url('simpanProsesBayar') // Assuming you want to log the serial number
        ];
        LogModel::create($logs2);

        // Update kewajiban & pembayaran
        $this->updatekwajiban($insertBayar->id);
        $this->updatebayar($insertBayar->id);

        // Hitung sisa uang
        $kelebihan_sisa = $sisa_pembayaran - $bayar_untuk_next;

        // Jika masih ada sisa → lanjut ke semester berikutnya
        if ($kelebihan_sisa > 0) {

          $semester = [$this->cekSemesterTerakhirLunas($request->nim,)];

          $this->prosesKelebihanBayar(
            $request,
            $id_item_s,
            $semester,
            '',
            $kelebihan_sisa,
            '',
            $userId,
            $imageName,
            $no = 0
          );
        }
      });
    } catch (\Exception $e) {
      // Simpan ke log_error di database


      // Simpan juga ke file log Laravel
      Log::error('Transaksi tunggakanDpp gagal', [
        'nim' => $request->nim ?? null,
        'user_id' => $userId,
        'error' => $e->getMessage(),
      ]);
    }
  }




  public function prosesBayarTagihanKhususPertama($request, $id_item_array, $sisa_pembayaran, $userId, $imageName, $no, $first_semester)
  {
    try {
      DB::beginTransaction();

      $id_item = $id_item_array;

      // Ambil data semester berikutnya
      $tunggakan = DB::table('tb_bayar')->select(
        'id_item',
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP'),
        DB::raw('SUM(jml_bayar) as SUMB')
      )
        ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
        ->where('tb_bayar.id_semester', $first_semester)
        ->where('tb_bayar.nim', $request->nim)
        ->where('id_item', $id_item[$no])
        ->groupBy('id_item')
        ->first();

      $sisa_tagihan = ($tunggakan->SUMK2 ?? 0) - ($tunggakan->SUMP ?? 0) - ($tunggakan->SUMB ?? 0);

      $bayar_untuk_next = min($sisa_pembayaran, $sisa_tagihan);

      if ($bayar_untuk_next == 0) {
        $this->prosesBayarTagihanKhususPertama(
          $request,
          $id_item_array,
          $sisa_pembayaran,
          $userId,
          $imageName,
          $no + 1,
          $first_semester
        );
        DB::commit();
        return;
      }

      $insertBayar = PembayaranModel::create([
        'nim' => $request->nim,
        'id_tahun' => date('Y'),
        'id_bulan' => date('m'),
        'id_semester' => sprintf("%02d", 1),
        'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
        'id_kasir' => $userId,
        'via' => $request->via,
        'bank_penerima' => $request->bank_penerima,
        'bukti_bayar' => asset('storage/app/private/' . $imageName),
        'no_kwitansi' => $request->no_kwitansi,
        'keterangan' => $request->comments,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      $logs = [
        'tanggal' => now(),
        'tabel' => 'tb_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'nim' => $request->nim,
          'id_tahun' => date('Y'),
          'id_bulan' => date('m'),
          'id_semester' => sprintf("%02d", 1),
          'tgl_bayar' => date('Y-m-d H:i:s', strtotime($request->tgl_trans)),
          'id_kasir' => $userId,
          'via' => $request->via,
          'bank_penerima' => $request->bank_penerima,
          'bukti_bayar' => asset('storage/app/private/' . $imageName),
          'no_kwitansi' => $request->no_kwitansi,
          'keterangan' => $request->comments,
          'created_at' => now(),
          'updated_at' => now(),
        ]),
        'serial' => url('simpanProsesBayar') // Assuming you want to log the serial number
      ];
      LogModel::create($logs);

      DB::table('tb_det_bayar')->insert([
        'id_bayar' => $insertBayar->id,
        'id_item' => $id_item[$no],
        'jml_bayar' => $bayar_untuk_next,
      ]);


      $logs2 = [
        'tanggal' => now(),
        'tabel' => 'tb_det_bayar',
        'aksi' => 'create',
        'user' => auth()->user()->id,
        'ip' => $request->ip(),
        'keterangan' => json_encode([
          'id_bayar' => $insertBayar->id,
          'id_item' => $id_item[$no],
          'jml_bayar' => $bayar_untuk_next
        ]),
        'serial' => url('simpanProsesBayar') // Assuming you want to log the serial number
      ];
      LogModel::create($logs2);

      $this->updatekwajiban($insertBayar->id);
      $this->updatebayar($insertBayar->id);

      $kelebihan_sisa = $sisa_pembayaran - $bayar_untuk_next;

      $tunggakan_khs = DB::table('tb_bayar')->select(
        'id_item',
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP'),
        DB::raw('SUM(jml_bayar) as SUMB')
      )
        ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
        ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
        ->where('tb_bayar.nim', $request->nim)
        ->where('d.id_kategori', 1)
        ->whereRaw('LOWER(d.nama_item) NOT LIKE ?', ['%dpp%'])
        ->groupBy('id_item')
        ->get();

      $total_kwajiban = $tunggakan_khs->sum('SUMK2');
      $total_potongan = $tunggakan_khs->sum('SUMP');
      $total_bayar    = $tunggakan_khs->sum('SUMB');

      $sisa_tagihan_khs = $total_kwajiban - $total_potongan - $total_bayar;

      if ($kelebihan_sisa > 0 && $sisa_tagihan_khs > 0) {
        $this->prosesBayarTagihanKhususPertama(
          $request,
          $id_item_array,
          $kelebihan_sisa,
          $userId,
          $imageName,
          $no + 1,
          $first_semester
        );
      } elseif ($kelebihan_sisa > 0 && $sisa_tagihan_khs == 0) {
        $mhs = MahasiswaMbcModel::where('str_nim', $request->nim)->first();

        $id_item_s = DB::table('tb_itembayar')
          ->where('str_angkatan', $mhs->str_angkatan)
          ->where('nama_item', 'like', '%semesteran%')
          ->first();

        $semester = [];

        $tunggakan_sem = DB::table('tb_bayar')
          ->select('id_semester')
          ->where('tb_bayar.nim', $request->nim)
          ->groupBy('id_semester')
          ->get();

        $tahun_ajaran = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();


        $smt = DB::table('tb_status_mahasiswa')->where('nim', $request->nim)->where('id_thn_ajaran', $tahun_ajaran->id)->first();

        $jml_smt = (int) $smt->id_semester;

        for ($i = 1; $i <= $jml_smt; $i++) {
          $semester[] = $i;
        }

        $this->prosesKelebihanBayar(
          $request,
          $id_item_s,
          $semester,
          $insertBayar->id,
          $kelebihan_sisa,
          $bayar_untuk_next,
          $userId,
          $imageName,
          $no = 0
        );
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error prosesBayarTagihanKhususPertama', [
        'message' => $e->getMessage(),
        'nim' => $request->nim ?? null,
        'trace' => $e->getTraceAsString(),
      ]);
      throw $e; // biar bisa ditangkap handler global kalau perlu
    }
  }


  public function importCsv(Request $request)
  {
    $request->validate([
      'csv_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('csv_file');
    $rowCount = 0;

    if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
      // Ambil header CSV (baris pertama)
      $header = fgetcsv($handle, 1000, ',');

      // Loop setiap baris CSV
      while (($data = fgetcsv($handle, 1000, ',')) !== false) {

        $tglBayar = null;
        if (!empty(trim($data[5])) && strtolower(trim($data[5])) !== 'null') {
          try {
            $tglBayar = \Carbon\Carbon::parse($data[5])->format('Y-m-d H:i:s');
          } catch (\Exception $e) {
            $tglBayar = null;
          }
        }

        // $row->str_nim,
        //                     $row->str_angkatan,
        //                     $row->str_jurusan,
        //                     $row->str_alamat,
        //                     $row->str_hp_mhs,
        //                     $row->str_imel_mhs,
        //                     $row->str_campus,
        //                     $row->bol_pjj,
        //                     $row->bol_karyawan,
        //                     $row->str_jurusan,
        //                     $row->bol_jimbaran,
        //                     $row->bol_lulus,
        //                     $row->str_kd_kelas, 
        //                     $row->str_nm_prodi,
        //                     $row->bol_sts_kelas, 
        //                     $row->str_nm_mhs,


        // Sesuaikan urutan kolom CSV dengan struktur tabel kamu
        DB::table('tb_mbc_mahasiswa')->insert([
          // 'id'             => $data[0] ?? null,
          //'id_tahun'            => !empty($data[1]) ? $data[1] : null,
          'str_nim'        => !empty($data[0]) ? $data[0] : null,
          'str_angkatan'         => !empty($data[1]) ? $data[1] : null,
          'str_jurusan'         => !empty($data[2]) ? $data[2] : null,
          'str_alamat'       => !empty($data[3]) ? $data[3] : null,
          'str_hp_mhs'       => !empty($data[4]) ? $data[4] : null,
          'str_imel_mhs'      => !empty($data[5]) ? $data[5] : null,
          'str_campus'      => !empty($data[6]) ? $data[6] : null,
          'bol_pjj'      => !empty($data[7]) ? $data[7] : null,
          'bol_karyawan'  => !empty($data[8]) ? $data[8] : null,
          'str_jurusan'  => !empty($data[9]) ? $data[9] : null,
          'bol_jimbaran'        => !empty($data[10]) ? $data[10] : null,
          'bol_lulus'    => !empty($data[11]) ? $data[11] : null,
          'str_kd_kelas'    => !empty($data[12]) ? $data[12] : null,
          'str_nm_prodi'    => !empty($data[13]) ? $data[13] : null,
          'bol_sts_kelas'    => !empty($data[14]) ? $data[14] : null,
          'str_nm_mhs'    => !empty($data[15]) ? $data[15] : null,
          // 'str_nm_mhs'    => !empty($data[13]) ? $data[13] : null,
          // 'bol_sts_kelas'    => !empty($data[14]) ? $data[14] : null,

          //  'sts_trans'      => $data[6] ?? 0,

        ]);

        $rowCount++;
      }

      fclose($handle);
    }

    return back()->with('success', "Import CSV berhasil! Total data: $rowCount");
  }

  public function setSemesterMahasiswa(Request $request)
  {


    try {
      DB::beginTransaction();

      $data = $request->data; // array dari frontend

      foreach ($data as $item) {
        $nim = $item['nim'];
        $semester = $item['semester'];

        // update
        DB::table('tb_status_mahasiswa')
          ->where('nim', $nim)
          ->update([
            'id_semester' => str_pad($semester, 2, '0', STR_PAD_LEFT),
            'updated_at' => now()
          ]);
      }

      DB::commit();

      return response()->json([
        'status' => true,
        'message' => 'Berhasil update semester'
      ]);
    } catch (\Exception $e) {

      DB::rollBack();

      return response()->json([
        'status' => false,
        'message' => 'Terjadi kesalahan',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function setSemesterNim(Request $request)
  {


    try {
      DB::beginTransaction();

      $data = $request->data; // array dari frontend


      $nim = $request->nim;
      $semester = $request->semester;

      // update
      DB::table('tb_status_mahasiswa')
        ->where('nim', $nim)
        ->update([
          'id_semester' => str_pad($semester, 2, '0', STR_PAD_LEFT),
          'updated_at' => now()
        ]);


      DB::commit();

      return response()->json([
        'status' => true,
        'message' => 'Berhasil update semester'
      ]);
    } catch (\Exception $e) {

      DB::rollBack();

      return response()->json([
        'status' => false,
        'message' => 'Terjadi kesalahan',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  
   public function hapusGambar(Request $request)
    {
        $id = $request->id;

        try {
            DB::beginTransaction();
            
            //  $data = PembayaranModel::findOrFail($id);
                
                
            //     if (!empty($data->bukti_bayar)) {
            //       Storage::disk('private')
            //         ->delete('bukti-transaksi/'.$data->bukti_bayar);
                    
            //          Storage::disk('private')
            //         ->delete($data->bukti_bayar);
            //     }
            
            DB::table('tb_bayar')->where('id',$id)->update(['bukti_bayar' => '']);

            Log::info('User login', [
                'user_id' => auth()->id(),
                'email'   => Auth::user()->email,
               // 'serial'  => $this->serial,
                'tabel' => 'tb_bayar',
                'aksi' => 'create',
                'data' => $request->all(),
                'url' => url('hapusGambar') . '/' . $id
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data pembayaran', [
                'error' => $e->getMessage(),
                'nim'   => $id
            ]);

            // Kalau mau lempar lagi errornya
            throw $e;
        }
    }
    
     public function export_pembayaran(Request $request, LogModel $log, $id)
  {

    $serial = $_SERVER['HTTP_USER_AGENT'];
    $userId = Auth::id();
    DB::transaction(function () use ($request, $id, $userId, $serial, $log) {

      // item bayar pendaftaran, dpp ,semesteran

      // $sisa_tagihan_semesteran = $this->sisa_tagihan_nim($request->nim);
      // $this->validate([
      //   //  'semester' => 'required',
      //   'tgl_trans' => 'required',
      //   'image' => 'required',
      // ]);

      $status = $request->status;


      DB::table('tb_ipaymu_transaction')
        ->where('id_bayar', $id)
        ->update([
         // 'status'           => 'success',
         // 'callback_payload' => json_encode($request->all()),
          'paid_at'          => now(),
          'updated_at'       => now()
        ]);




      $order = DB::table('tb_ipaymu_bayar')->where('id', $id)->first();
      $details = DB::table('tb_ipaymu_det_bayar')
        ->where('id_bayar', $order->id)
        ->first();


      $first_semester = DB::table('tb_bayar')
        ->where('nim', $order->nim)
        ->orderBy('id_semester', 'asc')->first();

      $smt = $first_semester->id_semester;
      $mbc_pay;

      $item_bayar = $request->item;


      //if($sisa_tagihan_semesteran <= $request->semesteran  ){
      if (($details->nama_item == 'pendaftaran') || ($details->nama_item == 'dpp') || ($details->nama_item == 'jaket')) {


        //  print_r('masuk sini');
        //   if ($request->file('image') != null) {
        $mbc_pay =  PembayaranModel::create(
          [
            'nim' => $order->nim,
            'id_tahun' => date('Y'),
            'id_bulan' => date('m'),
            'id_semester' => $smt,
            'tgl_bayar' => date('Y-m-d H:i:s', strtotime($order->tgl_bayar)),
            'id_kasir' => $order->id_kasir,
            'via' => 10,
            'sts_bayar' => 1,
            'no_kwitansi' => $order->no_kwitansi,
            'keterangan' => 'Via Ipaymu',
            'ket_simple' => 'Via Ipaymu',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ]
        );

        // $this->log->log('tb_bayar', 'insert', json_encode($request->all()), $serial, Auth::user()->email);

        Log::info('User login', ['user_id' => auth()->id(), 'email' => Auth::user()->email, 'serial' => $serial, 'table' => 'tb_bayar', 'data' => $request->all(), 'aksi' => 'create', 'url' => url('simpan')]);
        $logs2 = [
          'tanggal' => now(),
          'tabel' => 'tb_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'nim' => $order->nim,
            'id_tahun' => date('Y'),
            'id_bulan' => date('m'),
            'id_semester' => $smt,
            'tgl_bayar' => date('Y-m-d H:i:s', strtotime($order->tgl_bayar)),
            'id_kasir' => $order->id_kasir,
            'via' => 10,
            'sts_bayar' => 1,
            'no_kwitansi' => $order->no_kwitansi,
            'keterangan' => 'Via Ipaymu',
            'ket_simple' => 'Via Ipaymu',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ]),
          'serial' => url('simpan') // Assuming you want to log the serial number
        ];
        $log->create($logs2);
        //   }
      }



      $mhs = MahasiswaMbcModel::where('str_nim', $order->nim)->first();




      if ($details->nama_item == 'pendaftaran') {

        $daftar = 0;



        $id_item_p = DB::table('tb_itembayar')
          ->where('str_angkatan', $mhs->str_angkatan)
          ->where('nama_item', 'like', '%Pendaftaran%')
          ->first();


        DB::table('tb_det_bayar')->insert(
          [
            'id_bayar' => $mbc_pay->id,
            'id_item' => $id_item_p->id,
            'jml_bayar' => $details->jml_bayar,
            'id_user' => auth()->user()->id,
          ]
        );

        $logs = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $mbc_pay->id,
            'id_item' => $id_item_p->id,
            'jml_bayar' => $details->jml_bayar
          ]),
          'serial' => url('simpan') // Assuming you want to log the serial number
        ];
        $log->create($logs);
      }

      if ($details->nama_item == 'jaket') {

        $jaket = 0;



        $id_item_j = DB::table('tb_itembayar')
          ->where('str_angkatan', $mhs->str_angkatan)
          ->where(function ($q) {
            $q->where('nama_item', 'like', '%Jaket%')
              ->orWhere('nama_item', 'like', '%Kaos%')
              ->orWhere('nama_item', 'like', '%Almamater%');
          })
          ->first();

        DB::table('tb_det_bayar')->insert(
          [
            'id_bayar' => $mbc_pay->id,
            'id_item' => $id_item_j->id,
            'jml_bayar' => $details->jml_bayar,
            'id_user' => auth()->user()->id,
          ]
        );

        $logs = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $mbc_pay->id,
            'id_item' => $id_item_j->id,
            'jml_bayar' => $details->jml_bayar
          ]),
          'serial' => url('simpan') // Assuming you want to log the serial number
        ];
        $log->create($logs);
      }

      if ($details->nama_item == 'dpp') {
        $dpp_final = 0;




        $id_item_d = DB::table('tb_itembayar')
          ->where('str_angkatan', $mhs->str_angkatan)
          ->where('nama_item', 'like', '%dpp%')
          ->first();

        DB::table('tb_det_bayar')->insert(
          [
            'id_bayar' => $mbc_pay->id,
            'id_item' => $id_item_d->id,
            'jml_bayar' => $details->jml_bayar,
            'id_user' => auth()->user()->id,
          ]
        );

        $logs = [
          'tanggal' => now(),
          'tabel' => 'tb_det_bayar',
          'aksi' => 'create',
          'user' => auth()->user()->id,
          'ip' => $request->ip(),
          'keterangan' => json_encode([
            'id_bayar' => $mbc_pay->id,
            'id_item' => $id_item_d->id,
            'jml_bayar' => $dpp_final
          ]),
          'serial' => url('simpan') // Assuming you want to log the serial number
        ];
        $log->create($logs);
      }


      //==========================final Eksekutif


      if ($details->nama_item == 'semesteran') {

        $semesteran_final = $details->jml_bayar;

        $id_item_s = DB::table('tb_itembayar')
          ->where('str_angkatan', $mhs->str_angkatan)
          ->where('nama_item', 'like', '%semesteran%')
          ->first();


        $semester = [];

        //if($request->semester != 0){
        $tunggakan_semester = DB::table('tb_bayar')->select(
          'id_semester',
        )
          ->where('tb_bayar.nim', $order->nim)
          ->orderBy('id_semester', 'asc')
          ->groupBy('id_semester')
          ->get();

        foreach ($tunggakan_semester as $sem) {
          if (!empty($sem->id_semester)) {
            $semester[] = $sem->id_semester;
          }
        }
        //   }else{

        //       $semester = $request->semester;
        //   }

        $status = $this->cekTunggakanKhusus($request->nim);

        //   if ($status == 'belum_lunas') {
        //     return back()->with('error', 'Terdapat Tunggakan Khusus untuk Almamater dan Pendaftaran. Mohon lunasi terlebih dahulu tunggakan tersebut.');
        //   }
        $this->prosesKelebihanBayarIpaymu(
          $order->nim,
          $id_item_s,
          $semester,
          $mbc_pay->id ?? null,
          $semesteran_final,
          $order->tgl_bayar,
          $userId,
          'Pembayaran Ipaymu',
          $no = 0
        );
      }



      if (($details->nama_item == 'pendaftaran') || ($details->nama_item == 'dpp') || ($details->nama_item == 'jaket')) {

        $this->updatekwajiban($mbc_pay->id);
        $this->updatebayar($mbc_pay->id);
      }

      DB::table('tb_ipaymu_bayar')
        ->where('id', $order->id)
        ->update([
          'sts_bayar' => 1,
          'updated_at' => now()
        ]);

      // } // end of if($sisa_tagihan_semesteran <= $request->semesteran  ){

    });
    return back()->with('success', 'Berhasil');
  }


  public function prosesKelebihanBayarIpaymu(
    $nim,
    $id_item_s,
    $smt_array,
    $newRecordID,
    $sisa_pembayaran,
    $tgl_bayar,
    $userId,
    $ket_simple,
    $no
  ) {
    DB::beginTransaction();

    try {

      $this->prosesKelebihanBayarIpaymuInternal(
        $nim,
        $id_item_s,
        $smt_array,
        $newRecordID,
        $sisa_pembayaran,
        $tgl_bayar,
        $userId,
        $ket_simple,
        $no
      );

      DB::commit();
      return true;
    } catch (\Throwable $e) {

      DB::rollBack();

      Log::error('Error prosesKelebihanBayarIpaymu', [
        'nim' => $nim,
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ]);

      throw $e; // penting agar error tidak diam-diam
    }
  }

  private function prosesKelebihanBayarIpaymuInternal(
    $nim,
    $id_item_s,
    $smt_array,
    $newRecordID,
    $sisa_pembayaran,
    $tgl_bayar,
    $userId,
    $ket_simple,
    $no
  ) {

    $smt = $smt_array;

    if ($sisa_pembayaran > 0 && empty($smt[$no])) {
      $semester = [$this->cekSemesterTerakhirLunas($nim)];

      $this->prosesKelebihanBayarIpaymuInternal(
        $nim,
        $id_item_s,
        $semester,
        '',
        $sisa_pembayaran,
        $tgl_bayar,
        $userId,
        $ket_simple,
        0
      );
      return;
    }

    $tunggakan_sem = DB::table('tb_bayar')
      ->select(
        'id_semester',
        DB::raw('SUM(kwajiban_bayar) as SUMK2'),
        DB::raw('SUM(potongan) as SUMP'),
        DB::raw('SUM(jml_bayar) as SUMB')
      )
      ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
      ->join('tb_itembayar as d', 'tb_det_bayar.id_item', '=', 'd.id')
      ->where('tb_bayar.id_semester', $smt[$no])
      ->where('d.id_kategori', 2)
      ->where('tb_bayar.nim', $nim)
      ->groupBy('id_semester')
      ->first();

    $sisa_tagihan_semesteran_next =
      ($tunggakan_sem->SUMK2 ?? 0)
      - ($tunggakan_sem->SUMP ?? 0)
      - ($tunggakan_sem->SUMB ?? 0);

    $bayar_untuk_next = min($sisa_pembayaran, max(0, $sisa_tagihan_semesteran_next));

    if ($bayar_untuk_next == 0) {
      $this->prosesKelebihanBayarIpaymuInternal(
        $nim,
        $id_item_s,
        $smt,
        '',
        $sisa_pembayaran,
        $tgl_bayar,
        $userId,
        $ket_simple,
        $no + 1
      );
      return;
    }

    $insertBayar = PembayaranModel::create([
      'nim' => $nim,
      'id_tahun' => date('Y'),
      'id_bulan' => date('m'),
      'id_semester' => sprintf("%02d", $smt[$no]),
      'tgl_bayar' => date('Y-m-d H:i:s', strtotime($tgl_bayar)),
      'id_kasir' => $userId,
      'via' => 10,
      'ket_simple' => $ket_simple,
    ]);

    DB::table('tb_det_bayar')->insert([
      'id_bayar' => $insertBayar->id,
      'id_item' => $id_item_s->id,
      'jml_bayar' => $bayar_untuk_next,
      'id_user' => auth()->user()->id,
    ]);

    $this->updatekwajiban($insertBayar->id);
    $this->updatebayar($insertBayar->id);

    $kelebihan_sisa = $sisa_pembayaran - $bayar_untuk_next;

    if ($kelebihan_sisa > 0) {
      $this->prosesKelebihanBayarIpaymuInternal(
        $nim,
        $id_item_s,
        $smt,
        '',
        $kelebihan_sisa,
        $tgl_bayar,
        $userId,
        $ket_simple,
        $no + 1
      );
    }
  }
  
    public function simpanDokumen(Request $request,LogModel $log){
        
    
    //  $request->validate([
    //          'nim' => 'required|string',
    //         'nama_dokumen.*' => 'required|string|max:200',
    //         'dokumen.*' => 'required|file|mimes:csv,txt|max:5120'
    //     ]);
        
        
        Validator::make($request->all(), [
           'nim' => 'required|string',
            'nama_dokumen.*' => 'required|string|max:200',
            'dokumen.*' => 'required|file|mimes:csv,txt|max:5120'
    ]);

        DB::beginTransaction();

        try {
            $namaDokumen = $request->nama_dokumen;
            
            
            $nim = $request->nim;

            foreach ($request->file('dokumen') as $index => $file) {

                // Nama file aman & unik
                $namaFile = time() . '_' . Str::random(6).'_'.$nim.'.' .$file->getClientOriginalExtension();

                // Simpan file ke storage
                //$file->storeAs('public/dokumen', $namaFile);
                
                $rute = $file->storeAs('dokumen', $namaFile, 'private');
                
                
                $absolutePath = storage_path('app/private/' . $rute);
                
                $data =  [
                    'nim'           => $request->nim,
                    'nama_dokumen'  => $namaDokumen[$index],
                    'file'  =>  $namaFile,
                    'created_at'    => now(),
                    'updated_at'    => now()
                ];

                // Simpan ke database
                DB::table('tb_dokumen')->insert($data);
                
                
                
                 $logs = [
                            'tanggal' => now(),
                            'tabel' => 'tb_dokumen',
                            'aksi' => 'create',
                            'user' => auth()->user()->id,
                            'ip' => $request->ip(),
                            'keterangan' => json_encode($data),
                            'serial' => url('simpanDokumen') // Assuming you want to log the serial number
                          ];
                          $log->create($logs);
            }

            DB::commit();
            return back()->with('success', 'Dokumen berhasil diupload');

        } catch (\Exception $e) {
          //  DB::rollBack();
            
          // print_r($e->getMessage());
          //    dd($e->getMessage(), $e->getTraceAsString());
            return back()->with('error', 'Gagal upload dokumen: ' . $e->getMessage());
        }
        
    }
    
    public function download($id)
    {
        $dok = DB::table('tb_dokumen as a')->leftJoin('tb_mbc_mahasiswa as b','a.nim','=','b.str_nim')->where('a.id', $id)->first();
    
        if (!$dok) {
            abort(404, 'Dokumen tidak ditemukan');
        }
    
        $path = 'dokumen/' . $dok->file;
    
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File tidak ada di storage');
        }
    
        return Storage::disk('private')->download(
            $path,
            $dok->nama_dokumen .'_'.$dok->nim.'-'.$dok->str_nm_mhs.'.' . pathinfo($dok->file, PATHINFO_EXTENSION)
        );
    }
    
    public function hapusDokumen($id)
    {
        
        DB::beginTransaction();

            try {
                // Ambil data dokumen
                $dok = DB::table('tb_dokumen')->where('id', $id)->first();
        
                if (!$dok) {
                    return back()->with('error', 'Dokumen tidak ditemukan');
                }
        
                // Hapus file fisik (disk private)
                $path = 'dokumen/' . $dok->file;
        
                if (Storage::disk('private')->exists($path)) {
                    Storage::disk('private')->delete($path);
                }
        
                // Hapus data database
                DB::table('tb_dokumen')->where('id', $id)->delete();
        
                DB::commit();
                return back()->with('success', 'Dokumen berhasil dihapus');
        
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
            }

        
    }

    public function kwitansi($id)
    {
        $bayar = PembayaranModel::with('mahasiswa')->where('nim',$id)->first();

       $terakhir = PembayaranModel::with('mahasiswa')->where('nim', $id)
          ->latest('id')
          ->first();

      $totalBayar = 0;

      if ($terakhir) {

      $tanggal = date('Y-m-d', strtotime($terakhir->tgl_bayar));
          $totalBayar = PembayaranModel::where('nim', $id)
              ->whereDate('tgl_bayar', $tanggal)
              ->sum('tot_bayar');
      }

        // $pdf = Pdf::loadView('livewire.keuangan_mbc.kwitansi', [
        //     'bayar' => $bayar
        // ]);

         $nominal = $totalBayar;

                $terbilang = $this->terbilang($nominal);

        $pdf = Pdf::loadView('livewire.keuangan_mbc.kwitansi', [
            'bayar'      => $terakhir,
            'terbilang'  => $terbilang,
            'dibayar'  => $totalBayar
        ]);

        // Ukuran kertas A4
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'kwitansi-' . $bayar->no_kwitansi .'_'.$id.'.pdf'
        );
    }

     private function terbilang($angka)
    {
        $angka = abs((int) $angka);

        $bilangan = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas'
        ];

        if ($angka < 12) {
            return $bilangan[$angka];
        }

        if ($angka < 20) {
            return $this->terbilang($angka - 10) . ' belas';
        }

        if ($angka < 100) {
            return $this->terbilang(intdiv($angka, 10))
                . ' puluh '
                . $this->terbilang($angka % 10);
        }

        if ($angka < 200) {
            return 'seratus ' . $this->terbilang($angka - 100);
        }

        if ($angka < 1000) {
            return $this->terbilang(intdiv($angka, 100))
                . ' ratus '
                . $this->terbilang($angka % 100);
        }

        if ($angka < 2000) {
            return 'seribu ' . $this->terbilang($angka - 1000);
        }

        if ($angka < 1000000) {
            return $this->terbilang(intdiv($angka, 1000))
                . ' ribu '
                . $this->terbilang($angka % 1000);
        }

        if ($angka < 1000000000) {
            return $this->terbilang(intdiv($angka, 1000000))
                . ' juta '
                . $this->terbilang($angka % 1000000);
        }

        if ($angka < 1000000000000) {
            return $this->terbilang(intdiv($angka, 1000000000))
                . ' miliar '
                . $this->terbilang($angka % 1000000000);
        }

        if ($angka < 1000000000000000) {
            return $this->terbilang(intdiv($angka, 1000000000000))
                . ' triliun '
                . $this->terbilang($angka % 1000000000000);
        }

        return 'angka terlalu besar';
    }


    
    
}
