<!-- <td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
    <div style="text-align: right;">
        @foreach ($sumSemester as $tahun)
            @php
            $totalBayar = $items
                ->filter(function($i) use ($tahun){
                    return ($i->SUMB ?? 0) != 0;
                })
                ->sum('SUMB');
            @endphp
            @if($totalBayar > 0)
                <div>{{ number_format($totalBayar, 0, ',', '.') }}</div>
            @endif
        @endforeach
    </div>
</td> -->

<td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
  <div style="text-align: right;">
      @php
      $tahun = $sumSemester->last(); // ambil tahun terakhir

      $totalBayar = $items
          ->filter(function($i) use ($tahun){
              return  ($i->SUMK2 ?? 0) != 0;
          })
          ->sum('SUMK2');

     
      @endphp

      <div>
        
        {{ number_format($totalBayar, 0, ',', '.') }}
      </div>
  </div>
</td>

<td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
  <div style="text-align: right;">
      @php
      $tahun = $sumSemester->last(); // ambil tahun terakhir

      $totalBayar = $items
          ->filter(function($i) use ($tahun){
              return  ($i->SUMB ?? 0) != 0;
          })
          ->sum('SUMB');

     
      @endphp

      <div>
        
        {{ number_format($totalBayar, 0, ',', '.') }}
      </div>
  </div>
</td>
