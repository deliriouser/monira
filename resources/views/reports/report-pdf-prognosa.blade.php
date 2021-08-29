

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
  <body onload="startTime()">

    @switch($unit)
        @case('eselon1')
        <h3>REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}<br>
            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}<br>
            <br>

            <table class="table table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{{$item['Kode']}}</td>
                        <td class="text-start">{{$item['Keterangan']}}</td>
                        <td class="text-end">{{RP($item['Pagu'])}}</td>
                        <td class="text-end">{{RP($item['Realisasi'])}}</td>
                        <td class="text-center">{{Persen($item['Persen'])}}%</td>
                        <td class="text-end">{{RP($item['Prognosa'])}}</td>
                        <td class="text-center">{{Persen($item['PersenPrognosa'])}}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <th></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($data->sum('Pagu'))}}</th>
                        <th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
                        <th class="text-end">{{RP($data->sum('Prognosa'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)}}%</th>
                    </tr>
                </tfoot>
            </table>


            @break

        @case('propinsi')
        <h3>REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}<br>
            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}<br>
            <br>

            <table class="table table-sm" id="page-all">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
                        $TotalPrognosa=0;
                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="7"><b>{{$item->NamaHeader}}</b></td>
                    </tr>
                    @php
                        $noSatker=0;
                        $PaguAwal=0;
                        $PaguAkhir=0;
                        $Realisasi=0;
                        $Prognosa=0;
                    @endphp
                    @foreach($item->Data as $satker)
                    @php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                        $Prognosa+=$satker->Prognosa;
                    @endphp
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$satker->Kode}}</td>
                        <td class="text-start">{{$satker->Keterangan}}</td>
                        <td class="text-end">{{RP($satker->PaguAkhir)}}</td>
                        <td class="text-end">{{RP($satker->Realisasi)}}</td>
                        <td class="text-center">{{Persen($satker->Persen)}}%</td>
                        <td class="text-end">{{RP($satker->Prognosa)}}</td>
                        <td class="text-center">{{Persen($satker->PersenPrognosa)}}%</td>
                    </tr>
                    @endforeach

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($PaguAkhir)}}</th>
                        <th class="text-end">{{RP($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                        <th class="text-end">{{RP($Prognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                    </tr>

                    @php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa += $Prognosa;
                    @endphp

                    @endforeach
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
                        <th class="text-end">{{RP($TotalRealisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                        <th class="text-end">{{RP($TotalPrognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
                    </tr>
                </tfoot>
            </table>



            @break

            @case('satker')
            <h3>REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}<br>
                {{Auth::user()->satker->NamaSatuanKerja}}<br>
                TAHUN ANGGARAN {{$year}}<br>
            <br>

            <table class="table table-sm" id="page-all">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $TotalPrognosa  = 0;
                        $PaguAwalSub    = 0;
                        $PaguAkhirSub   = 0;
                        $RealisasiSub   = 0;
                        $PrognosaSub    = 0;

                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="7"><b>{{$item->NamaHeader}}</b></td>
                    </tr>

                    @foreach ($item->Data as $key => $value)
                    <tr class="table-warning">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center"><b>{{$key}}</b></td>
                            <td class="text-start" colspan="6"><b>
                                @if (isset($value->KodeSubHeader))
                                    {{$value->NamaSubHeader}}
                                @endif
                            </b></td>
                    </tr>
                    @php
                        $PaguAwal  = 0;
                        $PaguAkhir = 0;
                        $Realisasi = 0;
                        $Prognosa  = 0;

                    @endphp

                    @foreach ($value->SubData as $detil)
                    @php
                        // $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                        $Prognosa  += $detil->Prognosa;

                    @endphp

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$detil->Kode}}</td>
                        <td class="text-start">{{$detil->Keterangan}}</td>
                        <td class="text-end">{{RP($detil->PaguAkhir)}}</td>
                        <td class="text-end">{{RP($detil->Realisasi)}}</td>
                        <td class="text-center">{{Persen($detil->Persen)}}%</td>
                        <td class="text-end">{{RP($detil->Prognosa)}}</td>
                        <td class="text-center">{{Persen($detil->PersenPrognosa)}}%</td>
                    </tr>


                    @endforeach

                    @php
                        // $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa  += $Prognosa;

                    @endphp

                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($PaguAkhir)}}</th>
                        <th class="text-end">{{RP($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                        <th class="text-end">{{RP($Prognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                    </tr>

                    @php
                    // $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    $PrognosaSub  += $Prognosa;
                    @endphp

                    @endforeach

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH PROPINSI</th>
                        <th class="text-end">{{RP($PaguAkhirSub)}}</th>
                        <th class="text-end">{{RP($RealisasiSub)}}</th>
                        <th class="text-center">{{Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)}}%</th>
                        <th class="text-end">{{RP($PrognosaSub)}}</th>
                        <th class="text-center">{{Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)}}%</th>
                    </tr>

                    @php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    $PrognosaSub  = 0;
                    @endphp


                    @endforeach
                    <tr class="table-footer text-dark">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
                        <th class="text-end">{{RP($TotalRealisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                        <th class="text-end">{{RP($TotalPrognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
                    </tr>

            </table>

            @break
        @default

    @endswitch
    <footer>
        <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
    </footer>

</body>
</html>
