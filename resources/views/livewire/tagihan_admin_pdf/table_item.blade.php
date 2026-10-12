@php
$iy = 0; $totB = 0; $totK2 = 0; $totP = 0;
@endphp


<table cellspacing="0" cellpadding="5" width="100%">
  <thead>
    <tr>
      <th style="width:4%;">No</th>
      <th>NIM</th>
      <th>Nama Mahasiswa</th>
      <th>Status</th>
      <th>Kewajiban</th>
      <th>Bayar</th>




      <th style="width:12%;" class="text-right">Sisa</th>
    </tr>
  </thead>
  <tbody>
    @php
    $iy = 0; $totB = 0; $totK2 = 0; $totP = 0;
    @endphp

    @foreach($mhs as $baris)
    @php
    $iy++;
    $sumb = floatval($baris['total_bayar'] ?? 0);
    $sumk2 = floatval($baris['kewajiban'] ?? 0);

    $sisa = $sumk2 - $sumb;
    $totB += $sumb; $totK2 += $sumk2;

    @endphp
    <tr>
      <td class="center">{{ $iy }}</td>

      <td>{{ $baris['nim'] ?? '-' }}</td>

      <td>{{ $baris['nama'] ?? '-' }}</td>


      <td> <?php
            if ($baris['status'] == 1) {
              echo "REG";
            } elseif ($baris['status'] == 2) {
              echo "LN";
            } else {

              echo "-";
            }

            ?></td>

      <td class="text-right">{{ number_format($baris['kewajiban'],2) }}</td>

      <td class="text-right">{{ number_format($baris['total_bayar'],2) }}</td>




      <td class="text-right">{{ number_format($baris['sisa'],2) }}</td>

    </tr>
    @endforeach

    {{-- Total per tahun --}}
    <tr style="font-weight:700; background:#eef4ff;">
      <td></td>
      <td>TOTAL</td>

      <td></td>
      <td></td>
      <td class="text-right">{{ number_format($totK2,2) }}</td>

      <td class="text-right">{{ number_format($totB,2) }}</td>
      <td class="text-right">{{ number_format($totK2 - $totB,2) }}</td>
    </tr>
  </tbody>
</table>