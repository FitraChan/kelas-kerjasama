@extends('layouts.app2')
@section('main-content')


<div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Rekening Pembayaran<br>ITB STIKOM Bali</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form method="post" id="" action="" enctype="multipart/form-data" class="row needs-validation">
                    <input type="hidden" name="" value="">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="display table table-striped table-bordered" id="scroll_vertical_table" style="width:100%">
                                    <thead class="bg-gray-300">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Bank</th>
                                            <th scope="col">No Rekening</th>
                                            <th scope="col">Atas Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>1</th>
                                            <td>MASPION</td>
                                            <td>7002011110</td>
                                            <td>ITB STIKOM BALI</td>
                                        </tr>
                                        <tr>
                                            <th>2</th>
                                            <td>MUAMALAT</td>
                                            <td>7540000400</td>
                                            <td>ITB STIKOM BALI</td>
                                        </tr>
                                        <tr>
                                            <th>3</th>
                                            <td>BPD</td>
                                            <td>029-02-0203763-2</td>
                                            <td>ITB STIKOM BALI</td>
                                        </tr>
                                        <tr>
                                            <th>4</th>
                                            <td>BRI</td>
                                            <td>0017-01-003-066-30-3</td>
                                            <td>ITB STIKOM BALI</td>
                                        </tr>
                                        <tr>
                                            <th>5</th>
                                            <td>BNI</td>
                                            <td>0069008818</td>
                                            <td>PT WIDYA DHARMA SHANTI</td>
                                        </tr>
                                        <tr>
                                            <th>6</th>
                                            <td>BCA</td>
                                            <td>6110205111</td>
                                            <td>PT WIDYA DHARMA SHANTI</td>
                                        </tr>
                                        <tr>
                                            <th>7</th>
                                            <td>BANK SYARIAH MANDIRI</td>
                                            <td>7012257311</td>
                                            <td>PT WIDYA DHARMA SHANTI</td>
                                        </tr>
                                        <tr>
                                            <th>8</th>
                                            <td>MUAMALAT DOLLAR</td>
                                            <td>7510010769</td>
                                            <td>PT WIDYA DHARMA SHANTI</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Proses Bayar</button>-->
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-detail-format">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">FORMAT VIRTUAL ACCOUNTT<br>BANK MASPION dan BANK BRI</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h6><strong>ACCOUNT BANK MASPION</strong> </h6>
                <p style="font-size: 12px;">Mahasiswa yang dibuatkan virtual account Bank Maspion adalah mahasiswa angkatan 2017 - 2019
                    reguler semua prodi bukan investasi, bukan Jimbaran.<br>

                    Format nomer virtual account dari masing – masing mahasiswa adalah seperti berikut:
                </p>
                <p class="alert alert-success text-center"><strong>01201 + semester + NIM</strong></p>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="display table table-striped table-bordered" id="scroll_vertical_table" style="width:100%">
                            <tbody>
                                <tr>
                                    <td>01201 </td>
                                    <td>Kode STIKOM Bali pada bank Maspion, setiap mahasiswa sama.</td>
                                </tr>
                                <tr>
                                    <td>Semester</td>
                                    <td>Dua digit angka semester yang akan dibayar oleh mahasiswa, misal mahasiswa ingin
                                        membayar disemester 2 maka input 02</td>
                                </tr>
                                <tr>
                                    <td>NIM</td>
                                    <td>9 (Sembilan) digit Nim dari mahasiswa</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <h6><strong>VIRTUAL ACCOUNT BANK BRI</strong> </h6>
                <p style="font-size: 12px;">Mahasiswa yang dibuatkan virtual account Bank BRI adalah mahasiswa angkatan 2020 reguler semua
                    prodi bukan investasi, bukan Jimbaran.<br>

                    Format nomer virtual account dari masing – masing mahasiswa adalah seperti berikut:
                </p>
                <p class="alert alert-success text-center"><strong>10126 + semester + NIM</strong></p>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="display table table-striped table-bordered" id="scroll_vertical_table" style="width:100%">
                            <tbody>
                                <tr>
                                    <td>10126 </td>
                                    <td>Kode STIKOM Bali pada bank BRI, setiap mahasiswa sama.</td>
                                </tr>
                                <tr>
                                    <td>Semester</td>
                                    <td>Dua digit angka semester yang akan dibayar oleh mahasiswa, misal mahasiswa ingin
                                        membayar disemester 2 maka input 02</td>
                                </tr>
                                <tr>
                                    <td>NIM</td>
                                    <td>9 (Sembilan) digit Nim dari mahasiswa</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <h6><strong>SEKENARIO PEMBAYARAN <br>MENGGUNAKAN VIRTUAL ACCOUNT</strong> </h6>
                <p style="font-size: 12px;">
                    <ol>
                        <li>Pembayaran menggunakan Virtual Account bisa melalui Tailor atau transfer virtual Account
                            melalui ATM
                        </li>
                        <li>Bukti pembayaran Virtual Account melalui Bank / ATM adalah bukti pembayaran anda dan tidak
                            usah di kirim / diserahkan di bagian keuangan ITB STIKOM Bali
                        </li>
                        <li>Bagian Keuangan akan melakukan sinkronisasi dengan sistem internal ITB STIKOM Bali
                        </li>
                        <li>Mahasiswa bisa melihat informasi pembayaran di SION atau menanyakan langsung ke bagian
                            keuangan pada hari berikutnya
                        </li>
                        <li>Selesai</li>
                    </ol>
                </p>
                <br>
                <h6><strong>SEKENARIO PEMBAYARAN <br>NON VIRTUAL ACCOUNT</strong> </h6>
                <p style="font-size: 12px;">
                    <ol>
                        <li>Mahasiswa membayar melalui transfer ke rekening bank ITB STIKOM Bali (list rekening Bank ITB
                            STIKOM BALI Terlampir)
                        </li>
                        <li>Bukti Tranfer / bayar non Virtual Account di cetak dan di bawah ke bagian keuangan / kirim ke
                            WhatsApp keuangan ( No WA: 082146308782 ) dengan menulistkan NIM dan Nama mahasiswa
                            pada bukti pembayaran menggunakan tinta hitam/biru. (jika identitas mahasiswa tidak tertulis
                            di bukti bayar bank maka pembayaran mahasiswa tersebut tidak akan diproses)
                        </li>
                        <li>Mahasiswa dapat menambahkan keterangan jenis pembayaran pada pesan WhatsApp untuk
                            mempermudah dan mempercepat proses penginputan pembayaran mahasiswa bersangkutan
                        </li>
                        <li>Selesai</li>
                    </ol>
                </p>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <!-- ICON BG -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-icon-bg bg-success o-hidden mb-4">
                <div class="card-body">
                    <div class="row">
                   <div class="col-md-3">
                            <i class="i-Money-2 text-white"></i>
                        </div>
                        <div class="col-md-9">
                            <a href="" class="text-white" style="width: 250px;">
                            <h4 class="text-white mt-2 mb-0" style="width: 250px;">Total Kewajiban</h4>
                            </a>
                        </div> 
                        
                         <!-- <img src="{{ asset('vendor/images/rara.jpeg') }}" style="width: 250px;"  title="Logo"> -->
                        
                    </div>
                </div>
            </div>
            
              <a href="{{ url('daftar') }}" class="btn btn-info btn-sm btn-edit mb-2"> Registrasi </a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-icon-bg bg-warning o-hidden mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="i-Financial text-white"></i>
                        </div>
                        <div class="col-md-9">
                            <a href="" class="text-white" style="width: 250px;">
                            <h4 class="text-white mt-2 mb-0" style="width: 250px;">Total Pembayaran</h4>
                            </a>
                        </div> 
                        
                        <!--  <img src="{{ asset('vendor/images/rara.jpeg') }}" style="width: 250px;"  title="Logo"> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-icon-bg bg-primary o-hidden mb-4">
                <div class="card-body">
                    <div class="row">
                    
                    
                        <div class="col-md-3">
                            <i class="i-Financial text-white"></i>
                        </div>
                        <div class="col-md-9">
                            <a href="{{ route('keuangan') }}" class="text-white" style="width: 250px;">
                            <h4 class="text-white mt-2 mb-0" style="width: 250px;">Total Tunggakan</h4>
                            </a>
                        </div>
                        
                      
			  <!-- <img src="{{ asset('vendor/images/rara.jpeg') }}" style="width: 250px;"  title="Logo"> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-4 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="ul-widget__head  v-margin pb-20">
                        <div class="ul-widget__head-label">
                            <h3 class="ul-widget__head-title ">
                                Pengumuman
                            </h3>
                        </div>
                    </div>
                    <div class="ul-widget__body">
                    <div class="ul-widget1 ">
                        <div class="ul-widget4__item ul-widget4__users">
                            <div class="ul-widget4__pic-icon">
                                <i class="i-Folder-With-Document text-info "></i>
                            </div>
                            <div class="ul-widget2__info ul-widget4__users-info">
                                <h5 class="ul-widget4__title">REKENING PEMBAYARAN MAHASISWA ITB STIKOM BALI</h5>
                                <span class="ul-widget2__username">
                                    <p>Nomer Rekening Pembayaran dan Nomer WA = 082146308782</p>
                                    <a href="#modal-detail" data-toggle="modal" class="btn btn-info btn-sm btn-edit mb-2">
                                        <i class="nav-icon i-Eye-Scan"></i> Lihat Detail</a>
                                </span>
                            </div>
                        </div>
                        <div class="ul-widget4__item ul-widget4__users">
                            <div class="ul-widget4__pic-icon">
                                <i class="i-Folder-With-Document text-info "></i>
                            </div>
                            <div class="ul-widget2__info ul-widget4__users-info">
                                <h5 class="ul-widget4__title">FORMAT VIRTUAL ACCOUNT</h5>
                                <span class="ul-widget2__username">
                                    <p>FORMAT VIRTUAL ACCOUNT BANK MASPION dan BANK BRI VIRTUAL ACCOUNT BANK MASPION Mahasiswa yang dibuatkan virtual account Bank Maspion adalah mahasiswa angkatan 2017 - 2019 reguler semua prodi bukan investasi, bukan Jimbaran Format</p>
                                    <a href="#modal-detail-format" data-toggle="modal" class="btn btn-info btn-sm btn-edit mb-2">
                                        <i class="nav-icon i-Eye-Scan"></i> Lihat Detail</a>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                </div>
            </div>
        </div>

    </div>

<!-- end of main-content -->


@endsection
