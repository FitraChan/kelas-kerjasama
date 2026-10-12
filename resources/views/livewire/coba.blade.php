function coba()
  {


    $data = [];
    $years = MahasiswaMbcModel::select('str_angkatan')
     ->where('str_angkatan', '>', 2023)
    ->distinct()
    ->pluck('str_angkatan'); 

$query = DB::table('db_owner.mbc_v_mhs as a')
      ->join('mbc_bayar as b','b.nim','=','a.str_nim')
      ->join('mbc_det_bayar as c', 'b.id', '=', 'c.id_bayar')
      ->join('mbc_itembayar as d', 'c.id_item', '=', 'd.id')
      // ->selectRaw("'Mahasiswa Aktif' AS keterangan")
       ->select(
      'b.nim',
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
     ->groupBy('b.nim')
      ->groupBy('c.id_item')   
      ->groupBy('nama_item')       
      ->groupBy('id_semester')
       ->where('a.str_angkatan',2024)
       ->whereRaw('d.id_kategori = 2')->get();

       foreach($query as $z){
        //  $sisa = $z->SUMK2 - $z->SUMP - $z->SUMB;
         //if($z->SUMB == 0){
           $data[] = [$z->nim,$z->nama_item,$z->id_semester,$z->SUMB];

         //}
       }

       return $data;

// foreach ($years as $year) {
//     $query->selectRaw("
//         SUM(CASE WHEN a.str_angkatan = ? AND d.id_kategori = 2 THEN 1 ELSE 0 END) AS [$year]
//     ", [$year]);
// }

$dataAktif = $query->first();

//baris kedua:  aktif
// $query2 =  DB::table('db_owner.mbc_v_mhs as a')
//       ->join('mbc_bayar as b','b.nim','=','a.str_nim')
//       ->join('mbc_det_bayar as c', 'b.id', '=', 'c.id_bayar')
//       ->join('mbc_itembayar as d', 'c.id_item', '=', 'd.id')
//        ->selectRaw("'Mahasiswa  Aktif' AS keterangan");;

// foreach ($years as $year) {
//     $query2->selectRaw("
//         SUM(CASE WHEN a.str_angkatan = ? AND d.id_kategori = 2 THEN 1 ELSE 0 END) AS [$year]
//     ", [$year]);
// }

//$dataAktif = $query2->first();

//$rows = [$dataAktif, $dataNonAktif];
//$rows = [$dataAktif];

//return $rows;

//return view('laporan.mahasiswa', compact('years', 'rows'));

   


   // $ids = DB::table('mbc_bayar')->get();
   // $ids = DB::table('mbc_det_bayar')->get();
  //  $ids = DB::table('mbc_itembayar')->get();

    

//return $ids;

    // return $pivot = [
    //   'keterangan' => 'Jumlah Mahasiswa',
    // ] + $ids->toArray();


  }