

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
            color:#ffffff !important;
        }
        .bg-success {
            background-color:#f4f4f4;
            color:#000000 !important;
        }
        .table-footer {
            background-color:#cccccc;
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
    <h3>REKAPITULASI PAGU JENIS AKUN<br>
        KEGIATAN PADAT KARYA<br>
        DIREKTORAT JENDERAL PERHUBUNGAN LAUT<br>

        TAHUN ANGGARAN {{$year}}<br>
        <br>

        <table class="table table-sm" id="card">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">KODE</th>
                    <th class="text-start">KETERANGAN</th>
                    <th class="text-center"></th>
                    <th class="text-end">PAGU</th>
                    <th class="text-end">REALISASI</th>
                    <th class="text-center">%</th>
                    <th class="text-end">SISA</th>

                </tr>
            </thead>
            <tbody>
                @php
                $totalpagu = 0;
                $totaldsa  = 0;
                $totalsisa = 0;


                @endphp
                @foreach ($data as $item)
                <tr class="table-danger">
                    <th class="text-center">{{$item->KodeWilayah}}</th>
                    <th class="text-start" colspan="7">{{$item->NamaWilayah}}</th>
                </tr>
                @php
                    $totalpaguProp = 0;
                    $totaldsaProp  = 0;
                    $totalsisaProp = 0;
                @endphp
                @foreach ($item->Program as $item)
                <tr class="table-danger">
                    <th class="text-end"></th>
                    <th class="text-start">{{$item->KodeProgram}}</th>
                    <th class="text-start">{{$item->NamaProgram}}</th>
                    <th class="text-end" colspan="5"></th>
                </tr>
                @foreach ($item->Kegiatan as $Kegiatan)
                <tr class="table-warning">
                    <th class="text-end"></th>
                    <th class="text-start">{{$Kegiatan->KodeKegiatan}}</th>
                    <th class="text-start">{{$Kegiatan->NamaKegiatan}}</th>
                    <th class="text-end" colspan="5"></th>
                </tr>
                @foreach ($Kegiatan->Output as $Output)
                <tr class="table-success">
                    <th class="text-end"></th>
                    <th class="text-start">{{$Output->KodeOutput}}</th>
                    <th class="text-start">{{$Output->NamaOutput}}</th>
                    <th class="text-end" colspan="5"></th>
                </tr>
                @foreach ($Output->Akun as $Akun)
                <tr>
                    <td class="text-center"></td>
                    <td class="text-start">{{$Akun->KodeAkun}}</td>
                    <td class="text-start">{{$Akun->NamaAkun}}</td>
                    <td class="text-center">{{$Akun->KodeSumberDana}}</td>
                    <td class="text-end">{{RP($Akun->Pagu)}}</td>
                    <td class="text-end">{{RP($Akun->Dsa)}}</td>
                    <td class="text-center">{{Persen($Akun->Persen)}}%</td>
                    <td class="text-end">{{RP($Akun->Sisa)}}</td>

                </tr>
                @php
                    $totalpagu += $Akun->Pagu;
                    $totaldsa  += $Akun->Dsa;
                    $totalsisa += $Akun->Sisa;
                @endphp
                @php
                    $totalpaguProp += $Akun->Pagu;
                    $totaldsaProp  += $Akun->Dsa;
                    $totalsisaProp += $Akun->Sisa;
                @endphp
                @endforeach
                @endforeach
                @endforeach

                @endforeach
                <tr>
                    <th class="text-center"></th>
                    <th class="text-start"></th>
                    <th class="text-start">JUMLAH PROPINSI</th>
                    <th class="text-center"></th>
                    <th class="text-end">{{RP($totalpaguProp)}}</th>
                    <th class="text-end">{{RP($totaldsaProp)}}</th>
                    <th class="text-center">{{Persen(divnum($totaldsaProp,$totalpaguProp)*100)}}%</th>
                    <th class="text-end">{{RP($totalsisaProp)}}</th>
                </tr>

                @endforeach
            </tbody>
            <tfoot class="table-primary">
                <tr>
                    <th></th>
                    <th class="text-end"></th>
                    <th class="text-start">JUMLAH</th>
                    <th class="text-end"></th>
                    <th class="text-end">{{RP($totalpagu)}}</th>
                    <th class="text-end">{{RP($totaldsa)}}</th>
                    <th class="text-center">{{Persen(divnum($totaldsa,$totalpagu)*100)}}%</th>
                    <th class="text-end">{{RP($totalsisa)}}</th>
                </tr>
            </tfoot>
        </table>
<footer>
    <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
    MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
    Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
</footer>

</body>
</html>
