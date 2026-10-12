<td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
    <div style="text-align: right;">
        @foreach ($tanggalDPP as $tgl)
            @php
            $totalBayar = $items
                ->filter(function($i) use ($tgl) {
                    return stripos($i->nama_item, 'DPP') !== false
                           && date('Y-m-d', strtotime($i->tgl_bayar)) == date('Y-m-d', strtotime($tgl))
                           && ($i->SUMB ?? 0) != 0;
                })
                ->sum('SUMB');
            @endphp
            @if($totalBayar > 0)
                <div>{{ date('d-M-Y', strtotime($tgl)) }} : {{ number_format($totalBayar, 0, ',', '.') }}</div>
            @endif
        @endforeach
    </div>
</td>
