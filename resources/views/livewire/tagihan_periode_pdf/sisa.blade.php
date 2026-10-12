<!-- <td rowspan="{{ $rowspan }}" style="vertical-align: top; padding: 4px;">
    <div style="text-align: right;">
        @foreach ($sumSemester as $tahun)
            @php
                $totalBayar = $items
                    ->filter(function($i) use ($tahun){
                        return ($i->SUMB ?? 0) != 0;
                    })
                    ->sum('SUMB');

                $totalKewajiban = $items->sum('SUMK2');
                $sisaAll = $totalKewajiban - $totalBayar;
            @endphp

            @if($totalBayar > 0)
                <div>
                    <div>{{ date('Y', strtotime($tahun)) }} :</div>
                    {{-- {{ number_format($sisaAll, 0, ',', '.') }} --}}
                    {{ number_format($sisaAll, 0, ',', '.') }} 
                </div>
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
              return ($i->SUMB ?? 0) != 0;
          })
          ->sum('SUMB');

      $totalKewajiban = $items->sum('SUMK2');
      $sisaAll = $totalKewajiban - $totalBayar;
      @endphp
    @if($totalBayar > 0)
      <div>
        
        {{ number_format($sisaAll, 0, ',', '.') }}
      </div>
     @endif   
  </div>
</td> 


