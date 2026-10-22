<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaMbcModel;
use App\Models\StatusMahasiswaModel;
use App\Models\LogModel;
use App\Models\StatusBerangkat;


use App\Models\Jurusan;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MahasiswaMbcModel::with([
                'negaraTujuan',
                'statusBerangkat',
            ]);
            return DataTables::of($data)
                ->addIndexColumn()
                 ->addColumn('nama_negara', function ($row) {
                    return $row->negaraTujuan?->nama_negara ?? '-';
                })

                ->addColumn('status_berangkat', function ($row) {
                    return $row->statusBerangkat?->status ?? '-';
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->str_nim . '">';
                })
                // ->addColumn('status', fn($row) => $row->bol_sts_kelas ? 'Aktif' : 'Tidak Aktif')
                ->addColumn('action', function ($row) {
                    $edit = '<button class="btn btn-warning" data-toggle="modal" data-target="#ubah-' . $row->id . '">
               Edit
            </button>';
                    $delete = '<button class="btn btn-danger" onclick="confirm_delete(\'/mahasiswa/' . $row->str_nim . '\')">
               Delete
            </button>';
                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }

        $data = MahasiswaMbcModel::all();
        $jurusan = Jurusan::all();
        $status_berangkat = StatusBerangkat::all();
        $negara_tujuan = DB::table('tb_negara_tujuan')->get();
        return view('livewire.mhs', compact('data', 'jurusan', 'status_berangkat', 'negara_tujuan'));
    }

    public function getMahasiswaApi(Request $request)
    {
        // code...
        $nim = $request->str_nim;
        $cek = MahasiswaMbcModel::where('str_nim', $nim)->first();


        if (empty($cek->id)) {
            MahasiswaMbcModel::create([
                'str_nim' => $request->str_nim,
                'str_nm_mhs' => $request->str_nm_mhs,
                'str_jurusan' => $request->str_jurusan,
                'str_angkatan' => $request->str_angkatan,
                'str_alamat' => $request->str_alamat,
                'str_hp_mhs' => $request->str_hp_mhs,
                'str_imel_mhs' => $request->str_imel_mhs,
                'str_campus' => $request->str_campus,
                'bol_pjj' => $request->bol_pjj,
                'bol_karyawan' => $request->bol_karyawan,
                'bol_jimbaran' => $request->bol_jimbaran,
                'bol_lulus' => $request->bol_lulus,
                'str_kd_kelas' => $request->str_kd_kelas,
                'bol_sts_kelas' => $request->bol_sts_kelas,
            ]);

            $thn = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();
            StatusMahasiswaModel::create([
                'nim' => $request->str_nim,
                'status' => 1,
                'id_thn_ajaran' => $thn->id,
            ]);


            $semester = $request->semester['semester'];

            DB::table('tb_status_mahasiswa')
                ->where('nim', $request->str_nim)
                ->update([
                    'id_semester' => str_pad($semester, 2, '0', STR_PAD_LEFT),
                    'updated_at' => now()
                ]);

            return response()->json('data terinsert');
        } else {

            MahasiswaMbcModel::where('str_nim', $request->str_nim)->update(['bol_lulus' => $request->bol_lulus]);

            return response()->json('update status mahasiswa');
        }
    }

    public function store(Request $request)
    {
        $years = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();

        $request->validate([
            'str_nim' => 'required|unique:tb_mbc_mahasiswa,str_nim',
            'str_nm_mhs' => 'required',
        ]);

        MahasiswaMbcModel::create($request->all());
        DB::table('tb_status_mahasiswa')->insert([
            'nim' => $request->str_nim,
            'status' => $request->status,
            'id_thn_ajaran' => $years->id
        ]);


        return redirect()->back()->with('success', 'Data berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $data = MahasiswaMbcModel::findOrFail($id);
        $data->update([
            'str_nim'          => $request->str_nim,
            'str_nm_mhs'       => $request->str_nm_mhs,
            'str_jurusan'      => $request->str_jurusan,
            'str_angkatan'     => $request->str_angkatan,
            'str_alamat'       => $request->str_alamat,
            'str_hp_mhs'       => $request->str_hp_mhs,
            'str_imel_mhs'     => $request->str_imel_mhs,
            'str_campus'       => $request->str_campus,
            'bol_sts_kelas'    => $request->bol_sts_kelas,
            'bol_lulus'        => $request->bol_lulus,

            // 🔥 INI YANG KAMU BUTUHKAN
            'id_kuliah_kerja'  => $request->id_kuliah_kerja,
        ]);
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }



    public function updateLn(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids', []);
            $statusBaru = $request->input('status');

            if (empty($ids) || !$statusBaru) {
                return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 400);
            }

            MahasiswaMbcModel::whereIn('str_nim', $ids)->update(['is_luarnegeri' => $statusBaru]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function updateBolLulus(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids', []);
            $statusBaru = $request->input('status');

            // if (empty($ids) || !$statusBaru) {
            //     return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 400);
            // }

            MahasiswaMbcModel::whereIn('str_nim', $ids)->update(['bol_lulus' => $statusBaru]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function setSemesterNim(Request $request)
    {

        //$data = $request->data; // array dari frontend

        // code...
        $nim = $request->nim;
        $semester = $request->semester;
        $cek = MahasiswaMbcModel::where('str_nim', $nim)->first();


        //if(empty($cek-id)){
        // update
        DB::table('tb_status_mahasiswa')
            ->where('nim', $nim)
            ->update([
                'id_semester' => str_pad($semester, 2, '0', STR_PAD_LEFT),
                'updated_at' => now()
            ]);

        return response()->json(['message' => 'berhasil']);

        // }else{

        //   return response()->json(['message' => 'data sudah ada']);

        // }




    }

    public function autocomplete(Request $request)
    {
        $search = $request->search;

        $data = DB::table('pendaftarans')
            ->select('id', 'nama_lengkap')
            ->where('nama_lengkap', 'like', "%{$search}%")
            ->limit(10)
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'label' => $row->nama_lengkap,
                'value' => $row->nama_lengkap,
                'id'    => $row->id
            ];
        }

        return response()->json($result);
    }

    public function destroy(Request $request, $nim, LogModel $log)
    {
        MahasiswaMbcModel::where('str_nim', $nim)->delete();

        StatusMahasiswaModel::where('nim', $nim)->delete();

        //  DB::table('tb_status_mahasiswa')->where('nim',$nim)->delete();

        $logs2 = [
            'tanggal' => now(),
            'tabel' => 'tb_mbc_mahasiswa',
            'aksi' => 'delete',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode(['nim' => $nim]),
            'serial' => url('destroy') // Assuming you want to log the serial number
        ];
        $log->create($logs2);

        return response()->json(['message' => 'data berhasil dihapus']);

        //return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus');
    }


    public function updateStatusBerangkat(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|string',
            
        ]);

        MahasiswaMbcModel::whereIn('str_nim', $request->ids)
            ->update([
                'id_status_berangkat' => $request->status,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berangkat berhasil diperbarui.',
        ]);
    }

    public function updateNegaraTujuan(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|string',
            
        ]);

        MahasiswaMbcModel::whereIn('str_nim', $request->ids)
            ->update([
                'id_negara_tujuan' => $request->negara_tujuan,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Negara tujuan berhasil diperbarui.',
        ]);
    }
}
