@extends('layouts.app2')
@section('main-content')

<div class="row">
  <!-- ICON BG -->
  <div class="col-md-12 col-lg-12">
    <div class="card mb-2">
      <div class="card-body">
        <h4 class="card-title mb-3">Informasi Pembayaran</h4>
        <div class="separator-breadcrumb border-top"></div>

        <div class="row">
          <!-- Start Date -->
        

          <!-- End Date -->
          

          <!-- Select Kelas -->

          <div class="col-md-3 col-xs-3">
            <select class="form-control" id="status_mahasiswa" name="status_mahasiswa">
              <option value="">-- Pilih Status --</option>
              <option value="aktif">Mahasiswa Aktif</option>
              <option value="nonaktif">Mahasiswa Tidak Aktif</option>
              <option value="all">Tampil Semua</option>
            </select>
          </div>

          <div class="col-md-3 col-xs-3">
            <select class="form-control" id="kelas" name="kelas">
              <option value="">Seluruh Kelas</option>
              <option value="0">Reguler</option>
              <option value="1">Karyawan</option>
              <option value="2">Internasional</option>
              <option value="4">Eksekutif Jimbaran</option>
              <option value="5">Inter MBC</option>
              <option value="6">DD Binus</option>
              <option value="7">Excelent Class</option>
              <option value="9">DD STTB</option>
            </select>

             <!-- Select Status Mahasiswa -->
         
        </div>

         
      </div>

         

        <br>

        <!-- Table -->
        <table id="table_siswa" class="stripe row-border order-column">
          <thead>
            <tr>
              <th>NIM</th>
              <th>Nama</th>
              <th>Prodi</th>
              <th>Kelas</th>
              <th>Angkatan</th>
              <th>No Telp/WA</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- end of main-content -->

<script src="{{ asset('vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>

<script>
  function statusTunggakan(nim) {
    var url = '{{ url("tunggakanMbc") }}/' + nim;
    window.open(url, '_blank');
  }

  function all_transaksi(nim) {
    var url = '{{ url("all_transaksi") }}/' + nim;
    window.open(url, '_blank');
  }

  $(document).ready(function () {
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var table_siswa = $('#table_siswa').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('create_datatable_mbc') }}",
        data: function (d) {
          d.cari = $('#cari').val();
          d.kelas = $('#kelas').val();
          d.status_mahasiswa = $('#status_mahasiswa').val();
        }
      },
      columns: [
        { data: 'str_nim', name: 'str_nim' },
        { data: 'str_nm_mhs', name: 'str_nm_mhs' },
        { data: 'str_nm_prodi', name: 'str_nm_prodi' },
        { 
          className: 'nama_kelas',
          data: 'bol_sts_kelas',
          render: (data) => {
            switch (data) {
              case 0: return 'Reguler';
              case 1: return 'Karyawan';
              case 2: return 'Internasional';
              case 3: return 'Eksekutif Jimbaran';
              case 5: return 'Inter MBC';
              case 6: return 'DD Binus';
              case 7: return 'Excelent Class';
              case 9: return 'DD STTB';
              default: return 'Unknown';
            }
          }
        },
        { data: 'str_angkatan', name: 'str_angkatan' },
        { data: 'str_hp_mhs', name: 'str_hp_mhs' },
        { data: 'action', name: 'action' }
      ],
      order: [[0, 'desc']]
    });

    // Event Filter
    $('#kelas, #status_mahasiswa').change(function () {
      table_siswa.draw(true);
    });

    $('#btnFiterSubmitSearch').click(function () {
      table_siswa.draw(true);
    });
  });
</script>
@endsection
