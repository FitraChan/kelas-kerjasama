@extends('layouts.app2')
@section('main-content')

<div class="container py-4">
    <h3 class="mb-3">Daftar Status Mahasiswa</h3>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <button class="btn btn-primary mb-2" id="addBtn">Tambah Data</button>

    <!-- Dropdown ubah status -->
    <div class="d-flex align-items-center gap-2 mb-2">
      <select id="statusSelect" class="form-control" style="width: 200px;">
        <option value="">-- Pilih Status Baru --</option>
        <option value="1">Aktif</option>
        <option value="2">Cuti</option>
        <option value="3">Tidak Aktif</option>
        <option value="4">Telah Lulus</option>
      </select>
      <button id="btnUpdateStatus" class="btn btn-success">Ubah Status</button>
    </div>
  

   
  </div>

  <!-- Filter status mahasiswa -->
  <div class="d-flex align-items-center gap-2 mb-3">
    @csrf
    <select class="form-control" id="status_mahasiswa" name="status_mahasiswa" style="width: 200px;">
      <option value="">-- Pilih Status --</option>
      <option value="aktif">Mahasiswa Aktif</option>
      <option value="nonaktif">Mahasiswa Tidak Aktif</option>
    </select>
  </div>

    <table class="stripe row-border order-column" id="statusTable">
        <thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" id="selectAll"></th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Tahun Ajaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

        <form id="statusForm">
            @csrf
            <input type="hidden" id="id" name="id">
            <div class="modal-content">
                
                    <!-- <h5 class="modal-title" id="modalTitle">Tambah Data</h5> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                
                <div class="modal-body">
                   <div class="form-group mb-3">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" class="form-control">
                </div>

                  
                    <div class="mb-2">
                        <label>Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="2">Cuti</option>
                            <option value="3">Tidak Aktif</option>
                            <option value="4">Telah Lulus</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-3">
                    <label for="thn_ajaran">Tahun Ajaran</label>
                    <select id="id_thn_ajaran" name="id_thn_ajaran" class="form-control">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($thn_ajaran as $ta)
                            <option value="{{ $ta->id }}">{{ $ta->thn_ajaran }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
  </div>

</div>


<script src="{{ asset('public/vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

var table;
$(document).ready(function () {
     table = $('#statusTable').DataTable({
         processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: "{{ route('status-mahasiswa.index') }}",
             data: function (d) {
         
                 d.status_mahasiswa = $('#status_mahasiswa').val();
             }
        },
       
        columns: [
            {
          data: 'DT_RowIndex',
          name: 'DT_Row_Index',
          orderable: false,
          searchable: false
        },
            {data: 'checkbox', orderable: false, searchable: false},
            {data: 'nim', name: 'nim'},            
            {data: 'nama_mahasiswa', name: 'mahasiswa.str_nm_mhs'},            
            {data: 'status', name: 'status'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'id_thn_ajaran', name: 'id_thn_ajaran'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

     $('#status_mahasiswa').change(function () {
      table.draw(true);
    });

    // Tambah data
    $('#addBtn').click(function () {
        $('#statusForm')[0].reset();
        $('#id').val('');
        $('#modalTitle').text('Tambah Data');
         $('#nim').closest('.form-group').hide();
        $('#statusModal').modal('show');
    });

    // Simpan (Tambah / Update)
   $('#statusForm').submit(function (e) {
    e.preventDefault();

    let id = $('#id').val();
    let url = id ? `/status-mahasiswa/${id}` : "{{ route('status-mahasiswa.store') }}";
    let method = id ? 'PUT' : 'POST' ;
    let formData = $(this).serializeArray().filter(obj => obj.name !== '_token');

    $.ajax({
        url: url,
        method: method,
        data: $.param(formData),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            $('#statusModal').modal('hide');
            table.ajax.reload();
        },
        error: function (xhr) {
            alert('Terjadi kesalahan.');
        }
    });
});


    // Edit
    $('#statusTable').on('click', '.editBtn', function () {
        let id = $(this).data('id');
        $.get(`/status-mahasiswa/${id}`, function (data) {

            console.log(data.id_thn_ajaran);
            
            $('#id').val(data.id);
            $('#nim').val(data.nim);
            $('#status').val(data.status);
            $('#keterangan').val(data.keterangan);
            $('#id_thn_ajaran').val(data.id_thn_ajaran);
           // $('#modalTitle').text('Edit Data');
            $('#statusModal').modal('show');
        });
    });

    // Hapus
    $('#statusTable').on('click', '.deleteBtn', function () {
        if (confirm('Yakin ingin menghapus data ini?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/status-mahasiswa/${id}`,
                method: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function () {
                    table.ajax.reload();
                }
            });
        }
    });
});

$('#btnUpdateStatus').click(function () {
    let selected = [];
    $('.row-checkbox:checked').each(function () {
        selected.push($(this).val());
    });

    console.log(selected);
    

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
        url: "{{ route('status-mahasiswa.update-mass') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            ids: selected,
            status: newStatus
        },
        success: function (res) {
            if (res.success) {
                alert('Status berhasil diperbarui!');
                table.ajax.reload(null, false);
            } else {
                alert(res.message || 'Gagal memperbarui status.');
            }
        },
        error: function () {
            alert('Terjadi kesalahan saat memperbarui status.');
        }
    });
});
</script>

@endsection
