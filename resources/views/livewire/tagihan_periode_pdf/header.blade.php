<header class="header">
  <img src="{{ public_path('images/logo-mbc_2.png') }}" alt="logo" class="logo">

  <div>
    <h1>REKAP KEWAJIBAN MAHASISWA</h1>

    <div class="muted">
      @if(!empty($tahun))
        Tahun Akademik: <strong> <b >{{ $tahun }} </b></strong>
      @else
        Semua Tahun
      @endif
      <br>
      Rekap otomatis — {{ date('d M Y') }}
    </div>
  </div>
</header>
