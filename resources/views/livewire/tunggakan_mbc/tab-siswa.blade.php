<div class="tab-pane fade show active" id="siswa" role="tabpanel" aria-labelledby="siswa-tab">
  <div id="cardContainer">

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


    <div id='init' class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail Pembayaran</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <form action="{{ url('init')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 table-responsive">

                  <section id="loading">
                    <div id="loading-content"></div>
                  </section>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">NIM</label>

                    <input type="text" name="nim2" id="nim2" value="" class="form-control">

                  </div>



                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Tahun Pembayaran</label>
                    <input type="number" name="tahun" value="" class="form-control">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Biaya</label>
                    <input type="number" name="biaya" value="" id="biaya" class="form-control" required>
                    <input type="hidden" name="id_item" value="" id="id_item" class="form-control" required>
                  </div>

                  <!-- <div class="form-group col-md-12">
                        <label for="inputEmail4">Semester Saat Ini</label>
                        <input type="text" name="semester_now" value="" id="smt_now" class="form-control">
                      </div> -->


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Dari Semester</label>
                    <input type="number" name="dari" value="" id="dari" class="form-control">
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Sampai Semester</label>
                    <input type="number" name="sampai" value="" id="sampai" class="form-control">
                  </div>




                </div>
              </div>


            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>

            </div>

          </form>
        </div>
      </div>
    </div>

    <div id='tagihan_khusus' class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail Pembayaran</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <form action="{{ url('tagihan_khusus')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 table-responsive">

                  <section id="loading">
                    <div id="loading-content"></div>
                  </section>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">NIM</label>

                    <input type="text" name="nim3" id="nim3" value="" class="form-control">

                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Pendaftaran</label>
                    <input type="text" name="pendaftaran" value="" id="currency-field" class="form-control pendaftaran" value="" data-type="currency">
                    <input type="hidden" name="id_pendaftaran" value="" class="id_pendaftaran">



                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">DPP</label>
                    <input type="text" name="dpp" value="" id="currency-field" class="form-control dpp" value="" data-type="currency">
                    <input type="hidden" name="id_dpp" value="" class="id_dpp">
                  </div>


                   

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Jaket / Almamater</label>
                    <input type="text" name="jaket" value="" id="currency-field" class="form-control jaket" value="" data-type="currency">

                    <input type="hidden" name="id_jaket" value="" class="id_jaket">


                  </div>



                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Tahun Pembayaran</label>
                    <input type="number" name="tahun" value="" class="form-control">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Semester Saat Inii</label>
                    <input type="text" name="smt_now_tk" value="" id="smt_now2" class="form-control" required>
                  </div>




                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>

            </div>

          </form>
        </div>
      </div>
    </div>
    <!-- otomatis -->

    <div id='md-status-gelondongan' class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail Pembayaran</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>



          <form action="{{ url('simpanProsesBayar')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 table-responsive">

                  <section id="loading">
                    <div id="loading-content"></div>
                  </section>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">NIM</label>
                    <input type="text" name="nim" value="" id='nim' class="form-control">
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Tgl Trans</label>
                    <input type="datetime-local" name="tgl_trans" value="" class="form-control">
                  </div>



                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Bayar</label>


                    <input type="text" name="bayar" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Upload Gambar</label>
                    <input type="file" name="image" value="" class="form-control" required>
                  </div>


                  <!--<div class="form-group col-md-12">-->
                  <!--  <label for="inputEmail4">Pembayaran VIA</label>-->
                  <!--  <select name="via" class="form-control" required>-->
                  <!--    <option value="">Metode Pembayaran</option>-->
                  <!--    <option value="5">BNI</option>-->
                  <!--    <option value="1">MASPION</option>-->
                  <!--    <option value="9">MASPION VIRTUAL ACCOUNT</option>-->
                  <!--    <option value="10">I-PAYMU</option>-->
                  <!--  </select>-->
                  <!--</div>-->

                  <div class="form-group col-md-12">
                    <label>Bank Pengirim</label>
                    <select class="form-control" name="via">
                      <option value="">-- Pilih Bank --</option>

                      @foreach ($bank as $b)
                      <option value="{{ $b->id }}">
                        {{ $b->nama_bank }}
                      </option>
                      @endforeach

                    </select>
                  </div>


                  <div class="form-group col-md-12">
                    <label>Bank Penerima</label>
                    <select class="form-control" name="bank_penerima">
                      <option value="">-- Pilih Bank --</option>

                      @foreach ($bank_penerima as $b)
                      <option value="{{ $b->id }}">
                        {{ $b->nama_bank }}
                      </option>
                      @endforeach

                    </select>
                  </div>




                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Keterangan</label>
                    <input type="text" name="comments" value="" class="form-control">
                  </div>





                </div>
              </div>


            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>

            </div>

          </form>
        </div>
      </div>
    </div>



    <!-- <div class="container">
        <h3>Import CSV ke tb_itembayar</h3>
        <form action="{{ url('importCsv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">Pilih File CSV</label>
                <input type="file" name="csv_file" class="form-control" required>
            </div>
            <button class="btn btn-primary" type="submit">Import</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
    </div> -->


    <!-- manual input admin -->
    <div id='md-status' class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail Pembayaran</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>



          <form action="{{ url('simpan')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 table-responsive">

                  <section id="loading">
                    <div id="loading-content"></div>
                  </section>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">NIM</label>
                    <input type="text" name="nim" value="" id='nim_bayar' class="form-control">
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Tgl Trans</label>
                    <input type="datetime-local" name="tgl_trans" value="" class="form-control">
                  </div>


                  <div class="form-group col-md-12">
                      <label for="inputEmail4">Final Eksekutif</label>


                      <input type="text" name="final_eksekutif" id="currency-field" class="form-control" value="" data-type="currency">
                    </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Sks Di Akui</label>


                    <input type="text" name="sks_diakui" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Bimbingan TA</label>


                    <input type="text" name="pembayaran_ta" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>




                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Pendaftaran</label>


                    <input type="text" name="pendaftaran" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Jaket</label>


                    <input type="text" name="jaket" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">DPP</label>


                    <input type="text" name="dpp" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Semesteran</label>
                    <input type="text" name="semesteran" id="currency-field" class="form-control" value="" data-type="currency">
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Upload Gambar</label>
                    <input type="file" name="image" value="" class="form-control" required>
                  </div>
              
                  <div class="form-group col-md-12">
                    <label>Bank Pengirim</label>
                    <select class="form-control" name="via">
                      <option value="">-- Pilih Bank --</option>

                      @foreach ($bank as $b)
                      <option value="{{ $b->id }}">
                        {{ $b->nama_bank }}
                      </option>
                      @endforeach

                    </select>
                  </div>


                  <div class="form-group col-md-12">
                    <label>Bank Penerima</label>
                    <select class="form-control" name="bank_penerima">
                      <option value="">-- Pilih Bank --</option>

                      @foreach ($bank as $b)
                      <option value="{{ $b->id }}">
                        {{ $b->nama_bank }}
                      </option>
                      @endforeach

                    </select>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Pembayaran Semester</label>
                    <select name="semester" class="form-control">

                      <option value=0>0</option>
                      <option value="01">Satu</option>
                      <option value="02">Dua</option>
                      <option value="03">Tiga</option>
                      <option value="04">Empat</option>
                      <option value="05">Lima</option>
                      <option value="06">Enam</option>
                      <option value="07">Tujuh</option>
                      <option value="08">Delapan</option>
                      <option value="09">Sembilan</option>
                      <option value="10">Sepuluh</option>
                      <option value="11">Sebelas</option>
                      <option value="12">Dua Belas</option>
                      <option value="13">Tiga Belas</option>


                    </select>
                  </div>


                  <div class="form-group col-md-12">
                    <label for="inputEmail4">Keterangan</label>
                    <input type="text" name="comments" value="" class="form-control">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="inputEmail4">No Kwitansi</label>


                    <input type="text" name="no_kwitansi" id="no_kwitansi" class="form-control" value="">
                  </div>





                </div>
              </div>


            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>

            </div>

          </form>
        </div>
      </div>
    </div>

    <!-- end of 'md-status' -->

    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div id="print-area">
          <div class="row align-items-center">
            <div class="col-md-12">

              <h4 class="card-title mt-3">
                Informasi Pembayaran Mahasiswa : {{ $nama   }} ( {{ $nim }} )
              </h4>
            </div>


            <div class="col-md-12 text-end">
              <!-- Tombol PDF dan Form Pilih Tahun -->
              <div class="d-flex justify-content-end align-items-center flex-wrap gap-2">

                <button
                  class="btn btn-info btn-icon mb-2"
                  id="btn-detail"
                  data-nim="{{ $nim }}"
                  onclick="bayar('{{ $nim }}')">
                  Bayar
                </button>

                <button
                  class="btn btn-info btn-icon mb-2"
                  id="btn-detail"
                  data-nim="{{ $nim }}"
                  onclick="bayarGelondongan('{{ $nim }}')">
                  Bayar Gelondongan
                </button>



                <button
                  class="btn btn-success btn-icon mb-2"
                  id="btn-detail"
                  data-nim="{{ $nim }}"
                  onclick="init('{{ $nim }}')">
                  Tagihan Per Semester
                </button>

                <button class="btn btn-success btn-icon mb-2"
                  id="btn-detail"
                  data="{{ $nim }}"
                  onclick="tagihan_khusus('{{ $nim }}')">
                  Tagihan Khusus</button>




                <a href="<?= url('create_pdf/' . $nim) ?>" class="btn btn-info btn-icon mb-2" target="_blank">
                  <i class="nav-icon i-Check"></i> PDF USER
                </a>


                <a href="<?= url('create_pdf_kuliah_kerja/' . $nim) ?>" class="btn btn-info btn-icon mb-2" target="_blank">
                  <i class="nav-icon i-Check"></i> PDF Kuliah Kerja
                </a>

                <a href="<?= url('kwitansi/' . $nim) ?>" class="btn btn-info btn-icon mb-2" target="_blank">
                  <i class="nav-icon i-Check"></i> Kwitansi
                </a>




              </div>
            </div>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-5 col-lg-4">

          </div>
        </div>



        <div class="row">
          @foreach ($baris as $row)
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-icon-bg o-hidden mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 col-sm-3">
                    <i class="i-Money-2 text-blue"></i>
                  </div>
                  <div class="col-md-9 col-sm-6">
                    <p class="mt-2" style="font-size: 10px;">Tagihan Pembayaran</p>
                    <h3 class="title mb-0" style="margin-top:-15px; width: 350px;">
                      <b>Semester {{ $row->id_semester }}</b>
                    </h3>
                  </div>
                  <div class="col-lg-12 mt-3">
                    <table class="bordered text-left">
                      <tr>
                        <td class="pr-3">Tarif</td>
                        <td>Rp. {{ number_format($row->SUMK2) }}</td>
                      </tr>
                      <tr>
                        <td>Kewajiban</td>
                        <td>Rp. {{ number_format($row->SUMK2) }}</td>
                      </tr>

                      <tr>
                        <td>Sisa</td>
                        <td>Rp. {{ number_format($row->SUMK2 - $row->SUMB - $row->SUMP) }}</td>
                      </tr>
                    </table>
                  </div>
                  <div class="col-lg-12 mt-3">
                    <a href="#" class="btn btn-success btn-sm btn-edit"
                      onclick="edit('{{ $nim }}','{{ $row->id_semester }}')">
                      <i class='nav-icon i-Eye.-Scan'></i> Detail
                    </a>






                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>