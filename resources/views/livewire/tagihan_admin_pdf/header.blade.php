<header class="header">
  <img src="{{ public_path('images/logo-mbc_2.png') }}" alt="logo" class="logo">
  <div>
    <h1>REKAP KEWAJIBAN MAHASISWA</h1>
    <div class="muted">Rekap otomatis — {{ date('d M Y') }}</div>
  </div>
</header>

<section class="meta">
  <div class="info-vertical">
       <div><span class="label">Reguler</span>: {{ $reg ?? '-' }}</div>

       <div><span class="label">Luar Negeri</span>: {{ $ln ?? '-' }}</div>

      <div><span class="label">Total</span>: {{ $all ?? '-' }}</div>
      
      <?php if($status_mahasiswa == '001' || $status_mahasiswa == '002' || $status_mahasiswa == '003' || $status_mahasiswa == '004' || $status_mahasiswa == '005' || $status_mahasiswa == '201'){ ?>
      
                 <h3> Jurusan  {{ $jurusan }}  </h3>

<?php } ?>

  </div>
</section>
