@extends('layouts.app2')

@section('main-content')
<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="card mb-2">
      <div class="card-body">

        <!-- Modal Tambah -->
        <div class="modal fade" id="modalTambah" data-backdrop="static" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form action="{{ route('mahasiswa.store') }}" method="post">
                @csrf
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>NIM</label>
                      <input type="text" name="str_nim" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Nama</label>
                      <input type="text" name="str_nm_mhs" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Jurusan</label>
                      <select name="str_jurusan" class="form-control">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusan as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                        @endforeach
                      </select>
                    </div>



                    <div class="form-group col-md-6">
                      <label>Status</label>
                      <select name="bol_lulus" id="bol_lulus" class="form-control">
                        <option value="">-- Pilih Status --</option>
                        <option value="0">Aktif</option>
                        <option value="1">Lulus</option>
                        <option value="2">DO</option>
                        <option value="3">Keluar</option>
                        <option value="4">Meninggal</option>
                        <option value="5">Mengundurkan Diri</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Angkatan</label>
                      <input type="text" name="str_angkatan" class="form-control" maxlength="4">
                    </div>
                    <div class="form-group col-md-12">
                      <label>Alamat</label>
                      <input type="text" name="str_alamat" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>HP</label>
                      <input type="text" name="str_hp_mhs" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Email</label>
                      <input type="email" name="str_imel_mhs" class="form-control">
                    </div>




                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal Ubah -->
        @foreach ($data as $mhs)
        <div class="modal fade modalPendaftaran" id="ubah-{{$mhs->id}}" data-backdrop="static" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form action="{{ route('mahasiswa.update', $mhs->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>NIM</label>
                      <input type="text" name="str_nim" value="{{$mhs->str_nim}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Nama</label>
                      <input type="text" name="str_nm_mhs" value="{{$mhs->str_nm_mhs}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Jurusan</label>
                      <select name="str_jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($jurusan as $jrs)
                        <option value="{{ $jrs->kd_prodi }}"
                          {{ $mhs->str_jurusan == $jrs->kd_prodi ? 'selected' : '' }}>
                          {{ $jrs->nama_jurusan }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Angkatan</label>
                      <input type="text" name="str_angkatan" value="{{$mhs->str_angkatan}}" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                      <label>Alamat</label>
                      <input type="text" name="str_alamat" value="{{$mhs->str_alamat}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>HP</label>
                      <input type="text" name="str_hp_mhs" value="{{$mhs->str_hp_mhs}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Email</label>
                      <input type="email" name="str_imel_mhs" value="{{$mhs->str_imel_mhs}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Kampus</label>
                      <input type="text" name="str_campus" value="{{$mhs->str_campus}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Status Kelas</label>
                      <select name="bol_sts_kelas" class="form-control">
                        <option value="1" {{ $mhs->bol_sts_kelas == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $mhs->bol_sts_kelas == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                      </select>
                    </div>


                    <div class="form-group col-md-6">
                      <label>Status </label>
                      <select name="bol_lulus" id="bol_lulus" class="form-control">
                        <option value="">-- Pilih Status --</option>
                        <option value="0" {{ $mhs->bol_lulus == 0 ? 'selected' : '' }}>Aktif</option>
                        <option value="1" {{ $mhs->bol_lulus == 1 ? 'selected' : '' }}>Lulus</option>
                        <option value="2" {{ $mhs->bol_lulus == 2 ? 'selected' : '' }}>DO</option>
                        <option value="3" {{ $mhs->bol_lulus == 3 ? 'selected' : '' }}>Keluar</option>
                        <option value="4" {{ $mhs->bol_lulus == 4 ? 'selected' : '' }}>Meninggal</option>
                        <option value="5" {{ $mhs->bol_lulus == 5 ? 'selected' : '' }}>Mengundurkan Diri</option>
                      </select>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Nama Pendaftar</label>
                      <input type="text" id="nama_lengkap" class="form-control nama_lengkap" value="{{$mhs->pendaftaran->nama_lengkap ?? '' }}">
                      <input type="text" class="pendaftaran_id" name="id_kuliah_kerja" value="{{$mhs->pendaftaran->id_kuliah_kerja ?? ''}}" readonly>
                    </div>


                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  <button class="btn btn-primary" type="submit">Ubah</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        @endforeach

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">Data Mahasiswa</h1>
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
        </div>

       {{-- Panel Aksi Mahasiswa --}}
<div class="student-action-panel mb-4">

    <div class="student-action-header">
        <div class="student-action-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        <div>
            <h5 class="mb-1">Pengelolaan Mahasiswa</h5>
            <p class="mb-0">Pilih data yang ingin diperbarui, lalu klik tombol aksi.</p>
        </div>
    </div>

    <div class="student-action-grid">

        {{-- Status Mahasiswa --}}
        <div class="student-action-card status-card">
            <div class="action-card-heading">
                <div class="action-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h6>Status Mahasiswa</h6>
                    <small>Perbarui status akademik</small>
                </div>
            </div>

            <div class="action-card-body">
                <select id="statusSelect" class="form-control">
                    <option value="">-- Pilih Status --</option>
                    <option value="0">Aktif</option>
                    <option value="1">Lulus</option>
                    <option value="2">DO</option>
                    <option value="3">Keluar</option>
                    <option value="4">Meninggal</option>
                    <option value="5">Mengundurkan Diri</option>
                </select>

                <button id="btnUpdateStatus" type="button" class="btn-action btn-status">
                    <i class="fas fa-sync-alt"></i>
                    <span>Ubah Status</span>
                </button>
            </div>
        </div>

        {{-- Negara Tujuan --}}
        <div class="student-action-card negara-card">
            <div class="action-card-heading">
                <div class="action-icon">
                    <i class="fas fa-globe-asia"></i>
                </div>
                <div>
                    <h6>Negara Tujuan</h6>
                    <small>Perbarui negara tujuan mahasiswa</small>
                </div>
            </div>

            <div class="action-card-body">
                <select id="negaraTujuanSelect" class="form-control">
                    <option value="">-- Pilih Negara --</option>

                    @foreach ($negara_tujuan as $negara)
                        <option value="{{ $negara->id }}">
                            {{ $negara->nama_negara }}
                        </option>
                    @endforeach
                </select>

                <button id="btnUpdateNegaraTujuan" type="button" class="btn-action btn-negara">
                    <i class="fas fa-globe"></i>
                    <span>Ubah Negara</span>
                </button>
            </div>
        </div>

        {{-- Status Berangkat --}}
        <div class="student-action-card berangkat-card">
            <div class="action-card-heading">
                <div class="action-icon">
                    <i class="fas fa-plane-departure"></i>
                </div>
                <div>
                    <h6>Status Berangkat</h6>
                    <small>Perbarui status keberangkatan</small>
                </div>
            </div>

            <div class="action-card-body">
                <select id="statusBerangkatSelect" class="form-control">
                    <option value="">-- Pilih Status --</option>

                    @foreach ($status_berangkat as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->status }}
                        </option>
                    @endforeach
                </select>

                <button id="btnUpdateStatusBerangkat" type="button" class="btn-action btn-berangkat">
                    <i class="fas fa-check-circle"></i>
                    <span>Ubah Status</span>
                </button>
            </div>
        </div>

        {{-- Status LN / REG --}}
        <div class="student-action-card jenis-card">
            <div class="action-card-heading">
                <div class="action-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div>
                    <h6>Jenis Mahasiswa</h6>
                    <small>Reguler atau luar negeri</small>
                </div>
            </div>

            <div class="action-card-body">
                <select id="statusSelectLn" class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="1">Reguler</option>
                    <option value="2">Luar Negeri</option>
                </select>

                <button id="btnUpdateStatusLn" type="button" class="btn-action btn-jenis">
                    <i class="fas fa-sync-alt"></i>
                    <span>Ubah Jenis</span>
                </button>
            </div>
        </div>

    </div>
</div>


        <table class="table table-striped" id="mahasiswa">
          <thead>
            <tr>
              <th>No</th>
              <th><input type="checkbox" id="selectAll"></th>

              <th>NIM</th>
              <th>Nama</th>
              <th>Status</th>
              <th>REG / LN</th>
              <th>Negara Tujuan</th>
              <th>Status Berangkat</th>

              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>
    </div>
  </div>
</div>
<style>
  .ui-autocomplete {
    z-index: 1055 !important;
  }
</style>



<!-- JQUERY (WAJIB PALING ATAS) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JQUERY UI -->
<link rel="stylesheet"
  href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>





<script>
  var table;

  function confirm_delete(target) {
    Swal.fire({
      title: 'Yakin Menghapus?',
      text: "Data akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "DELETE",
          url: target,
          data: {
            _token: '{{csrf_token()}}'
          },
          success: function() {
            Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
            table.draw();
          }
        });
      }
    })
  }

  $(document).ready(function() {
    table = $('#mahasiswa').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('mahasiswa.index') }}",
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          orderable: false,
          searchable: false
        },

        {
          data: 'checkbox',
          orderable: false,
          searchable: false
        },

        {
          data: 'str_nim',
          name: 'str_nim'
        },
        {
          data: 'str_nm_mhs',
          name: 'str_nm_mhs'
        },
        {
          data: 'bol_lulus',
          name: 'bol_lulus',
          render: function(data, type, row) {

            console.log('DATA STATUS:', data);
            switch (data) {
              case 0:
                return 'Aktif';
              case 1:
                return 'Lulus';
              case 2:
                return 'DO';
              case 3:
                return 'Keluar';
              case 4:
                return 'Meninggal';
              case 5:
                return 'Mengundurkan Diri';
              default:
                return '-';
            }
          }
        },

        {
          data: 'is_luarnegeri',
          name: 'is_luarnegeri',
          render: function(data) {
            if (data == 1) return 'Reguler';
            if (data == 2) return 'Luar Negeri';
            return '-';
          }
        },
        // { data: 'str_jurusan', name: 'str_jurusan' },
        //  { data: 'str_angkatan', name: 'str_angkatan' },
        // { data: 'status', name: 'status' },

        {
            data: 'nama_negara',
            name: 'negaraTujuan.nama_negara',
            defaultContent: '-'
        },
        {
            data: 'status_berangkat',
            name: 'statusBerangkat.status',
            defaultContent: '-'
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
        
      ]
    });
  });

  $('#btnUpdateStatus').click(function() {
    let selected = [];
    $('.row-checkbox:checked').each(function() {
      selected.push($(this).val());
    });
    
    let newStatus = $('#statusSelect').val();

    if (selected.length === 0) {
      alert('Pilih minimal satu mahasiswa!');
      return;
    }
    if (!newStatus) {
      alert('Pilih status baru terlebih dahulu!');
      return;
    }

    $.ajax({
      url: "{{ url('updateBolLulus') }}",
      method: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        ids: selected,
        status: newStatus
      },
      success: function(res) {
        if (res.success) {
          alert('Status berhasil diperbarui!');
          table.ajax.reload(null, false);
        } else {
          alert(res.message || 'Gagal memperbarui status.');
        }
      },
      error: function(err) {
        console.log(err);

        alert('Terjadi kesalahan saat memperbarui status.');
      }
    });
  });


  $('#btnUpdateStatusLn').click(function() {
    let selected = [];
    $('.row-checkbox:checked').each(function() {
      selected.push($(this).val());
    });
  

    let newStatus = $('#statusSelectLn').val();

    if (selected.length === 0) {
      alert('Pilih minimal satu mahasiswa!');
      return;
    }
    if (!newStatus) {
      alert('Pilih status baru terlebih dahulu!');
      return;
    }

    $.ajax({
      url: "{{ url('updateLn') }}",
      method: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        ids: selected,
        status: newStatus
      },
      success: function(res) {
        if (res.success) {
          alert('Status berhasil diperbarui!');
          table.ajax.reload(null, false);
        } else {
          alert(res.message || 'Gagal memperbarui status.');
        }
      },
      error: function(err) {

        console.log(err);

        alert('Terjadi kesalahan saat memperbarui status.');
      }
    });
  });

  $(function() {
    $(".nama_lengkap").autocomplete({
      minLength: 2,
      source: function(request, response) {

        console.log('KEYWORD:', request.term);

        $.ajax({
          url: "{{ url('autocomplete') }}",
          dataType: "json",
          data: {
            search: request.term
          },
          success: function(data) {

            console.log('RESPONSE DARI SERVER:', data);
            //  console.table(data); // 🔥 paling enak dibaca

            response(data); // ini WAJIB
          },
          error: function(xhr) {
            console.log('ERROR:', xhr.responseText);
          }
        });
      },
      select: function(event, ui) {
        console.log('SELECTED ITEM:', ui.item);

        $(this).val(ui.item.label);
        $('.pendaftaran_id').val(ui.item.id);
        return false;
      }
    });
  });

  $('#btnUpdateStatusBerangkat').click(function() {
    let selected = [];

    $('.row-checkbox:checked').each(function() {
        selected.push($(this).val());
    });

    let newStatus = $('#statusBerangkatSelect').val();

    if (selected.length === 0) {
        alert('Pilih minimal satu mahasiswa!');
        return;
    }

    if (!newStatus) {
        alert('Pilih status berangkat terlebih dahulu!');
        return;
    }

    $.ajax({
        url: "{{ url('updateStatusBerangkat') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            ids: selected,
            status: newStatus
        },
        success: function(res) {
            if (res.success) {
                alert('Status berangkat berhasil diperbarui!');
                table.ajax.reload(null, false);
            } else {
                alert(res.message || 'Gagal memperbarui status berangkat.');
            }
        },
        error: function(err) {
            console.log(err);
            alert('Terjadi kesalahan saat memperbarui status berangkat.');
        }
    });
});

$('#btnUpdateNegaraTujuan').click(function() {
    let selected = [];

    $('.row-checkbox:checked').each(function() {
        selected.push($(this).val());
    });

    let negaraTujuan = $('#negaraTujuanSelect').val();

    if (selected.length === 0) {
        alert('Pilih minimal satu mahasiswa!');
        return;
    }

    if (!negaraTujuan) {
        alert('Pilih negara tujuan terlebih dahulu!');
        return;
    }

    $.ajax({
        url: "{{ url('updateNegaraTujuan') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            ids: selected,
            negara_tujuan: negaraTujuan
        },
        success: function(res) {
            if (res.success) {
                alert('Negara tujuan berhasil diperbarui!');
                table.ajax.reload(null, false);
            } else {
                alert(res.message || 'Gagal memperbarui negara tujuan.');
            }
        },
        error: function(err) {
            console.log(err);
            alert('Terjadi kesalahan saat memperbarui negara tujuan.');
        }
    });
});
</script>
@endsection