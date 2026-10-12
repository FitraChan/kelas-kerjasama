<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogModel;
use App\Models\BayarIpaymu;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class IpaymuOrderController extends Controller
{

    public function index(Request $request)
    {
        $data = BayarIpaymu::with(['detailBayar','mahasiswa','transaction'])->orderBy('id', 'DESC')->get();

        //  $data = $itemBayar->orderBy('id','DESC')->get();
        if ($request->ajax()) {
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {

    if ($data->sts_bayar == 1) {
        return '';
    }
    
     if ($data->transaction && $data->transaction->status === 'success') {
            return '
            <button class="btn btn-danger" onclick="confirm_export('.$data->id.')">
               Export
            </button>
            ';
            
     }
            })            
            ->make(true);
        }
        return view('livewire.ipaymu', compact('data'));
    }


    public function simpan_ipaymu(Request $request)
    {
        // $request->validate([
        //     'nim' => 'required|string',
        //     'items' => 'required|array',
        //     'nominal' => 'required'
        // ]);

        try {
            DB::transaction(function () use ($request) {

                $userId = auth()->id();

                $orderId = DB::table('tb_ipaymu_bayar')->insertGetId([
                    'nim' => $request->nim,
                    'id_tahun' => date('Y'),
                    'id_bulan' => date('m'),
                    'tgl_bayar' => date('Y-m-d',strtotime($request->tgl_trans ))  ?? now(),
                    'id_kasir' => $userId,
                    'via' => 4,
                    'sts_bayar' => 2,
                    'no_kwitansi' => $request->no_kwitansi,
                    'keterangan' => $request->comments,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $get_nominal = (int) preg_replace('/[^0-9]/', '', $request->nominal);

                $nominal  = substr($get_nominal, 0, -2);

              //  foreach ($request->items as $item) {
                    DB::table('tb_ipaymu_det_bayar')->insert([
                        'id_bayar' => $orderId,
                        'nama_item' => $request->item,
                        'jml_bayar' => $nominal,
                        'id_user' => $userId,
                    ]);
             //   }

                DB::table('tb_ipaymu_transaction')->insert([
                    'id_bayar' => $orderId,
                    'amount' => $nominal,
                    'status' => 'pending',
                    'created_at' => now()
                ]);
            });

            return back()->with('success', 'Berhasil');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
