<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rekap Kewajiban Mahasiswa - {{ $atas->str_nim ?? '' }}</title>
  <style>
    @page {
      size: A4 landscape;
      margin: 15mm;
    }

    /* Saat print */
    @media print {
      body {
        font-family: Arial, sans-serif;
        font-size: 12px; /* 🔹 ukuran font untuk cetak (lebih besar & terbaca jelas) */
        -webkit-print-color-adjust: exact;
      }
      .sheet {
        width: 100%;
        height: 100%;
      }
    }

    /* Saat dilihat di layar */
    body {
      font-family: Arial, sans-serif;
      font-size: 12px; /* 🔹 ukuran font default di layar */
      background: #fff;
    }

    .sheet {
      box-shadow: 0 0 10px rgba(0,0,0,.1);
      padding: 20px;
      background: #fff;
      min-height: 297mm; /* tinggi A4 */
      width: 420mm;      /* lebar A4 landscape */
      margin: 20px auto;
    }

    .container { max-width: 900px; margin: 0 auto; }
    .header { display: flex; align-items: center; gap: 12px; }
    .logo { width: 200px; }
    h1 { font-size: 18px; margin: 0; }
    .muted { color: #666; font-size: 11px; }
    .info-vertical div { margin: 3px 0; font-size: 11px; }
    .label { display: inline-block; width: 100px; font-weight: 600; }

    /* 🔹 Ukuran tabel agak lebih besar dari sebelumnya */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      font-size: 11px;
    }
    table thead th {
      background: #f3f6fb;
      border: 1px solid #d3dbe8;
      padding: 6px;
      text-align: left;
      font-weight: 700;
    }
    table tbody td {
      border: 1px solid #e3e8ef;
      padding: 6px;
      vertical-align: middle;
    }
    table tbody tr:nth-child(even) { background: #fbfdff; }

    .text-right { text-align: right; }
    .center { text-align: center; }

    .sign {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      font-size: 11px;
    }
    .sign .col { width: 45%; text-align: center; }
    .sign .date { margin-top: 8px; margin-bottom: 15px; }
  </style>
</head>
<body>
  <div class="container">
    @yield('content')
  </div>
</body>
</html>
