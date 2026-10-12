<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use DataTables;

use Illuminate\Http\Request;
use App\Models\StatusMahasiswaModel;
use App\Models\MahasiswaMbcModel;


class StatusMahasiswaController extends Controller
{
    public function index(Request $request)
    {

        $status = $request->status_mahasiswa ?? null;
        $years = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();

        $tahun =  $years->thn_ajaran;
        $data = StatusMahasiswaModel::with('mahasiswa')
            ->when($status, function ($query, $status) use ($tahun) {


                if ($status == 'aktif') {
                    $query->where('status', 1)
                        ->where('id_thn_ajaran', $tahun)
                        ->whereHas('mahasiswa', function ($q) {
                            $q->where('bol_lulus', 0);
                        });
                } elseif ($status == 'nonaktif') {
                    $query->where('status', 3)
                        ->where('id_thn_ajaran', $tahun) 
                        ->whereHas('mahasiswa', function ($q) {
                            $q->where('bol_lulus', 0);
                        });
                        
                }
            })->orderBy('id_thn_ajaran', 'desc');

        $thn_ajaran = DB::table('tb_thn_ajaran')->orderBy('id', 'desc')->get();



        if ($request->ajax()) {

            return datatables()->of($data)
                ->addIndexColumn() // ⬅️ ini penting untuk DT_RowIndex

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('nama_mahasiswa', function ($row) {
                    return $row->mahasiswa ? $row->mahasiswa->str_nm_mhs : '-';
                })

                ->addColumn('status', function ($row) {
                    return match ($row->status) {
                        1 => 'Aktif',
                        2 => 'Cuti',
                        3 => 'Tidak Aktif',
                        4 => 'Telah Lulus',
                        default => '-'
                    };
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-warning btn-sm editBtn" data-id="' . $row->id . '">Edit</button>
                        
                    ';
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }
        return view('livewire.status_mahasiswa', compact('data', 'thn_ajaran'));

        // return view('livewire.status_mahasiswa');
    }



    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_thn_ajaran' => 'required|integer'
            ]);

            DB::beginTransaction();

            // Ambil semua mahasiswa yang statusnya 1 (Aktif) atau 2 (Cuti)
            $siswa = StatusMahasiswaModel::whereIn('status', [1, 2])->get();

            foreach ($siswa as $var) {
                StatusMahasiswaModel::create([
                    'nim' => $var->nim,
                    'status' => 1,
                    'id_thn_ajaran' => $request->id_thn_ajaran,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan untuk semua mahasiswa aktif dan cuti.'
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Simpan log error untuk debugging
            // Log::error('Gagal menyimpan status mahasiswa: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $item = StatusMahasiswaModel::findOrFail($id);
        return response()->json($item);
    }

    public function edit($id)
    {
        $item = StatusMahasiswaModel::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = StatusMahasiswaModel::findOrFail($id);
        $item->update($request->all());
        return response()->json($item);

        //return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        // $item = StatusMahasiswaModel::findOrFail($id);
        // $item->delete();
        // return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function updateMass(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids', []);
            $statusBaru = $request->input('status');

            if (empty($ids) || !$statusBaru) {
                return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 400);
            }

            StatusMahasiswaModel::whereIn('id', $ids)->update(['status' => $statusBaru]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

   
}
