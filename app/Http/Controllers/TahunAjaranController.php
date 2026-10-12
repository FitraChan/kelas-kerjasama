<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ThnAjaran;
use App\Models\StatusMahasiswaModel;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $data = ThnAjaran::orderBy('id','desc')->get();
        return view('livewire.thn_ajaran', compact('data'));
    }

    public function store(Request $request)
    {

        try {

            DB::transaction(function () use ($request) {

                // Ambil tahun ajaran aktif saat ini
                $thn = ThnAjaran::where('isaktiv', 1)->first();

                if (!$thn) {
                    throw new \Exception("Tidak ada tahun ajaran aktif.");
                }

                // Ambil status mahasiswa untuk tahun aktif
               $status = StatusMahasiswaModel::with('mahasiswa')
                                    ->where('id_thn_ajaran', $thn->id)
                                    ->where('status', '!=', 4) // tidak aktif
                                    ->whereHas('mahasiswa', function ($query) {
                                            $query->whereNotIn('bol_lulus', [1, 5, 3]);
                                        })
                                    ->get();

                // Nonaktifkan semua tahun ajaran
                ThnAjaran::query()->update(['isaktiv' => 0]);

                // Tambahkan tahun ajaran baru dan simpan ID-nya
                $newThn = ThnAjaran::create([
                    'id'           => $thn->id +  1,
                    'thn_ajaran' => $request->thn_ajaran,
                    'isaktiv'    => $request->isaktiv ?? 0,
                ]);

                // Loop status mahasiswa dan buatkan semester baru
                foreach ($status as $st) {

                    $smt = (int)$st->id_semester + 1;
                    StatusMahasiswaModel::create([
                         // Pastikan ID unik
                        'nim'          => $st->nim,
                        'id_semester'  => str_pad($smt, 2, '0', STR_PAD_LEFT),
                        'id_thn_ajaran'=> $thn->id +  1,
                    ]);
                }

            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        ThnAjaran::where('id', $request->id)->update([
            'thn_ajaran' => $request->thn_ajaran,
            'isaktiv' => $request->isaktiv,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        ThnAjaran::where('id', $id)->delete();

        StatusMahasiswaModel::where('id_thn_ajaran', $id)->delete();
        return response()->json(['success' => true]);
    }
}
