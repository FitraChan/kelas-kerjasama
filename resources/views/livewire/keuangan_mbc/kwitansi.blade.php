<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kwitansi {{ $bayar->no_kwitansi }}</title>

    <style>

        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .page {
            width: 210mm;
            height: 297mm;
            position: relative;
            padding-top: 35mm;
        }


        /* =====================================================
           AREA KWITANSI
        ====================================================== */

        .kwitansi {
            width: 165mm;
            margin-left: 28mm;
            position: relative;
        }


        /* =====================================================
           LOGO / HEADER
        ====================================================== */

        .logo-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 32mm;
            height: 15mm;
        }

        .logo-box {
            width: 13mm;
            height: 13mm;
            float: left;
            margin-right: 2mm;
        }

        .logo-box img {
            width: 13mm;
            height: 13mm;
            object-fit: contain;
        }

        .logo-text {
            font-size: 7px;
            line-height: 9px;
            font-weight: bold;
            padding-top: 2mm;
            color: #555;
        }

        .logo-text-small {
            font-size: 5px;
            font-weight: normal;
            color: #777;
        }


        /* =====================================================
           KOTAK KIRI
        ====================================================== */

        .left-box {
            position: absolute;
            left: 0;
            top: 18mm;

            width: 24mm;
            height: 75mm;

            border: 1.5px solid #333;
            border-radius: 5mm;
        }


        /* =====================================================
           CONTENT KANAN
        ====================================================== */

        .content {
            margin-left: 30mm;
            width: 135mm;
        }


        .row {
            width: 100%;
            min-height: 10mm;
        }

        .label {
            display: inline-block;
            width: 42mm;
            font-size: 11px;
        }

        .colon {
            display: inline-block;
            width: 5mm;
            text-align: center;
        }

        .value {
            display: inline-block;
            width: 82mm;
            font-size: 11px;
        }


        /* =====================================================
           NO KWITANSI
        ====================================================== */

        .no-kwitansi {
            font-weight: normal;
        }


        /* =====================================================
           KETERANGAN
        ====================================================== */

        .keterangan {
            font-style: italic;
        }


        /* =====================================================
           DETAIL PEMBAYARAN
        ====================================================== */

        .detail {
            width: 100%;
            margin-top: 4mm;

            border-top: 1px solid #555;
            border-bottom: 1px solid #555;

            padding: 2mm 0;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table td {
            vertical-align: top;
            padding: 1mm 0;
            font-size: 10px;
        }

        .detail-keterangan {
            width: 48%;
            font-style: italic;
        }

        .detail-tanggal {
            width: 25%;
        }

        .detail-jumlah {
            width: 27%;
            text-align: right;
        }


        /* =====================================================
           AREA BAWAH
        ====================================================== */

        .bottom {
            margin-top: 6mm;
            width: 100%;
            position: relative;
            height: 35mm;
        }


        /* =====================================================
           JUMLAH
        ====================================================== */

        .jumlah-label {
            position: absolute;
            left: 0;
            top: 19mm;

            font-size: 11px;
            font-style: italic;
            font-weight: bold;
        }

        .jumlah-box {
            position: absolute;
            left: 27mm;
            top: 16mm;

            width: 48mm;
            height: 10mm;

            border: 1px solid #555;

            text-align: center;
            padding-top: 2.5mm;

            font-weight: bold;
            font-size: 12px;

            transform: skewX(-25deg);
        }

        .jumlah-text {
            transform: skewX(25deg);
        }


        /* =====================================================
           TANGGAL + PENERIMA
        ====================================================== */

        .signature {
            position: absolute;
            right: 0;
            top: 0;

            width: 50mm;

            text-align: center;
        }

        .signature-date {
            font-size: 10px;
            margin-bottom: 1mm;
        }

        .signature-title {
            font-size: 10px;
        }

        .signature-space {
            height: 17mm;
        }

        .signature-name {
            border-bottom: 1px solid #555;
            padding-bottom: 1mm;

            font-size: 10px;
            font-weight: bold;
        }

        .signature-company {
            margin-top: 2mm;
            font-size: 9px;
            color: #0070c0;
            font-weight: bold;
        }


        /* =====================================================
           TERBILANG
        ====================================================== */

        .terbilang {
            position: absolute;
            left: 0;
            top: 5mm;

            width: 90mm;

            font-size: 9px;
            font-style: italic;
        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {
            position: absolute;

            left: 28mm;
            top: 105mm;

            width: 165mm;

            font-size: 8px;
            color: #777;
        }

    </style>

</head>


<body>

<div class="page">

    <div class="kwitansi">


        {{-- =====================================================
             LOGO
        ====================================================== --}}

        <div class="logo-area">

            <div class="logo-box">

                {{-- Jika punya logo, gunakan ini --}}
<img src="{{ public_path('images/ke_kwitansi.png') }}"
     style="width: 100px;">
                <!-- <div style="
                    font-size: 30px;
                    font-weight: bold;
                    color: #4385d1;
                    line-height: 30px;
                ">
                    K
                </div> -->

            </div>


          
        </div>


        {{-- =====================================================
             KOTAK KIRI
        ====================================================== --}}

        <div class="left-box">
        </div>


        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <div class="content">


            {{-- NO KWITANSI --}}

            <div class="row">

                <span class="label">
                    KWITANSI No.
                </span>

                <span class="colon">
                    :
                </span>

                <span class="value no-kwitansi">
                    {{ $bayar->no_kwitansi }}
                </span>

            </div>


            {{-- SUDAH TERIMA DARI --}}

            <div class="row">

                <span class="label">
                    Sudah terima dari
                </span>

                <span class="colon">
                    :
                </span>

                <span class="value">

                    {{ $bayar->mahasiswa->str_nm_mhs ?? '' }}

                </span>

            </div>


            {{-- KETERANGAN --}}

            <div class="row">

                <span class="label keterangan">
                    Untuk pembayaran
                </span>

                <span class="colon">
                    :
                </span>

                <span class="value keterangan">

                    {{ $bayar->ket_simple }}

                </span>

            </div>


            {{-- =================================================
                 DETAIL
            ================================================== --}}

            <div class="detail">

                <table class="detail-table">

                    <tr>

                        <td class="detail-keterangan">

                            :

                            {{ $bayar->ket_simple }}

                        </td>


                        <td class="detail-tanggal">

                            <strong>
                                Bulan/Tgl
                            </strong>

                            <br>

                            {{ \Carbon\Carbon::parse($bayar->tgl_bayar)->format('d-M-Y') }}

                        </td>


                        <td class="detail-jumlah">

                            Rp{{ number_format(
                                $dibayar,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <td>
                            -
                        </td>

                        <td>
                        </td>

                        <td class="detail-jumlah">
                            -
                        </td>

                    </tr>

                </table>

            </div>


            {{-- =================================================
                 BOTTOM
            ================================================== --}}

            <div class="bottom">


                {{-- TERBILANG --}}

                <div class="terbilang">

                    Terbilang:

                    <strong>
                        {{ ucwords($terbilang) }} rupiah
                    </strong>

                </div>


                {{-- JUMLAH --}}

                <div class="jumlah-label">

                    Jumlah

                </div>


                <div class="jumlah-box">

                    <div class="jumlah-text">

                        Rp{{ number_format(
                            $dibayar,
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>

                </div>


                {{-- TANDA TANGAN --}}

                <div class="signature">

                    <div class="signature-date">

                        Denpasar,
                        {{ \Carbon\Carbon::parse(
                            $bayar->tgl_bayar
                        )->format('d-M-Y') }}

                    </div>


                    <div class="signature-title">

                        Penerima,

                    </div>


                    <div class="signature-space">

                        {{-- tempat tanda tangan --}}

                    </div>


                    <div class="signature-name">

                          I Gst Ayu Alit Budiari


                    </div>


                    <div class="signature-company">

                      
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="footer">

            Dicetak pada:
            {{ now()->format('d-m-Y H:i:s') }}

        </div>


    </div>

</div>

</body>

</html>