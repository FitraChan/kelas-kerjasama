<script src="{{ asset('public/vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
    $("#btnTambah").on("click", function() {

      let idBayar = $("#edit_id_bayar").val();
      // Reset form
      $("#editForm")[0].reset();

      $("#edit_id_bayar").val(idBayar);
      $("#edit_id").val(""); // kosongkan id
      $("#editModalLabel").text("Tambah Detail Transaksi"); // ganti judul modal
      $("#form_tambah_item").show();
     // $("#form_kewajiban").hide();

      // Tampilkan modal
      $("#editModal").modal("show");
    });
  });
  
  function hapusGambar(id, nim) {
    Swal.fire({
      title: 'Yakin ingin hapus?',
      text: "Data ini tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('hapusGambar') }}",
          type: "POST",
          data: {
            id: id,
            _token: "{{ csrf_token() }}"
          },
          success: function(res) {
            Swal.fire('Terhapus!', 'Data sudah dihapus.', 'success');
            transaksi.ajax.reload();
            detail_transaksi.ajax.reload();
            $('#cardContainer').load('/reloadCardHapus/' + nim);

          }
        });
      }
    });

  }

  function hapus(id, nim) {
    Swal.fire({
      title: 'Yakin ingin hapus?',
      text: "Data ini tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('hapusTransaksi') }}",
          type: "POST",
          data: {
            id: id,
            _token: "{{ csrf_token() }}"
          },
          success: function(res) {
            Swal.fire('Terhapus!', 'Data sudah dihapus.', 'success');
            transaksi.ajax.reload();
            detail_transaksi.ajax.reload();
            $('#cardContainer').load('/reloadCardHapus/' + nim);

          }
        });
      }
    });

  }

  function edit(id, semester) {
    $('.nav-tabs .active').parent().next().find('a[data-toggle="tab"]').tab('show');

    $.ajax({
      url: "{{ url('getMahasiswa') }}/" + id,
      type: "GET",
      success: function(res) {

        console.log('detail', res);
        
        $('#nim').val(res.str_nim);
        $('#nama').val(res.str_nm_mhs);
        $('#semester_id').val(semester ?? ''); // ambil semester dari mbc_bayar jika ada
      }
    });

    //$('#detail_transaksi').DataTable().clear().draw();

    if ($.fn.DataTable.isDataTable('#detail_transaksi')) {
      let table = $('#detail_transaksi').DataTable();

      // Ubah URL jadi endpoint kosong
      table.ajax.url("{{ url('detailBayarKosong') }}").load();
    }

    transaksi = $('#transaksi').DataTable({
      destroy: true,
          pageLength: 4,  // Ã°Å¸â€˜Ë† tampilkan 5 per halaman
      searching: false,
      processing: true,
      serverSide: true,
      paging: true,
      scrollX: true,
      ajax: {
        url: "{{ url('hisBayar') }}/" + id,
        type: "GET",
        data: function(d) {
          d.semester = semester;
          d._token = "{{ csrf_token() }}";
        }
      },
      columns: [{
          data: 'id_tahun',
          className: 'text-center'
        },
        {
          data: 'id_bulan',
          className: 'text-center'
        },
        {
          data: 'tgl_bayar',
          className: 'text-center'
        },
        {
          data: 'tot_bayar',
          render: formatNumber,
          className: 'text-center'
        },
        {
          data: 'tot_kwajiban',
          render: formatNumber,
          className: 'text-center'
        },
        {
          data: 'keterangan',
          className: 'text-center'
        },

        {
          data: 'sts_trans',
          className: 'text-center',
          render: function(data, type, row) {
            if (data == 1) {
              return 'Reguler';
            } else if (data == 2) {
              return 'Luar Negeri';
            } else {
              return '-';
            }
          }
        },
        {
           data: 'bukti_bayar_url', // <-- Ganti nama kolom/data yang diakses
          className: 'text-center',
          render: function(data, type, row) {
            if (data) {
              // 'data' kini adalah URL aman
              return `<a href="${data}" target="_blank">Lihat Bukti</a>`;
            } else {
              return '-';
            }
          }
        },
        
         {
          data: 'action-gambar',
          className: 'text-center'
        },
        {
          data: 'action',
          className: 'text-center'
        }
      ],
      order: [
        [0, 'desc']
      ]
    });

    $('#transaksi tbody').off('click').on('click', 'tr', function() {
      let data = transaksi.row(this).data();

      $('#edit_id_bayar').val(data.id);
      if (data) detail(data.id);
    });

// $(document).off('click', '#transaksi tbody tr').on('click', '#transaksi tbody tr', function() {
//   const data = transaksi.row(this).data();
//   if (!data || !data.id_bayar) return;
  
//   console.log('Klik transaksi:', data.id_bayar);
//   $('#edit_id_bayar').val(data.id_bayar);
//   detail(data.id_bayar);
// });
  }

  function detail(id) {
    detail_transaksi = $('#detail_transaksi').DataTable({
      destroy: true,
      searching: false,
      processing: true,
      serverSide: true,
      paging: false,
      scrollX: true,
      ajax: {
        url: "{{ url('detailBayar') }}/" + id
      },
      columns: [{
          data: 'item_bayar.nama_item',
          className: 'text-center'
        },
        {
          data: 'jml_bayar',
          render: formatNumber,
          className: 'text-center'
        },
        {
          data: 'kwajiban_bayar',
          render: formatNumber,
          className: 'text-center'
        },
        {
          data: 'pengguna',
  className: 'text-center',
  render: function (data, type, row) {
    return data && data.name ? data.name : '-';
  }
        },
        {
          data: 'action',
          className: 'text-center'
        }
      ],
      order: [
        [0, 'desc']
      ]
    });
  }

  function formatNumber(data, type) {
    if (type === 'display' || type === 'filter') {
      return new Intl.NumberFormat('id-ID').format(data);
    }
    return data;
  }

  $('#editForm').on('submit', function(e) {
    e.preventDefault();

    var id = $('#edit_id').val();

    var id_bayar = $('#edit_id_bayar').val();
    if (id) {

       $.ajax({
    url: "{{ url('simpan_edit') }}",
    type: "POST",
    data: {
      id: $('#edit_id').val(),
      id_item: $('#edit_id_item').val(),
      id_bayar: $('#edit_id_bayar').val(),
      jml_bayar: $('.edit_jumlah_bayar').val(),
      semester: $('#edit_semester').val(),
      kewajiban: $('.edit_kewajiban').val(),
      potongan: $('.edit_potongan').val(),
      _token: "{{ csrf_token() }}"
    },

    success: function (response) {
      console.log("SUCCESS:", response);

      $('#editModal').modal('hide');
      $('#detail_transaksi').DataTable().ajax.reload();
      $('#transaksi').DataTable().ajax.reload();

      $('#cardContainer').load('/reloadCard/' + $('#edit_id_bayar').val());

      alert(response.message ?? 'Data berhasil diperbarui');
    },

    error: function (xhr, status, error) {
      console.error("AJAX ERROR:", xhr);

      let message = 'Terjadi kesalahan tidak diketahui';

      // ðŸ”´ VALIDATION ERROR (422)
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        message = '';
        $.each(xhr.responseJSON.errors, function (key, value) {
          message += value[0] + '\n';
        });
      }
      // ðŸ”´ SERVER ERROR (500)
      else if (xhr.responseJSON) {
        message =
          (xhr.responseJSON.message ?? '') +
          '\n\nError: ' + (xhr.responseJSON.error ?? '-') +
          '\nFile: ' + (xhr.responseJSON.file ?? '-') +
          '\nLine: ' + (xhr.responseJSON.line ?? '-');
      }
      // ðŸ”´ ERROR LAIN
      else {
        message = xhr.responseText;
      }

      alert(message);
    }
  });
    } else {
      $.ajax({
        url: "{{ url('tambahDetail') }}", // endpoint tambah
        type: 'post',
        data: {
          id_item: $('#tambah_id_item').val(),
          id_bayar: $('#edit_id_bayar').val(),
                kewajiban: $('.edit_kewajiban').val(),

          jml_bayar: $('.edit_jumlah_bayar').val(),
          semester: $('#edit_semester').val(),
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {

          $('#editModal').modal('hide');
          $('#detail_transaksi').DataTable().ajax.reload();
          $('#transaksi').DataTable().ajax.reload();

          alert('Data berhasil ditambahkan!');
        },
        error: function(xhr) {
          alert('Terjadi kesalahan: ' + xhr.responseText);
        }
      });
    }
  });


  function edit2(id) {

    $.ajax({
      url: "{{ url('getDetailBayar') }}/" + id,
      type: "GET",
      success: function(res) {

        console.log(res);




        $('#edit_id').val(res.id);
        $('#edit_id_item').val(res.id_item);
        var sem = res.bayar.id_semester;
        var semF = sem.replace(/^0+/, "");
        $('#edit_semester').val(semF);


        $('#edit_id_bayar').val(res.id_bayar);
        $('#edit_item').val(res.item_bayar.nama_item);
        $('.edit_jumlah_bayar').val(res.jml_bayar);
        $('.edit_kewajiban').val(res.kwajiban_bayar);
            if (res.potongan > 0) {
        let persen = Math.round((res.potongan / res.kwajiban_bayar) * 100);
        $('.edit_potongan').val(persen);
        } else{

          $('.edit_potongan').val(0);
        }

        $("#form_tambah_item").hide();
        $("#form_kewajiban").show();
        $('#editModal').modal('show');


      }
    });

  }


  function hapusDetail(id, id_bayar) {
    Swal.fire({
      title: 'Yakin ingin hapus?',
      text: "Data ini tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('hapusDetail') }}",
          type: "POST",
          data: {
            id: id,
            id_bayar: id_bayar,
            _token: "{{ csrf_token() }}"
          },
          success: function(res) {
            Swal.fire('Terhapus!', 'Data sudah dihapus.', 'success');
            transaksi.ajax.reload();
            detail_transaksi.ajax.reload();

          }
        });
      }
    });

  }

  function editTransaksi(id) {
    // $('#editTransaksiModal').modal('show');


    $.ajax({
      url: "{{ url('showPembayaran') }}/" + id,
      type: "GET",
      success: function(res) {

        console.log(res);

        $('#edit_id_bayar_transaksi').val(res.id);

        $('#kategori_id').val(res.sts_trans);
        $('#asal').val(res.keterangan);
                $('#ket_simple').val(res.ket_simple);

        //$('#tgl_bayar').val(res.tgl_bayar);
        if (res.tgl_bayar) {
          $('#tgl_bayar').val(res.tgl_bayar.split(' ')[0]);
        } else {
          $('#tgl_bayar').val(''); // kosongkan jika null
        }
        $('#id_semester').val(res.id_semester);
        $('#bank').val(res.via);
        $('#bank_penerima').val(res.bank_penerima);





        $('#editTransaksiModal').modal('show');


      }
    });

  }


  $('#editTransaksiModalForm').on('submit', function(e) {
    e.preventDefault();

    var id = $('#edit_id_bayar_transaksi').val();
    if (!id) return;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('keterangan', $('#keterangan').val());
    formData.append('asal', $('#asal').val());
    formData.append('kategori_id', $('#kategori_id').val());
    formData.append('tgl_bayar', $('#tgl_bayar').val());
    formData.append('id_semester', $('#id_semester').val());
    formData.append('ket_simple', $('#ket_simple').val());
    
    formData.append('via', $('#bank').val());
    formData.append('bank_penerima', $('#bank_penerima').val());

    formData.append('_token', '{{ csrf_token() }}');

    let gambar = $('#gambar')[0].files[0];
    if (gambar) {
      formData.append('gambar', gambar);
    }

    $.ajax({
      url: "{{ url('simpanKategori') }}",
      type: "POST",
      data: formData,
      processData: false, // WAJIB
      contentType: false, // WAJIB
      success: function(response) {
        $('#editTransaksiModal').modal('hide');
        $('#detail_transaksi').DataTable().ajax.reload();
        $('#transaksi').DataTable().ajax.reload();
        alert('Data berhasil diperbarui!');
      },
      error: function(xhr) {
        alert('Terjadi kesalahan: ' + xhr.responseText);
      }
    });
  });


  function bayar(val) {

    $('#md-status').modal('show');
    $('#va').text("0");
    $('#unik').text("0");
    $('#status_trx').text("0");
    $('#nominal').text("0");
    $('#bank').text("");
    $('#waktu').text("0");
    $('#tipe').text("");
    $('#nim_bayar').val(val);
    //$('#semester_id_pop').val(semester_id);

  }
  
   function bayarGelondongan(val) {

    $('#md-status-gelondongan').modal('show');
    $('#va').text("0");
    $('#unik').text("0");
    $('#status_trx').text("0");
    $('#nominal').text("0");
    $('#bank').text("");
    $('#waktu').text("0");
    $('#tipe').text("");
    $('#nim').val(val);
    //$('#semester_id_pop').val(semester_id);

  }

   function init(val) {
    $('#init').modal('show');

    $('#nim2').val(val);
    
    console.log('masuk');

    $.ajax({
      url: '{{ url("getTagihan") }}' + '/' + val,
      method: "GET",
      dataType: 'json',
      success: function(data) {
        console.log('tagihan',data[0].nilai);
        $('#biaya').val(data[0].nilai);
         $('#id_item').val(data[0].id);

      },
      error: function(xhr, status, error) {
           $('#id_item').val(56);
        console.error('AJAX ERROR:', xhr.responseText);
      }
    });
  } // limit


  function tagihan_khusus(val) {
    $('#tagihan_khusus').modal('show');

    $('#nim3').val(val);

    $.ajax({
      url: '{{ url("semester") }}' + '/' + val,
      method: "GET",


      dataType: 'json',
      success: function(data) {
        console.log(data);
        $('#smt_now2').val(data[0].semester);
        $('.dpp').val(data[0].dpp);
        $('.jaket').val(data[0].jaket);
        $('.pendaftaran').val(data[0].pendaftaran);
        
        $('.id_dpp').val(data[0].id_dpp);
        $('.id_jaket').val(data[0].id_jaket);
        $('.id_pendaftaran').val(data[0].id_pendaftaran);


      }
    });
  }

  function hapusSemester(nim,semester_id) {
    Swal.fire({
      title: 'Yakin ingin hapus semester '+semester_id+'?',
      text: "Data ini tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('hapusSemester') }}",
          type: "POST",
          data: {
            nim: nim,
            semester_id: semester_id,
            _token: "{{ csrf_token() }}"
          },
          success: function(res) {
            Swal.fire('Terhapus!', 'Data sudah dihapus.', 'success');

            var url = "<?= url('reloadCardHapus')  ?>"+'/'+nim  ;
          
            $('#cardContainer').load(url);

          }
        });
      }
    });

  }
  
  
  function addFileInput() {
  const wrapper = document.getElementById('fileWrapper');

  const div = document.createElement('div');
  div.classList.add('mb-3', 'file-item');

          div.innerHTML = `
           <div class="mb-3 file-item">
              <label class="form-label">Nama Dokumen</label>
              <input type="text" name="nama_dokumen[]" class="form-control">
            </div>
          
            <label class="form-label">Pilih File</label>
            <div class="d-flex gap-2">
              <input type="file" name="dokumen[]" class="form-control">
              <button type="button" class="btn btn-danger" onclick="removeFileInput(this)">
                ✕
              </button>
            </div>
          `;
        
          wrapper.appendChild(div);
        }
        
        function removeFileInput(button) {
          button.closest('.file-item').remove();
        }
</script>