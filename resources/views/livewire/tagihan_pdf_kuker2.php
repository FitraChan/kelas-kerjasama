<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rekap Kewajiban Mahasiswa - {{ $atas->str_nim ?? '' }}</title>
  <style>
    /* Umum */


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
      display: flex;
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
        <div><span class="label">Nama Siswa</span>: {{ $atas->str_nm_mhs ?? '-' }}</div>
        <div><span class="label">NIS</span>: {{ $atas->str_nim ?? '-' }}</div>
        <div><span class="label">Angkatan</span>: {{ $atas->str_angkatan ?? '-' }}</div>
        <div><span class="label">Prodi</span>: {{ $atas->str_nm_prodi ?? '-' }}</div>
        <div><span class="label">Tahun Ajaran</span>: {{ $thn_ajaran->str_thn_ajaran ?? '-' }}</div>
      

      </div>
    </section>

    {{-- Tabel item --}}



   <table border="1" width="100%" cellspacing="0" cellpadding="4">
    <thead style="background:#f0f0f0;">
        <tr>
            <th width="4%">No</th>
            <th>Nama Item</th>
            <th>Tanggal</th>
            <th>Jumlah Bayar</th>
            <th>Total Bayar</th>
            <th>Total Kewajiban</th>
            <th>Sisa</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>

    @php
    $groupData = $pdf->groupBy('nama_pembayaran');
    $no = 1;
    $grandBayar = 0;
    $grandKewajiban = 0;
    @endphp
    
    @foreach($groupData as $namaItem => $rows)
    
        @php
        $rowspan = $rows->count();
        $totalBayar = $rows->sum('dibayarkan');
        $totalKewajiban = $rows->sum('nominal');
        $sisaItem = $totalKewajiban - $totalBayar;
    
        $grandBayar += $totalBayar;
        $grandKewajiban += $totalKewajiban;
        @endphp
    
        @foreach($rows as $i => $row)
            <tr>
                @if($i == 0)
                    <td rowspan="{{ $rowspan }}" align="center">{{ $no++ }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $namaItem }}</td>
                @endif
    
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
    
                <td align="right">
                    {{ number_format($row->dibayarkan ?? 0, 2) }}
                </td>
    
                @if($i == 0)
                    <td rowspan="{{ $rowspan }}" align="right">
                        {{ number_format($totalBayar, 2) }}
                    </td>
                    <td rowspan="{{ $rowspan }}" align="right">
                        {{ number_format($totalKewajiban, 2) }}
                    </td>
                    <td rowspan="{{ $rowspan }}" align="right">
                        {{ number_format($sisaItem, 2) }}
                    </td>
                    <td rowspan="{{ $rowspan }}" align="center">
                        {{ $sisaItem <= 0 ? 'LUNAS' : 'BELUM LUNAS' }}
                    </td>
                @endif
            </tr>
        @endforeach
    
    @endforeach

<tr style="font-weight:bold; background:#eef4ff;">
    <td></td>
    <td>TOTAL</td>
    <td></td>
    <td></td>
    <td align="right">{{ number_format($grandBayar, 2) }}</td>
    <td align="right">{{ number_format($grandKewajiban, 2) }}</td>
    <td align="right">{{ number_format($grandKewajiban - $grandBayar, 2) }}</td>
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
          Rochma Anie
        </div>
      </div>
    </div>

  </div>
</body>

</html>