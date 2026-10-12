<td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
    <div style="text-align: right;">
        @foreach ($tanggalAlmamater as $tgl)
            @php
            $totalBayar = $items
                ->filter(function($i) use ($tgl) {
                    return in_array($i->nama_item, [
                                'Almamater, Topi Dan Buku',
                                'Kaos Gmti Dan Tas',
                                'Biaya Orientasi Jaket & Almamater',
                            ]) !== false
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
