<div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
 

   {{-- ✅ Tampilkan notifikasi error/success di sini --}}
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <strong>Perhatian!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif


   
     <div class="container">
        <h3>Dokumen a/n  <?= $nama; ?></h3>
        <form action="{{ url('simpanDokumen') }}" method="POST" enctype="multipart/form-data">
          @csrf
        <input type="hidden" name="nim" value="{{ request()->segment(2)  }}" class="form-control">
          <div id="fileWrapper">
              
            <div class="mb-3 file-item">
              <label class="form-label">Nama Dokumen</label>
              <input type="text" name="nama_dokumen[]" class="form-control">
            </div>
            <div class="mb-3 file-item">
              <label class="form-label">Pilih File</label>
              <input type="file" name="dokumen[]" class="form-control" required>
            </div>
            
           
            
            
          </div>
        
          <div class="mb-3">
            <button type="button" class="btn btn-success" onclick="addFileInput()">
              + Tambah File
            </button>
          </div>
        
          <button class="btn btn-primary" type="submit">Simpan</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
    </div>
    
    
    <hr>

        <h4 class="mt-4">Daftar Dokumen</h4>
        
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th width="5%">No</th>
                <th>Nama Dokumen</th>
                <th width="15%">Link</th>
                <th width="15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($dokumen as $i => $row)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $row->nama_dokumen }}</td>
                  <td>
                    <a href="{{ url('download/'.$row->id) }}"
                       class="btn btn-sm btn-info">
                      Download
                    </a>
                  </td>
                  <td>
                    <form action="{{ url('hapusDokumen/'.$row->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin hapus dokumen ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger">
                        Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">
                    Belum ada dokumen
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>




 
</div>