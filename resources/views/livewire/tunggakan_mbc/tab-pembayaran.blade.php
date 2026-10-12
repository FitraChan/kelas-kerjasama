<div class="tab-pane fade" id="pembayaran" role="tabpanel" aria-labelledby="pembayaran-tab">
  <div class="row">
    <div class="col-md-6 col-lg-6">
      <div class="card mb-2">
        <div class="card-body">
          <div class="form-group row">
            <label for="nim" class="col-sm-2 col-form-label">NIM</label>
            <div class="col-sm-10">
              <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukkan NIM">
            </div>
          </div>
          <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
              <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama">
            </div>

            
          </div>


           <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Smester</label>
            <div class="col-sm-10">
              <input type="text" id="semester_id" name="semester_id" class="form-control" placeholder="Masukkan Nama">
            </div>

            
          </div>

           <div class="modal fade" id="editTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Edit Detail Transaksi</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form id="editTransaksiModalForm">
                  <div class="modal-body">

                    <input type="hidden" id="edit_id_bayar_transaksi">

                    

                    <!-- <div class="form-group" id="form_tambah_item">-->
                    <!--  <label for="tambah_id_item">Pilih Item</label>-->
                    <!--  <select name="kategori_id" id="kategori_id" class="form-control">-->
                    <!--    <option value="">-- Pilih --</option>-->
                    <!--      <option value="1">Reguler</option>-->
                    <!--      <option value="2">Luar Negeri</option>-->
                    <!--  </select>-->
                    <!--</div>-->
                    
                    
                    <div class="form-group">
                        <label>Bank Pengirim</label>
                        <select class="form-control" name="id_bank" id="bank">
                            <option value="">-- Pilih Bank --</option>

                            @foreach ($bank as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->nama_bank }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    
                    
                    <div class="form-group">
                        <label>Bank Penerima</label>
                        <select class="form-control" name="bank_penerima" id="bank_penerima">
                            <option value="">-- Pilih Bank --</option>

                            @foreach ($bank_penerima as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->nama_bank }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!--<div class="form-group">-->
                    <!--  <label>Keterangan</label>-->
                    <!--  <input type="text" class="form-control" id="asal" name="asal">-->
                    <!--</div>-->


                     <div class="form-group">
                      <label>Keterangan Simple</label>
                      <input type="text" class="form-control" id="ket_simple" name="ket_simple">
                    </div>


                    <div class="form-group">
                      <label>Tanggal Bayar</label>
                      <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar">
                    </div>

                     <div class="form-group">
                      <label>Semester</label>
                      <input type="text" class="form-control" id="id_semester" name="id_semester">
                    </div>
                    
                     <div class="form-group">
                      <label>Gambar</label>
                      <input type="file" class="form-control" id="gambar" name="gambar">
                    </div>



                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </form>

              </div>
            </div>
          </div>

          <h4 class="card-title mb-3">Informasi Pembayaran</h4>
          <table id="transaksi" class="stripe row-border order-column" style="width:100%">
            <thead>
              <tr>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Tanggal Bayar</th>
                <th>Jumlah</th>
                <th>Kewajiban</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Bukti</th>
                <th>Hps Gambar</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-6">
      <div class="card mb-2">
        <div class="card-body">

         <button type="button" class="btn btn-success mb-3" id="btnTambah">
          Tambah Detail
        </button>

          <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Edit Detail Transaksi</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form id="editForm">
                  <div class="modal-body">
                    <input type="hidden" id="edit_id">

                    <input type="hidden" id="edit_id_bayar">

                    <input type="hidden" id="edit_id_item">



                    <div class="form-group">
                      <label>Item</label>
                      <input type="text" class="form-control" id="edit_item">
                    </div>
                    
                    <!-- <div class="form-group" id="form_tambah_item">-->
                    <!--  <label for="tambah_id_item">Pilih Item</label>-->
                    <!--  <select name="edit_item" id="edit_item" class="form-control">-->
                    <!--    <option value="0">-- Pilih Item --</option>-->
                      
                    <!--      <option value="9">DPP</option>-->
                    <!--      <option value="8">Biaya Orientasi Jaket & Almamater </option>-->
                    <!--      <option value="7"> Pendaftaran</option>-->
                        
                       
                    <!--  </select>-->
                    <!--</div>-->

                    <div class="form-group">
                      <label>Jumlah Bayar</label>
                      <input type="text"  id="currency-field" class="edit_jumlah_bayar form-control" data-type="currency">
                    </div>

                    <div class="form-group" id="form_kewajiban">
                      <label>Kewajiban</label>
                      <input type="text"  class="edit_kewajiban form-control" data-type="currency">
                    </div>


                   <div class="form-group" id="form_kewajiban">
                      <label>Potongan</label>
                      <input type="number"  class="edit_potongan form-control"> %
                    </div>

                    <div class="form-group">
                      <label>Semester</label>
                      <input type="text"  id="edit_semester" class="form-control" required>
                    </div>

                     <div class="form-group" id="form_tambah_item">
                      <label for="tambah_id_item">Pilih Item</label>
                      <select name="tambah_id_item" id="tambah_id_item" class="form-control">
                        <option value="">-- Pilih Item --</option>
                        @foreach ($item_bayar as $item)
                          <option value="{{ $item->id }}">{{ $item->nama_item }}</option>
                        @endforeach
                      </select>
                    </div>


                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </form>

              </div>
            </div>
          </div>

          <h4 class="card-title mb-3">Detail Transaksi</h4>
          <table id="detail_transaksi" class="stripe row-border order-column" style="width:100%">
            <thead>
              <tr>
                <th>Item</th>
                <th>Jml Bayar</th>
                <th>Kewajiban</th>
                <th>Admin</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>