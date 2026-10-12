@extends('layouts.app2')
@section('main-content')


<div class="row">

  <div class="col-md-12 col-lg-12">
    <div class="card mb-2">
      <div class="card-body">
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{route('ipaymu.store')}}" method="post">
                @csrf
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Nama Item</label>
                      <input type="text" name="nama_item" class="form-control" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Keterangan</label>
                      <input type="text" name="keterangan" class="form-control" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Nominal</label>
                      <input type="number" name="def_value" class="form-control" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Angkatan</label>
                      <input type="number" name="str_angkatan" class="form-control" maxlength="4" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="id_kategori">Kategori</label>
                      <select name="id_kategori" id="id_kategori" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="1">Pendaftaran</option>
                        <option value="2">Semesteran</option>                        
                        <option value="3">Cuti</option>
                        
                      </select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="riset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        @foreach ($data as $dp)
        <div class="modal fade" id="ubah-{{$dp->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ubah-{{$dp->id}}Label" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ubah-{{$dp->id}}Label">Ubah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{route('ipaymu.update', $dp->id)}}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Nama Item</label>
                      <input type="text" name="nama_item" value="{{$dp->nama_item}}" class="form-control" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Keterangan</label>
                      <input type="text" name="keterangan" value="{{$dp->keterangan}}" class="form-control" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Nominal</label>
                      <input type="number" name="def_value" value="{{$dp->def_value}}" class="form-control" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="inputEmail4">Angkatan</label>
                      <input type="number" name="str_angkatan" value="{{$dp->str_angkatan}}" class="form-control" maxlength="4" id="inputEmail4">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="id_kategori">Kategori</label>
                     <select name="id_kategori" id="id_kategori" class="form-control">
    <option value="">-- Pilih Kategori --</option>
    <option value="1" {{ $dp->id_kategori == 1 ? 'selected' : '' }}>Pendaftaran</option>
    <option value="2" {{ $dp->id_kategori == 2 ? 'selected' : '' }}>Semesteran</option>
        <option value="3" {{ $dp->id_kategori == 3 ? 'selected' : '' }}>Cuti</option>

  </select>
                    </div>
                  </div>
                </div>
           
            <div class="modal-footer">
              <button type="riset" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
            </form>
             </div>
          </div>
        </div>
     
      @endforeach





      <!-- Page Heading -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Item Bayar</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
          Tambah
        </button>

      </div>


      <table class="stripe row-border order-column" id="ipaymu">
        <thead> 
          <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Item</th>
              <th>Nominal</th>
              <th>Status Verif</th>
              <th>Status Export</th>          
              <th>Action</th>
          </tr>
        </thead>

        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<script src="{{ asset('vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var table;
   function confirm_export(id) {
            Swal.fire({
                    title: 'Yakin EXPORT ??',
                    text: "Pastikan Yang Kamu Pilih Benar",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: "{{ url('export_pembayaran') }}/"+id,
                            data:{
                                _token: '{{csrf_token()}}'
                            },
                            success: function(url){
                                console.log(url);
                                Swal.fire(
                                    'Berhasil!',
                                    'Data Berhasil Di Export !!',
                                    'success'
                                ),
                                table.draw();
                            }
                        });

                    }
                })
        }
  $(document).ready(function() {
     table = $('#ipaymu').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: {
        url: "{{ route('ipaymu.index') }}",
      },
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_Row_Index',
          orderable: false,
          searchable: false
        },

         {
          data: 'nim',
          name: 'nim'
        },
        {
          data: 'mahasiswa.str_nm_mhs',
          name: 'str_nm_mhs'
        },
        {
          data: 'detail_bayar.nama_item',
          name: 'nama_item'
        },       
        {
            data: 'detail_bayar.jml_bayar',
            name: 'jml_bayar',
            render: function(data, type, row) {
                if (data == null) return '';
                return 'Rp ' + parseInt(data).toLocaleString('id-ID');
            }
        },
          {
          data: 'transaction.status',
          name: 'status'
        }, 
        {
          data: 'sts_bayar',
          name: 'sts_bayar',
          render: function (data) {
          if (data == 1) return 'Sukses';
          if (data == 2) return 'Pending';
          if (data == 3) return 'Gagal';
          if (data == 4) return 'Expired';
          return '-';
        }
        },       
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
      ],
    });
  });
</script>



@endsection