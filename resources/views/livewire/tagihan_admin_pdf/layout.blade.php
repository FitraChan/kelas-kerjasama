<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rekap Kewajiban Mahasiswa - {{ $atas->str_nim ?? '' }}</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 20px; background: #fff; }
    .container { max-width: 900px; margin: 0 auto; }
    .header { display: flex; align-items: center; gap: 12px; }
    .logo { width: 230px; }
    h1 { font-size: 18px; margin: 0; }
    .muted { color: #666; font-size: 12px; }
    .info-vertical div { margin: 4px 0; font-size: 14px; }
    .label { display: inline-block; width: 120px; font-weight: 600; }
    table { width: 100%; border-collapse: collapse; margin-top: 18px; font-size: 13px; }
    table thead th { background: #f3f6fb; border: 1px solid #d3dbe8; padding: 8px; text-align: left; font-weight: 700; }
    table tbody td { border: 1px solid #e3e8ef; padding: 8px; vertical-align: middle; }
    table tbody tr:nth-child(even) { background: #fbfdff; }
    .text-right { text-align: right; }
    .center { text-align: center; }
    .sign { margin-top: 28px; display: flex; justify-content: space-between; align-items: flex-end; font-size: 13px; }
    .sign .col { width: 45%; text-align: center; }
    .sign .date { margin-top: 10px; margin-bottom: 20px; }
  </style>
</head>
<body>
  <div class="container">
    @yield('content')
  </div>
</body>
</html>
