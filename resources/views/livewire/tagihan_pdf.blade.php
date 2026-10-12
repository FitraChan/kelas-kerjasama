<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rekap Kewajiban Mahasiswa - {{ $atas->str_nim ?? '' }}</title>
  <style>
    /* Umum */
    
    
    table {
      page-break-inside: auto;
    }
    
    tr {
      page-break-inside: avoid !important;
      page-break-after: auto;
    }
        
    
    @page {
    size: A4 landscape;
    margin: 15mm;
  }


    .sign .date {
      display: block;
      margin-top: 80px;
      /* bikin turun ke bawah */
      margin-bottom: 40px;
      /* spasi ke tanda tangan */
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      color: #111;
      margin: 20px;
      background: #fff;
    }

    .info-vertical div {
      margin: 4px 0;
      /* kasih jarak antar baris */
      font-size: 14px;
    }

    .label {
      display: inline-block;
      width: 120px;
      /* biar rata kiri */
      font-weight: 600;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
    }

    .header {
      
      align-items: center;
      gap: 12px;
    }

    .logo {
      width: 230px;
    }

    /* sesuaikan ukuran */
    h1 {
      font-size: 18px;
      margin: 0;
    }

    .meta {
      margin-top: 8px;
      font-size: 14px;
      color: #222;
    }

    /* Info siswa */
    .info {
      margin-top: 12px;
      display: flex;
      flex-wrap: wrap;
      gap: 6px 20px;
      font-size: 14px;
    }

    .info .label {
      min-width: 110px;
      font-weight: 600;
    }

    /* Tabel */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 18px;
      font-size: 10px;
    }

    table thead th {
      background: #f3f6fb;
      border: 1px solid #d3dbe8;
      padding: 8px;
      text-align: left;
      font-weight: 700;
    }

    table tbody td {
      border: 1px solid #e3e8ef;
      padding: 8px;
      vertical-align: middle;
    }

    table tbody tr:nth-child(even) {
      background: #fbfdff;
    }

    .text-right {
      text-align: right;
    }

    .center {
      text-align: center;
    }

    /* Tanda tangan */
   .sign {
    margin-top: 28px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-size: 13px;
}

.sign .col {
    width: 45%;
    text-align: center;
}

    .sign .date {
      margin-bottom: 56px;
    }

    /* space for signature */

    /* Angkatan list */
    .angkatan-list {
      margin-top: 10px;
      font-size: 13px;
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    /* kecil */
    .muted {
      color: #666;
      font-size: 12px;
    }
  </style>
</head>

<body>
  <div class="container">
    <header class="header">
      {{-- logo: pastikan file ada di public/images/ke.png --}}
      <img src="{{ asset('images/logo-mbc_2.png') }}" alt="logo" class="logo">
      <div>
        <h1>REKAP KEWAJIBAN MAHASISWA</h1>
        <div class="muted">Rekap otomatis — {{ date('d M Y') }}</div>
      </div>
    </header>

    <section class="meta">
      <div class="info-vertical">
        <div><span class="label">Nama Mahasiswa</span>: {{ $atas->str_nm_mhs ?? '-' }}</div>
        <div><span class="label">NIS</span>: {{ $atas->str_nim ?? '-' }}</div>
        <div><span class="label">Angkatan</span>: {{ $atas->str_angkatan ?? '-' }}</div>
        <div><span class="label">Prodi</span>: {{ $atas->str_nm_prodi ?? '-' }}</div>
        <div><span class="label">Tahun Ajaran</span>: {{ $thn_ajaran->str_thn_ajaran ?? '-' }}</div>
        <div><span class="label">Keaktifan</span>: {{ $aktif  }}</div>

      </div>
    </section>

    {{-- Tabel item --}}


    @php
    $iy = 0;
    $totB = 0.0;
    $totK2 = 0.0;
    $totP = 0.0;

    // group data per semester
    $groupSemester = $sem->groupBy('id_semester');

    $groupItem = $isi->groupBy('nama_item');


   


    // mapping semester ke romawi
   
    @endphp

    <table>
      <thead>
        <tr>
          <th style="width:4%;">No</th>
          <th>Nama Item</th>
           <th style="width:12%;" class="text-right">Tot Kewajiban</th>
           <th style="width:12%;" class="text-right">Tot Potongan</th>
          
          <th style="width:12%;" class="text-center">Tanggal</th>

          <th style="width:12%;" class="text-center">Jumlah Bayar</th>
          
         
          
          <th style="width:12%;" class="text-right">Tot Bayar</th>
         
         
          <th style="width:12%;" class="text-right">Sisa</th>
          <th style="width:12%;" class="text-right">Ket</th>
          <th style="width:12%;" class="text-right">Admin</th>
        </tr>
      </thead>
      <tbody>
        @foreach($groupItem as $namaItemKey => $rows)
        @php
         $iy++; 
        $rowspan = $rows->count();

        $totalBayar = $rows->sum('SUMB');
        $totalKewajiban = $rows->sum('SUMK2');
        $totalPotongan = $rows->sum('SUMP');
        $sisaItem = $totalKewajiban - $totalPotongan - $totalBayar;

        // akumulasi global
        $totB += $totalBayar;
        $totK2 += $totalKewajiban;
        $totP += $totalPotongan;
        @endphp

        @foreach($rows as $i => $baris)
        @php
       

        $nama_item = $baris->nama_item ?? '-';
        if ($nama_item == 'Final Eksekutif') {
        $nama_item = 'Final Eksekutif Semester ' . $baris->id_semester;
        }
        @endphp

        <tr>
         

        @if($i === 0)
    <td rowspan="{{ $rowspan }}" class="center">{{ $iy }}</td>
    <td rowspan="{{ $rowspan }}">{{ $nama_item }}</td>
  @endif
  
  
             {{-- TOT KEWAJIBAN --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($totalKewajiban,2) }}
          </td>
          @endif
          
          
             {{-- TOT POTONGAN --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($totalPotongan,2) }}
          </td>
          @endif
          
          

          {{-- TANGGAL --}}
          <td class="text-center">
             @if($baris->jml_bayar > 0)
            {{ \Carbon\Carbon::parse($baris->tgl_bayar)->format('d-M-Y') }}

            @endif
          </td>

          <td class="text-center">

             @if($baris->jml_bayar > 0)    
            {{ number_format($baris->jml_bayar,2)  }}

              @endif
          </td>

          {{-- TOT BAYAR --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($totalBayar,2) }}
          </td>
          @endif

          

       

          {{-- SISA --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($sisaItem,2) }}
          </td>
          @endif

          <td class="text-right">{{ $baris->ket_simple }}</td>
            <td class="text-right">{{ $baris->nama_user }}</td>

        </tr>

        @endforeach
        @endforeach

        @foreach($groupSemester as $idSemester => $rows)
        @php
$iy++;
         $romawi = [
    '01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V',
    '06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX',
    '10'=>'X',
    '11'=>'XI',
    '12'=>'XII',
    '13'=>'XIII',
    '14'=>'XIV',
    '15'=>'XV',
    '16'=>'XVI',
    '17'=>'XVII',
    '18'=>'XVIII',
    '19'=>'XIX',
    ];

         
        $semLabel = $romawi[$idSemester] ?? $idSemester;

        $rowspan = $rows->count();

        $totalSumbSemester = $rows->sum('SUMB');
        $totalKewajibanSemester = $rows->sum('SUMK2');
        $totalPotonganSemester = $rows->sum('SUMP');

        $sisaSemester = $totalKewajibanSemester
        - $totalPotonganSemester
        - $totalSumbSemester;
        @endphp

        @foreach($rows as $i => $baris)
        @php
        

$angkatan = (int) $baris->str_angkatan; // contoh: 2021
$id = (int) $baris->id_semester;         // contoh: 3

  // 1. Hitung tahun ajaran berdasarkan semester
        $startYear = $angkatan + floor(($id - 1) / 2);
        $endYear   = $startYear + 1;
        $tahunAjar = $startYear . '/' . $endYear;
        
        // 2. Tentukan ganjil/genap
        $jenis = ($id % 2 == 0) ? 'Genap' : 'Ganjil';

        $item = $rows->first();
        $namaItem = $item->nama_item;
        if ($item->id_kategori == 2) {
        $namaItem .= ' ' . $semLabel;
        }


          $huruf = $romawi[$id] ?? $id;
        
        // 4. Gabungkan output akhir
        $hasil = $tahunAjar . ' – ' . $jenis . ' ' . $huruf;

        // akumulasi global
        $totB += floatval($baris->SUMB ?? 0);
        $totK2 += floatval($baris->SUMK2 ?? 0);
        $totP += floatval($baris->SUMP ?? 0);
        @endphp

        <tr>
            
            
          @if($i === 0)
            <td rowspan="{{ $rowspan }}" class="center">{{ $iy }}</td>
            <td rowspan="{{ $rowspan }}">{{ $hasil }}</td>
          @endif
          
          
            {{-- TOT KEWAJIBAN --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($totalKewajibanSemester,2) }}
          </td>
          @endif

          {{-- TOT POTONGAN --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($totalPotonganSemester,2) }}
          </td>
          @endif


          <td class="text-center">

           @if($baris->jml_bayar > 0)
            {{ \Carbon\Carbon::parse($baris->tgl_bayar)->format('d-M-Y') }}

            @endif
          </td>

          <td class="text-center">
         @if($baris->jml_bayar > 0)    
            {{ number_format($baris->jml_bayar,2)  }}

          @endif
          </td>

          {{-- TOT BAYAR --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($totalSumbSemester,2) }}
          </td>
          @endif

        
          {{-- SISA --}}
          @if($i === 0)
          <td class="text-right" rowspan="{{ $rowspan }}">
            {{ number_format($sisaSemester,2) }}
          </td>
          @endif

          <td class="text-right">{{ $baris->ket_simple }}</td>
         <td class="text-right">{{ $baris->nama_user }}</td>

        </tr>
        @endforeach
        @endforeach


        {{-- TOTAL --}}
        <tr style="font-weight:700; background:#eef4ff;">
          <td></td>
          <td>TOTAL</td>
          <td class="text-right">{{ number_format($totK2,2) }}</td>
          <td class="text-right">{{ number_format($totP,2) }}</td>
          <td class="text-right"></td>
          <td class="text-right"></td>
          <td class="text-right">{{ number_format($totB,2) }}</td>
          <td class="text-right">{{ number_format($totK2 - $totP - $totB,2) }}</td>
          
          <td></td>
        </tr>

      </tbody>
    </table>





    <div class="sign">
      <div class="col">
        <div class="date" style="margin-top:10px; margin-bottom:20px;">
          Denpasar, {{ date('d M Y') }}
        </div>
        <div>Siswa</div>
        <div style="margin-top:46px; font-weight:700;">
          {{ $atas->str_nm_mhs ?? '' }}
        </div>
      </div>

      <div class="col">
        <div class="date" style="margin-top:10px; margin-bottom:20px;">&nbsp;</div>
        <div>Bagian Keuangan,</div>
        <div style="margin-top:46px; font-weight:700;">
           Rochma Nurwandani 
           <!-- Fitra Anggara Yudha -->
        </div>
      </div>
    </div>

  </div>
</body>

</html>