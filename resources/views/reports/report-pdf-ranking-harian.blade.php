


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

        <h3>REALISASI BELANJA SATKER<br>BERDASARKAN RANGKING TERBESAR KE TERENDAH <br>
            DIREKTORAT JENDERAL PERHUBUNGAN LAUT<br>
            TAHUN ANGGARAN {{date('Y')}}<br><br>
            <small>POSISI {{date('d')}} {{strtoupper(nameofmonth(date('n')))}} {{date('Y')}}</small>
            <br>
            <br>

            <table class="table table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">NAMA SATKER</th>
                        <th class="text-center">PAGU</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">SISA</th>
                        <th class="text-center">PROGNOSA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr class="@if($item->Persen>$top) table-success @elseif($item->Persen>$bottom AND $item->Persen<$top) table-warning @else table-danger @endif">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$item->KodeSatker}}</td>
                        <td class="text-start">{{$item->NamaSatuanKerja}}</td>
                        <td class="text-end">{{RP($item->Pagu)}}</td>
                        <td class="text-end">{{RP($item->Realisasi)}}</td>
                        <td class="text-center">{{Persen($item->Persen)}}%</td>
                        <td class="text-end">{{RP($item->Sisa)}}</td>
                        <td class="text-center">
                            @if ($item->Persen_prognosa>0 and $item->Persen_prognosa<100)
                            {{Persen($item->Persen_prognosa)}}%
                            @elseif($item->Persen_prognosa>100)
                            {{Persen($item->Persen_satker)}}%
                            @else
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- <tfoot class="table-primary">
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($data->sum('Pagu'))}}</th>
                        <th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
                        <th class="text-end">{{RP($data->sum('Sisa'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)}}%</th>
                    </tr>
                </tfoot> --}}
            </table>
<div style="margin-top:10px; text-align:left;">
Keterangan<br>
Hijau : Realisasi Telah Melampaui {{$top}}%<br>
Kuning : Realisasi Masih Dibawah {{$top}}%<br>
Merah : Realisasi Masih Dibawah Target Prognosa Bulan Berjalan {{$bottom}}%<br>
Prognosa Kosong : UPT/Satker Belum Kirim SPTJM<br>
</div>
    <footer>
        <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
    </footer>

</body>
</html>
