@extends('layouts.app2')
@section('main-content')

<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="card mb-2">
      <div class="card-body">

        <!-- Judul -->
        <h4 class="card-title mb-3">Informasi Pembayaran</h4>
        <div class="separator-breadcrumb border-top"></div>

        <!-- Filter -->
  <div class="row mb-3 align-items-end">

  <!-- Form Status Mahasiswa -->
  <div class="col-md-4">
    <form action="{{ url('create_pdf_admin') }}" method="POST" target="_blank" class="w-100">
      @csrf
      <div class="d-flex gap-2">
        <select class="form-control" id="status_mahasiswa" name="status_mahasiswa">
          <option value="">-- Pilih Status --</option>
          <option value="aktif">Mahasiswa Aktif</option>
          <option value="cuti">Mahasiswa Cuti</option>
           <option value="nonaktif">Mahasiswa Tidak Aktif</option>
          <option value="do">Mahasiswa DO</option>
          <option value="lulus">Mahasiswa Lulus</option>
          <option value="ln">Luar Negeri</option>
           <option value="reg">Reguler</option>
          <option value="005">Bisnis Digital</option>
          <option value="001">Sistem Komputer</option>
          <option value="003">Sistem Informasi</option>
          <option value="004">Teknologi Informasi</option>
          <option value="002">D3-MANAJEMEN INFORMATIKA</option>
          <option value="201">S2-SISTEM INFORMASI</option>



        </select>
        <button type="submit" class="btn btn-danger">
          <i class="fa fa-file-pdf-o"></i> Cetak PDF
        </button>
      </div>
    </form>
  </div>
  
  

  <!-- Form Pilih Tahun -->
  <div class="col-md-5">
    <form action="{{ url('tagihan_periode_pdf') }}" method="POST" target="_blank" class="w-100">
      @csrf
      <div class="row g-2 align-items-end">
        <div class="col-md-4">
          <select name="tahun" id="tahun" class="form-control">
            <option value="">-- Pilih Tahun --</option>
            @php
              $tahunSekarang = date('Y');
              $tahunMulai = 2018;
            @endphp
            @for ($t = $tahunSekarang; $t >= $tahunMulai; $t--)
              <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                {{ $t }}
              </option>
            @endfor
          </select>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-file-earmark-pdf"></i> Cetak
          </button>
        </div>
      </div>
    </form>
  </div>

</div>




        <!-- Table -->
        <table id="table_siswa" class="stripe row-border order-column">
          <thead>
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Status</th>
              <th>REG / LN</th>          
              <th>Action</th>
            </tr>
          </thead>
        </table>

      </div>
    </div>
  </div>
</div>




@include('livewire.keuangan_mbc.script')
@endsection