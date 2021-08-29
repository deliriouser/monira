

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
<body>

<div class="container-default">
    <h3>REKAPITULASI RENCANA PENARIKAN DANA MP PNBP<br>
        {{Auth::user()->satker->NamaSatuanKerja}}<br>
        TAHUN ANGGARAN {{$year}}<br>
        <br>

    <table class="table table-sm">
        <thead class="bg-primary text-white">
            <tr>
                <th class="text-center">NO</th>
                <th class="text-start">BULAN</th>
                <th class="text-center">RPD MP</th>
                <th class="text-center">ALOKASI MP</th>
                <th class="text-center">DAYA SERAP MP</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_rpd = 0;
                $total_mp  = 0;
                $total_dsa = 0;
                $bulan_depan = Date('n')+1;
            @endphp
            @foreach ($data as $item)
            @if($item->id<=$bulan_depan)
            @php
            $total_rpd += $item->rpd->jumlah ?? '0';
            $total_mp  += $item->mp->Amount ?? '0';
            $total_dsa += $item->dsa->Amount ?? '0';
            @endphp

            <tr>
                <td valign="middle" class="text-center">{{$loop->iteration}}</td>
                <td valign="middle" class="text-start">{{$item->BulanName}}</td>
                <td valign="middle" class="text-end">{{RP($item->rpd->jumlah ?? '0')}}</td>
                <td valign="middle" class="text-end">{{RP($item->mp->Amount ?? '0')}}</td>
                <td valign="middle" class="text-end">{{RP($item->dsa->Amount ?? '0')}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
        <tr class="bg-subheader">
            <th></th>
                <th class="text-start">JUMLAH</th>
                <th class="text-end">{{RP($total_rpd)}}</th>
                <th class="text-end">{{RP($total_mp)}}</th>
                <th class="text-end">{{RP($total_dsa)}}</th>
            </tr>
            <tr class="bg-sumheader">
                <th></th>
                <th class="text-start">JUMLAH PAGU PNBP</th>
                <th class="text-end">{{RP($paguPNBP)}}</th>
                <th class="text-end"></th>
                <th class="text-end"></th>
            </tr>
            <tr class="bg-footer">
                <th></th>
                <th class="text-start">SISA RPD</th>
                <th class="text-end">{{RP($paguPNBP-$total_rpd)}}</th>
                <th class="text-end"></th>
                <th class="text-end"></th>
            </tr>

    </table>
</div>

    <footer>
        <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
    </footer>

</body>
</html>
