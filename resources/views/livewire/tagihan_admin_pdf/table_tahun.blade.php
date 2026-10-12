<table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr class="text-center">
                <th style="width:50%">Status Mahasiswa Tahun Ajaran <?= $tahun_ajaran; ?></th>
                <th style="width:50%">Jumlah Mahasiswa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Aktif</td>
                <td class="text-center">{{ $total_aktif }}</td>
            </tr>
             <tr>
                <td>Cuti</td>
                <td class="text-center">{{ $total_cuti }}</td>
            </tr>
            <tr>
                <td>Tidak Aktif</td>
                <td class="text-center">{{ $total_tidak_aktif }}</td>
            </tr>
            
              <tr>
                <td>Do</td>
                <td class="text-center">{{ $do }}</td>
            </tr>


           <tr>          
                <td>Alumni</td>
                <td class="text-center">{{ $total_lulus }}</td>
            </tr>


             <tr>          
                <td>Mengundurkan Diri</td>
                <td class="text-center">{{ $md }}</td>
            </tr>
            
        </tbody>
    </table>


    <h3>Daftar Mahasiswa Drop Out (DO)</h3>

<table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th width="10%">No</th>
            <th width="30%">NIM</th>
            <th>Nama Mahasiswa</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($daftar_do as $mhs)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mhs->str_nim }}</td>
                <td>{{ $mhs->str_nm_mhs }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" align="center">Tidak ada mahasiswa DO.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<h3>Daftar Mahasiswa MD</h3>

<table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th width="10%">No</th>
            <th width="30%">NIM</th>
            <th>Nama Mahasiswa</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($daftar_md as $mhs)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mhs->str_nim }}</td>
                <td>{{ $mhs->str_nm_mhs }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" align="center">Tidak ada mahasiswa MD.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
