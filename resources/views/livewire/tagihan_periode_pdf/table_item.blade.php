@php
// Tetap sama seperti kode lama
$grouped = collect($mhs)->groupBy('nim');
$no = 1;
$iy = 0; $totB = 0; $totK2 = 0; $totP = 0;

// === SEMESTERAN ===
$tanggalSemester = collect($mhs)
->filter(function($i) use ($tahun){
if (!isset($i->nama_item)) return false;
$isSemesteran = stripos($i->nama_item, 'Semesteran') !== false;
if (empty($tahun)) {
return $isSemesteran;
}
return $isSemesteran ;
})
->groupBy(function($i){ return date('Y-m-d', strtotime($i->tgl_bayar)); })
->sortKeys()->keys()->values();

// === DPP ===
$tanggalDPP = collect($mhs)
->filter(function($i) use ($tahun){
if (!isset($i->nama_item)) return false;
$isDPP = stripos($i->nama_item, 'DPP') !== false;
if (empty($tahun)) {
return $isDPP;
}
return $isDPP;
})
->groupBy(function($i){ return date('Y-m-d', strtotime($i->tgl_bayar)); })
->sortKeys()->keys()->values();

// === ALMAMATER ===
$tanggalAlmamater = collect($mhs)
->filter(function($i) use ($tahun){
if (!isset($i->nama_item)) return false;
$isAlmamater = in_array($i->nama_item, [
'Almamater, Topi Dan Buku',
'Kaos Gmti Dan Tas',
'Biaya Orientasi Jaket & Almamater',
]);
if (empty($tahun)) {
return $isAlmamater;
}
return $isAlmamater;
})
->groupBy(function($i){ return date('Y-m-d', strtotime($i->tgl_bayar)); })
->sortKeys()->keys()->values();


// === Final Eksekutif ===
$tanggalFinalEksekutif = collect($mhs)
->filter(function($i) use ($tahun){
if (!isset($i->nama_item)) return false;
$isFinalEksekutif = in_array($i->nama_item, ['Final Eksekutif','Biaya Admin','Bimbingan TA']);
if (empty($tahun)) {
return $isFinalEksekutif;
}
return $isFinalEksekutif;
})
->groupBy(function($i){ return date('Y-m-d', strtotime($i->tgl_bayar)); })
->sortKeys()->keys()->values();

// === PENDAFTARAN ===
$tanggalPendaftaran = collect($mhs)
->filter(function($i) use ($tahun){
if (!isset($i->nama_item)) return false;
$isPendaftaran = stripos($i->nama_item, 'Pendaftaran') !== false;
if (empty($tahun)) {
return $isPendaftaran;
}
return $isPendaftaran;
})
->groupBy(function($i){ return date('Y-m-d', strtotime($i->tgl_bayar)); })
->sortKeys()->keys()->values();








// === TOTAL SEMESTER ===
$grouped2 = collect($tot)->groupBy('nim');

$sumSemester = collect($tot)
->when(!empty($tahun), function($c) use ($tahun){
return $c->filter(function($i) use ($tahun){
return date('Y', strtotime($i->tgl_bayar)) == $tahun;
});
})
->groupBy(function($i){ return date('Y', strtotime($i->tgl_bayar)); })
->sortKeys()->keys()->values();

// === GRAND TOTAL ===



    $iy = 0;
    $totB = 0.0;
    $totK2 = 0.0;
    $totP = 0.0;
    $sisaItem = 0.0;
@endphp

<table cellspacing="0" cellpadding="5" width="100%" border="1" style="border-collapse: collapse; text-align:center; font-size:12px;">
    <thead>
        <tr>
            <th style="width:4%;">No</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
                       <th>Biaya Lain</th>

            <th>DPP</th>
            <th>Pendaftaran</th>
            <th>Almamater</th>
            <th>Semesteran</th>
            <th>Kewajiban All</th>
            <th>Bayar All</th>
            <!-- <th>Sisa All</th> -->
        </tr>
    </thead>

    <tbody>
        @foreach($grouped as $nim => $items)
            @php $rowspan = $items->count(); 
            
             $totalBayar = $items->sum('SUMB');
        $totalKewajiban = $items->sum('SUMK2');
        $totalPotongan = $items->sum('SUMP');
    //  $sisaItem = $totalKewajiban - $totalPotongan - $totalBayar; 



        $totB += $totalBayar;
        $totK2 += $totalKewajiban;
        $totP += $totalPotongan;
            
            
            @endphp
            @foreach($items as $index => $baris)
            <tr>
                @if($index == 0)
                    {{-- Identitas --}}
                    @include('livewire.tagihan_periode_pdf.identitas', [
                        'no' => $no++,
                        'rowspan' => $rowspan,
                        'baris' => $baris
                    ])


                     {{-- Final --}}
                    @include('livewire.tagihan_periode_pdf.final_eksekutif', [
                        'items' => $items,
                        'tanggalFinalEksekutif' => $tanggalFinalEksekutif,
                        'rowspan' => $rowspan
                    ])

                    {{-- DPP --}}
                    @include('livewire.tagihan_periode_pdf.dpp', [
                        'items' => $items,
                        'tanggalDPP' => $tanggalDPP,
                        'rowspan' => $rowspan
                    ])

                    {{-- Pendaftaran --}}
                    @include('livewire.tagihan_periode_pdf.pendaftaran', [
                        'items' => $items,
                        'tanggalPendaftaran' => $tanggalPendaftaran,
                        'rowspan' => $rowspan
                    ])

                    {{-- Almamater --}}
                    @include('livewire.tagihan_periode_pdf.almamater', [
                        'items' => $items,
                        'tanggalAlmamater' => $tanggalAlmamater,
                        'rowspan' => $rowspan
                    ])

                    {{-- Semesteran --}}
                    @include('livewire.tagihan_periode_pdf.semesteran', [
                        'items' => $items,
                        'tanggalSemester' => $tanggalSemester,
                        'rowspan' => $rowspan
                    ])

                    {{-- Tambahan kolom kanan --}}
                    @include('livewire.tagihan_periode_pdf.bayar', [
                        'items' => $grouped2[$nim] ?? [],
                        'sumSemester' => $sumSemester,
                        'rowspan' => $rowspan
                    ])

               {{--   @include('livewire.tagihan_periode_pdf.sisa', [
                        'items' => $grouped2[$nim] ?? [],
                        'sumSemester' => $sumSemester,
                        'rowspan' => $rowspan,
                        'baris' => $baris
                    ])   --}}
                @endif
            </tr>
            @endforeach
        @endforeach

         {{-- BARIS TOTAL --}}
        <tr style="font-weight: bold; background-color: #f0f0f0;">
            <td colspan="8" style="text-align: right; padding: 8px;">TOTAL :</td>
            <td style="text-align: right; padding: 8px;">
                {{ number_format($totK2, 0, ',', '.') }}
            </td>
            <td style="text-align: right; padding: 8px;">
                {{ number_format($totB, 0, ',', '.') }}
            </td>
            <!-- <td style="text-align: right; padding: 8px;"> -->
                {{-- number_format($sisaItem, 0, ',', '.') --}}
            <!-- </td> -->
        </tr>
    </tbody>
</table>