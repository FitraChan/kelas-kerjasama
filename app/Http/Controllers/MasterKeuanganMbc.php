<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keuangan_model;
use App\Models\Status_model;
use App\Models\PembayaranModel;
use App\Models\DetailPembayaranModel;
use App\Models\MhsModel;
use App\Models\MahasiswaMbcModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\LogModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use DataTables;

class MasterKeuanganMbc extends Controller
{
    //

    protected $serial;

    public function __construct()
    {
        $this->serial = $_SERVER['HTTP_USER_AGENT'];
    }
    public function index(Request $request)
    {

        $status = Status_model::pluck('ket_status', 'id');

        $angkatan = MahasiswaMbcModel::select('str_angkatan')->whereRaw('str_angkatan > 2017')->groupBy('str_angkatan')->orderBy('str_angkatan', 'asc')->get();
        $jurusan = DB::table('prodi')->get();

        $item_bayar = DB::table('tb_itembayar')->get();
        return view('livewire.master_keuangan_mbc', compact('status', 'angkatan', 'jurusan', 'item_bayar'));
    }

    public function create_datatable_master_mbc(Request $request)
    {


        $cari = !empty($request->cari) ? $request->cari : '%';
        $kelas = $request->kelas ? $request->kelas : '%';
        $hasil = MahasiswaMbcModel::where('bol_lulus', 0)
            ->where('str_nm_mhs', 'like', '%' . $cari . '%')
            ->where('bol_sts_kelas', 5)
            ->orWhere('bol_sts_kelas', 7)
            ->where('bol_sts_kelas', 'like', $kelas)
            ->Where('str_nim', 'like', $cari)->get();
        return datatables()->of($hasil)
            ->addIndexColumn()->addColumn('action', function ($row) {

                //$smt = DB::table('t_status_mahasiswa')->select(DB::raw('count(str_nim) as jml'))->where('str_nim',$row->str_nim)->first();
                $btn = '<button class="btn btn-sm btn-success" id="btn-detail" data="' . $row->str_nim . '" onclick="edit(' . $row->str_nim . ')">Edit</button>


                  ';
                return $btn;
            })
            ->make(true);
    }

    public function hisBayar($id, Request $request)
    {
        $semester = $request->input('semester'); // ambil dari request

        $query = PembayaranModel::with('detailBayar.itemBayar')
            ->where('nim', $id)
            ->when(!empty($semester), function ($q) use ($semester) {
                $q->where('id_semester', $semester);
            })
            ->orderBy('tgl_bayar', 'desc');


        return datatables()->of($query)
            ->editColumn('tgl_bayar', function ($row) {
                return $row->tgl_bayar
                    ? Carbon::parse($row->tgl_bayar)->format('d-m-Y')
                    : '-';
            })
            ->addIndexColumn()->addColumn('action', function ($row) {

                //$smt = DB::table('t_status_mahasiswa')->select(DB::raw('count(str_nim) as jml'))->where('str_nim',$row->str_nim)->first();
                $btn = '<button class="btn btn-sm btn-warning" id="btn-detail" data="' . $row->id . '" onclick="hapus(' . $row->id . ',' . $row->nim . ')">Hapus</button>
                <button class="btn btn-sm btn-primary" id="btn-detail" data="' . $row->id . '" onclick="editTransaksi(' . $row->id . ')">Edit</button>


                  ';
                return $btn;
            })
            
             ->addIndexColumn()->addColumn('action-gambar', function ($row) {

                $btn = '<button class="btn btn-sm btn-danger" id="btn-detail" data="' . $row->id . '" onclick="hapusGambar(' . $row->id . ',' . $row->nim . ')">Hapus Gambr</button>';
                return $btn;
            })
            ->rawColumns(['action', 'action-gambar'])

            ->make(true);
    }


    public function detailBayar($id)
    {

        $query = DetailPembayaranModel::with(['bayar', 'itemBayar', 'pengguna'])->where('id_bayar', $id)->get();
        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button class="btn btn-sm btn-success" onclick="edit2(' . $row->id . ')">Edit</button>
                <button class="btn btn-sm btn-warning" onclick="hapusDetail(' . $row->id . ', ' . $row->id_bayar . ')">Del</button>
               ';
                return $btn;
            })
            ->make(true);
    }

    public function getMahasiswa($id)
    {
        $data = MahasiswaMbcModel::where('str_nim', $id)->first();

        return $data;
    }

    public function getDetailBayar($id)
    {
        $data = DetailPembayaranModel::with(['itemBayar', 'bayar'])->where('id', $id)->first();

        return $data;
    }


    public function updateDetailPembayaran(Request $request)
    {

        $id = $request->id;
        $data = DetailPembayaranModel::where('id', $id)->update('jml_bayar', $request->jml_bayar);

        return $data;
    }

    public function simpanKategori(Request $request)
    {
        $id = $request->id;
        $smt = $request->id_semester;
        $sem = str_pad($smt, 2, '0', STR_PAD_LEFT);

        $dataUpdate = [
           // 'keterangan' => $request->asal,
            'ket_simple' => $request->ket_simple,
          //  'sts_trans' => $request->kategori_id,
            'tgl_bayar' => $request->tgl_bayar,
            'id_semester' => $sem,
            'via' => $request->via,
            'bank_penerima' => $request->bank_penerima ?? 0,
        ];



        // CEK JIKA ADA GAMBAR
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $imageName = 'profile_pengguna_' . Str::random(5) . '.' . $gambar->extension();

            $rute = $gambar->storeAs('bukti-transaksi', $imageName, 'private');

            $absolutePath = storage_path('app/private/' . $rute);


            $dataUpdate['bukti_bayar'] = $imageName; // field DB
        }

        $data = PembayaranModel::where('id', $id)->update($dataUpdate);

        return response()->json([
            'success' => true
        ]);
    }


    public function show($id)
    {

        $data = DB::table('tb_bayar')->where('id', $id)->first();

        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }

        return response()->json($data);
    }

    public function showPembayaran($id)
    {

        $data = DB::table('tb_bayar')->where('id', $id)->first();

        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }

        return response()->json($data);
    }



    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $data =  DB::table('tb_bayar')->find($id);
            if (!$data) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $data->update($request->all());


            Log::info('User login', [
                'user_id' => auth()->id(),
                'email'   => Auth::user()->email,
                'serial'  => $this->serial,
                'tabel' => 'tb_bayar',
                'aksi' => 'create',
                'data' => $request->all(),
                'url' => url('master_keuangan_mbc') . '/' . $id
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data pembayaran', [
                'error' => $e->getMessage(),
                'id'   => $id
            ]);

            // Kalau mau lempar lagi errornya
            throw $e;
        }
    }

    public function hapusTransaksi(Request $request)
    {
        $id = $request->id;

        try {
            DB::beginTransaction();
            $ids = DB::table('tb_bayar')
                ->join('tb_det_bayar', 'tb_bayar.id', '=', 'tb_det_bayar.id_bayar')
                ->where('tb_bayar.id', $id)
                ->orderBy('tb_bayar.id', 'desc')
                ->pluck('tb_det_bayar.id');

            // DB::table('tb_det_bayar')
            //     ->whereIn('id_bayar', $ids)
            //     ->delete();

            DB::table('tb_bayar')
                ->where('tb_bayar.id', $id)
                ->delete();

            DB::table('tb_det_bayar')
                ->whereNotIn('id_bayar', function ($query) {
                    $query->select('id')->from('tb_bayar');
                })
                //->limit(100) // optional
                ->delete();

            Log::info('User login', [
                'user_id' => auth()->id(),
                'email'   => Auth::user()->email,
                'serial'  => $this->serial,
                'tabel' => 'tb_bayar',
                'aksi' => 'create',
                'data' => $request->all(),
                'url' => url('hapusTransaksi') . '/' . $id
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
}
