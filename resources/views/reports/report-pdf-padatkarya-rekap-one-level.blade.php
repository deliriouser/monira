

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
    <h3>REKAPITULASI<br>
        KEGIATAN PADAT KARYA<br>
        DIREKTORAT JENDERAL PERHUBUNGAN LAUT<br>

        TAHUN ANGGARAN {{$year}}<br>
        <br>
<table class="table table-sm" id="card">
    <thead class="bg-primary text-white">
        <tr class="bg-primary">
            <th class="text-center" colspan="5">TARGET</th>
            <th class="text-center" rowspan="2"></th>
            <th class="text-center" colspan="5">REALISASI</th>
        </tr>
        <tr>
            <th class="col-1 text-center">TOTAL PAGU KEGIATAN`</th>
            <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
            <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
            <th class="col-1 text-center">TOTAL REALISASI</th>
            <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
            <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)

        <tr>
            <td class="text-end">{{RP($item->Target_TotalPagu)}}</td>
            <td class="text-end">{{RP($item->Target_TotalBiayaLain)}}</td>
            <td class="text-end">{{RP($item->Target_TotalBiayaUpah)}}</td>
            <td class="text-center">{{RP($item->Target_JumlahOrang)}}</td>
            <td class="text-center">{{RP($item->Target_JumlahOrangHari)}}</td>
            <td></td>
            <td class="text-end">{{RP($item->Daser_TotalPagu)}}</td>
            <td class="text-end">{{RP($item->Daser_TotalBiayaLain)}}</td>
            <td class="text-end">{{RP($item->Daser_TotalBiayaUpah)}}</td>
            <td class="text-center">{{RP($item->Daser_JumlahOrang)}}</td>
            <td class="text-center">{{RP($item->Daser_JumlahOrangHari)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<footer>
    <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
    MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
    Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
</footer>


</body>
</html>
