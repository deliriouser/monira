

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('assets/images/favicon.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" type="image/x-icon">
    <title>MONIRA : MONITORING INFORMASI DAN REALISASI ANGGARAN</title>
    <style>


@page {
            padding-bottom:34px;
        }

        .pagenum:before {
        content: counter(page);
    }
        footer {
            padding-top:10px;
                position: fixed;
                bottom: 1cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;
                text-align:center;
            }

body {
    text-align: center;
    font-size: 9px;
    font-family:Arial, Helvetica, sans-serif;
}

table{
    border-spacing: -1px;}

.table {
    /* border-collapse: collapse; */
    width: 100%;
    page-break-inside: always;
}

.table td, .table th {
    border: 1px solid #ccc;
    padding: 5px;
}
.bg-primary {
    background-color:#0d6efd;
}

.bg-subheader {
    background-color:#ffd6d6;
}

.table-danger {
    background-color:#f0a6ad;
}
.table-warning {
    background-color:#fefbec;
}
.table-success {
    background-color:#d1e7dd;
}

.bg-danger {
    background-color:#de3e4d;
}
.table-footer {
    background-color:#cccccc;;
}

.bg-sumheader {
    background-color:#f4f4f4;;
}
thead {
    text-align:center;
}
.text-white {
    color:#ffffff;
}

.text-dark {
    color:#000000;
    font-weight:bold;
}

.text-center {
    text-align:center;
}
.text-start {
    text-align:left;
}
.text-end {
    text-align:right;
}

h3 {
font-size: 13px;
padding:0px;
margin:0px;
}

</style>

</head>
  <body onload="startTime()">

    <h3>REKAPITULASI PROGNOSA DAYA SERAP<br>
        {{Auth::user()->satker->NamaSatuanKerja}}<br>
        TAHUN ANGGARAN {{$year}}<br>
        <br>

    <table class="table table-sm" id="page-all">
        <thead class="bg-primary text-white">
            <tr>
                <th class="text-center">NO</th>
                <th class="text-start">KODE</th>
                <th class="text-start">KETERANGAN</th>
                <th class="text-center">DANA</th>
                <th class="text-center">PAGU</th>
                <th class="text-center">PROGNOSA</th>
                <th class="text-center">%</th>
                <th class="text-center">SISA ANGGARAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $TotalPaguAwal  = 0;
                $TotalPaguAkhir = 0;
                $TotalRealisasi = 0;
                $TotalPrognosa  = 0;
                $TotalSisa  = 0;
                $PaguAwalSub    = 0;
                $PaguAkhirSub   = 0;
                $RealisasiSub   = 0;
                $PrognosaSub    = 0;
                $SisaSub        = 0;

            @endphp
            @foreach ($data as $item)
            <tr class="table-danger">
                <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                <td class="text-start" colspan="7"><b>{{strtoupper($item->NamaHeader)}}</b></td>
            </tr>

            @foreach ($item->Data as $key => $value)
            <tr class="table-warning">
                    <td class="text-center"{{$loop->iteration}}</td>
                    <td class="text-center"><b>{{$key}}</b></td>
                    <td class="text-start" colspan="7"><b>
                        @if (isset($value->KodeSubHeader))
                            {{strtoupper($value->NamaSubHeader)}}
                        @endif
                    </b></td>
            </tr>
            @php
                $PaguAwal  = 0;
                $PaguAkhir = 0;
                $Realisasi = 0;
                $Prognosa  = 0;
                $Sisa      = 0;
            @endphp

            @foreach ($value->SubData as $detil)
            @php
                $PaguAwal  += $detil->PaguAwal ?? '0';
                $PaguAkhir += $detil->PaguAkhir ?? '0';
                $Realisasi += $detil->Realisasi ?? '0';
                $Prognosa  += $detil->Prognosa ?? '0';
                $Sisa      = $PaguAkhir-$Prognosa;
            @endphp

            <tr valign="middle">
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$detil->Kode}}</td>
                <td class="text-start">{{$detil->Keterangan}}
                    @if(isset($detil->Justifikasi))
                    <br><small><i>Justifikasi : {{$detil->Justifikasi}}</i></small>
                    @endif
                </td>
                <td class="text-center">{{($detil->NamaDana)}}</td>
                <td class="text-end">{{RP($detil->PaguAkhir)}}</td>
                <td class="text-end">{{RP($detil->Prognosa)}}</td>
                <td class="text-center">{{Persen($detil->Persen)}}%</td>
                <td class="text-end">
                    {{RP($detil->PaguAkhir-$detil->Prognosa)}}
                </td>
            </tr>


            @endforeach

            @php
                $TotalPaguAwal  += $PaguAwal;
                $TotalPaguAkhir += $PaguAkhir;
                $TotalRealisasi += $Realisasi;
                $TotalPrognosa  += $Prognosa;
                $TotalSisa      += $Sisa;
            @endphp

            <tr class="border-top-primary">
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start">JUMLAH</th>
                <th class="text-end"></th>
                <th class="text-end">{{RP($PaguAkhir)}}</th>
                <th class="text-end">{{RP($Prognosa)}}</th>
                <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                <th class="text-end">{{RP($Sisa)}}</th>
            </tr>

            @php
            $PaguAwalSub  += $PaguAwal;
            $PaguAkhirSub += $PaguAkhir;
            $RealisasiSub += $Realisasi;
            $PrognosaSub  += $Prognosa;
            $SisaSub      += $Sisa;
            @endphp

            @endforeach

            <tr class="table-info">
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start">JUMLAH SUB</th>
                <th class="text-end"></th>
                <th class="text-end">{{RP($PaguAkhirSub)}}</th>
                <th class="text-end">{{RP($PrognosaSub)}}</th>
                <th class="text-center">{{Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)}}%</th>
                <th class="text-end">{{RP($SisaSub)}}</th>
            </tr>

            @php
            $PaguAwalSub  = 0;
            $PaguAkhirSub = 0;
            $RealisasiSub = 0;
            $PrognosaSub  = 0;
            @endphp


            @endforeach
            <tr class="table-primary">
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start">JUMLAH RAYA</th>
                <th class="text-end"></th>
                <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
                <th class="text-end">{{RP($TotalPrognosa)}}</th>
                <th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
                <th class="text-end">{{RP($TotalSisa)}}</th>
            </tr>
    </table>



    <footer>
        <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
    </footer>

</body>
</html>
