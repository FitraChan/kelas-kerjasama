<td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
    <div style="text-align: right;">
        @foreach ($tanggalFinalEksekutif as $tgl)
          @php
$itemsPerTanggal = $items
    ->filter(function($i) use ($tgl) {
        return in_array($i->nama_item, ['Final Eksekutif','Biaya Admin','Bimbingan TA'])
               && date('Y-m-d', strtotime($i->tgl_bayar)) == date('Y-m-d', strtotime($tgl))
               && ($i->SUMB ?? 0) != 0;
    });

$totalBayar = $itemsPerTanggal->sum('SUMB');

$namaItems = $itemsPerTanggal->pluck('nama_item')->unique()->implode('<br>');
@endphp

@if($totalBayar > 0)
<div>
    {{ date('d-M-Y', strtotime($tgl)) }} :
    {{ number_format($totalBayar, 0, ',', '.') }}
    <br>
   ( {!! $namaItems !!} )
</div>
@endif

        @endforeach
    </div>
</td>
