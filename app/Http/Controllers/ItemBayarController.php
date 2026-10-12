<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemBayar;
use DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;




class ItemBayarController extends Controller
{
  //
  public function index(Request $request, ItemBayar $itemBayar)
  {
    $data = $itemBayar->orderBy('str_angkatan', 'DESC')->get();

    //  $data = $itemBayar->orderBy('id','DESC')->get();
    if ($request->ajax()) {
      return datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
          return '
							<button class="btn btn-sm btn-info" id="btn-detail" data-toggle="modal" data-target="#ubah-' . $data->id . '"> Edit </button>

							';
        })
        ->addColumn('kategori', function ($data) {
          if ($data->id_kategori == 1) {
            return 'Pendaftaran';
          } elseif ($data->id_kategori == 2) {
            return 'Semesteran';
          } else {
            return '-';
          }
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    return view('livewire.itemBayar', compact('data'));
  }

  public function store(Request $request)
  {
    try {
      $serial = $_SERVER['HTTP_USER_AGENT'];
      $data = $request->validate([
        'nama_item' => 'required|string|max:255',
        //'id_kategori' => 'required|integer',
        // 'id_kat_periode' => 'nullable|integer',
        //'keterangan' => 'nullable|string',
        'def_value' => 'required|numeric',
        'str_angkatan' => 'nullable|string|max:255',
      ]);

      $item = ItemBayar::create($request->all());
      Log::info('Item Bayar', ['user_id' => auth()->id(), 'email' => Auth::user()->email, 'serial' => $serial, 'table' => 'mbc_itembayar', 'data' => $request->all(), 'aksi' => 'create', 'url' => url('itemBayar')]);

      if ($item) {
        return back()->with('success', 'Data Baru Behasil ditambah');
      } else {
        return back()->with('error', 'Data Baru Gagal ditambah');
      }
      return response()->json($item, 200);
    } catch (\Throwable $th) {
      return back()->with('error', $th->getMessage());
    }
  }

  public function show($id)
  {
    $item = ItemBayar::findOrFail($id);
    return response()->json($item);
  }

  public function update(Request $request, $id)
  {

    $serial = $_SERVER['HTTP_USER_AGENT'];

    try {
      $item = ItemBayar::findOrFail($id);

      $data = $request->validate([
        'nama_item' => 'required|string|max:255',
        // 'id_kategori' => 'required|integer',
        //'id_kat_periode' => 'nullable|integer',
        //'keterangan' => 'nullable|string',
        'def_value' => 'required|numeric',
        'str_angkatan' => 'nullable|string|max:255',
      ]);

      $cek =    $item->update($request->all());

      Log::info('Item Bayar', ['user_id' => auth()->id(), 'email' => Auth::user()->email, 'serial' => $serial, 'table' => 'mbc_itembayar', 'data' => $request->all(), 'aksi' => 'update', 'url' => url('itemBayar') . '/' . $id]);


      if ($cek) {
        return back()->with('success', 'Data Behasil diubah');
      } else {
        return back()->with('error', 'Data Gagal diubah');
      }


      //  return response()->json($item);

    } catch (\Throwable $th) {
      return back()->with('error', $th->getMessage());
    }
  }

  public function destroy($id, Request $request)
  {
    // $serial = $_SERVER['HTTP_USER_AGENT'];

    // try {
    //   $item = ItemBayar::findOrFail($id);
    //   $cek =  $item->delete();
    //   Log::info('Item Bayar', ['user_id' => auth()->id(), 'email' => Auth::user()->email, 'serial' => $serial, 'table' => 'mbc_itembayar', 'data' => $request->all(), 'aksi' => 'delete', 'url' => url('itemBayar') . '/' . $id]);

    //   if ($cek) {
    //     return response()->json(['success' => 'Success']);
    //   } else {
    //     return response()->json(['error' => 'Data Delete failed.']);
    //   }
    // } catch (\Throwable $th) {
    //   return back()->with('error', $th->getMessage());
    // }
  }
}
