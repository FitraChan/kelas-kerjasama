@extends('layouts.app2')
@section('main-content')



<div class="container mt-5">

    <div class="modal fade" id="modalAdd">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formAdd">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Tahun Ajaran</label>
                            <input type="text" name="thn_ajaran" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Aktif (0/1)</label>
                            <input type="number" name="isaktiv" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formEdit">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tahun Ajaran</h5>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-2">
                            <label>Tahun Ajaran</label>
                            <input type="text" name="thn_ajaran" id="edit_thn" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Aktif (0/1)</label>
                            <input type="number" name="isaktiv" id="edit_aktif" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>

                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modalDelete">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formDelete">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Data</h5>
                    </div>

                    <div class="modal-body">
                        <p>Yakin ingin menghapus data ini?</p>
                        <input type="hidden" name="id" id="delete_id">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>

                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <h3>Data Tahun Ajaran</h3>
    <button class="btn btn-primary mb-3"
        data-toggle="modal"
        data-target="#modalAdd">
        Tambah
    </button>

    <!-- TABEL -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tahun Ajaran</th>
                <th>Aktif</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->thn_ajaran }}</td>
                <td>{{ $d->isaktiv == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>
                    <button class="btn btn-warning btnEdit"
                        data-id="{{ $d->id }}"
                        data-thn="{{ $d->thn_ajaran }}"
                        data-aktif="{{ $d->isaktiv }}"
                        data-toggle="modal"
                        data-target="#modalEdit">
                        Edit
                    </button>

                    <button class="btn btn-danger btnDelete"
                        onclick="confirm_delete('<?= $d->id ?>')">
                        Hapus
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<script src="{{ asset('public/vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Tambah
    $('#formAdd').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('thn_ajaran.store') }}", // route resource untuk store
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
            location.reload();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
})

    // Show edit data
    $('.btnEdit').click(function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_thn').val($(this).data('thn'));
        $('#edit_aktif').val($(this).data('aktif'));
    });

    // Update
    $('#formEdit').submit(function(e) {
    e.preventDefault();

    let id = $('#edit_id').val();

    $.ajax({
        url: "{{ url('thn_ajaran') }}/" + id,
        type: "PUT",
        data: $(this).serialize(),

        success: function(response) {
            location.reload();
        },

        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});


    // Hapus
    $('.btnDelete').click(function() {
        $('#delete_id').val($(this).data('id'));
    });

   function confirm_delete(id) {

    Swal.fire({
        title: 'Yakin Menghapus?',
        text: 'Pastikan data yang kamu pilih benar.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {

            let url = "{{ route('thn_ajaran.destroy', ':id') }}"
                .replace(':id', id);

            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function(response) {

                    Swal.fire({
                        title: 'Terhapus!',
                        text: 'Data berhasil dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });

                },

                error: function(xhr) {

                    console.log(xhr.responseText);

                    Swal.fire(
                        'Gagal!',
                        'Data gagal dihapus.',
                        'error'
                    );

                }
            });

        }

    });
}
</script>






@endsection