<script src="{{ asset('public/vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>

<script>
  function statusTunggakan(nim) {
    var url = '{{ url("tunggakanMbc") }}/' + nim;
    window.open(url, '_blank');
  }

  function all_transaksi(nim) {
    var url = '{{ url("all_transaksi") }}/' + nim;
    window.open(url, '_blank');
  }

  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    let table_siswa = $('#table_siswa').DataTable({

      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('create_datatable_mbc') }}",
        data: function(d) {
          d.tahun_ajaran = $('#tahun_ajaran').val();
          d.status_mahasiswa = $('#status_mahasiswa').val();
        }
      },
      columns: [

        {
          data: null,
          orderable: false, // jangan bisa di-sort
          searchable: false, // jangan ikut pencarian
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          data: 'str_nim',
          name: 'a.str_nim'
        },
        {
          data: 'str_nm_mhs',
          name: 'a.str_nm_mhs'
        },
        {
          data: 'bol_lulus',
          name: 'a.bol_lulus',
          render: function(data, type, row) {
            switch (data) {
              case '0':
                return 'Aktif';
              case '1':
                return 'Lulus';
              case '2':
                return 'DO';
              case '3':
                return 'Keluar';
              case '4':
                return 'Meninggal';
              case '5':
                return 'Mengundurkan Diri';
              default:
                return '-';
            }
          }
        },

        {
          data: 'is_luarnegeri',
          name: 'a.is_luarnegeri',
          render: function(data) {
            if (data == 1) return 'Reguler';
            if (data == 2) return 'Luar Negeri';
            return '-';
          }
        },

        // { data: 'str_nm_prodi', name: 'a.str_nm_prodi' },

        // {
        //   data: 'str_thn_ajaran', name: 'e.str_thn_ajaran'

        // },
        // { data: 'str_angkatan', name: 'a.str_angkatan' },

        // { 
        //   data: 'tunggakan',
        //   name: 'tunggakan',
        //   render: function (data, type, row) {
        //         return  parseFloat(data).toLocaleString('id-ID');
        //     }

        // },
        {
          data: 'action',
          name: 'action'
        }
      ],
      order: [
        [0, 'desc']
      ]
    });

    // Event Filter
    $('#tahun_ajaran, #status_mahasiswa').change(function() {
      table_siswa.draw(true);
    });
  });
</script>