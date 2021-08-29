

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

    @switch($unit)
        @case('eselon1')
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}<br>
            KEGIATAN PENANGANAN COVID-19<br>
            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}<br>
            PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}} </h3>
            <br>

            <table class="table table-sm" id="card">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AWAL</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{{$item['Kode']}}</td>
                        <td class="text-start">{{$item['Keterangan']}}</td>
                        <td class="text-end">{{RP($item['PaguAwal'])}}</td>
                        <td class="text-end">{{RP($item['Pagu'])}}</td>
                        <td class="text-end">{{RP($item['Realisasi'])}}</td>
                        <td class="text-center">{{Persen($item['Persen'])}}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($data->sum('PaguAwal'))}}</th>
                        <th class="text-end">{{RP($data->sum('Pagu'))}}</th>
                        <th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
                    </tr>
                </tfoot>
            </table>

            @break

        @case('propinsi')
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}<br>
            KEGIATAN PENANGANAN COVID-19<br>

            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}<br>
            PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}} </h3>
            <br>

            <table class="table table-sm" id="page-all">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AWAL</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
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
                    @endphp
                    @foreach($item->Data as $satker)
                    @php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                    @endphp
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$satker->Kode}}</td>
                        <td class="text-start">{{$satker->Keterangan}}</td>
                        <td class="text-end">{{RP($satker->PaguAwal)}}</td>
                        <td class="text-end">{{RP($satker->PaguAkhir)}}</td>
                        <td class="text-end">{{RP($satker->Realisasi)}}</td>
                        <td class="text-center">{{Persen($satker->Persen)}}%</td>
                    </tr>
                    @endforeach

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($PaguAwal)}}</th>
                        <th class="text-end">{{RP($PaguAkhir)}}</th>
                        <th class="text-end">{{RP($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                    </tr>

                    @php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    @endphp

                    @endforeach
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end">{{RP($TotalPaguAwal)}}</th>
                        <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
                        <th class="text-end">{{RP($TotalRealisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                    </tr>
                </tfoot>
            </table>

            @break

            @case('satker')
            @if($segment!='volume')
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}<br>
            KEGIATAN PENANGANAN COVID-19<br>

            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}<br>
            PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}} </h3>
            <br>

            <table class="table table-sm" id="page-all">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AWAL</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $PaguAwalSub  = 0;
                        $PaguAkhirSub = 0;
                        $RealisasiSub = 0;

                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="6"><b>{{$item->NamaHeader}}</b></td>
                    </tr>

                    @foreach ($item->Data as $key => $value)
                    <tr class="table-warning">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center"><b>{{$key}}</b></td>
                            <td class="text-start" colspan="5"><b>
                                @if (isset($value->KodeSubHeader))
                                    {{$value->NamaSubHeader}}
                                @endif
                            </b></td>
                    </tr>
                    @php
                        $PaguAwal     = 0;
                        $PaguAkhir    = 0;
                        $Realisasi    = 0;
                    @endphp

                    @foreach ($value->SubData as $detil)
                    @php
                        $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                    @endphp

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$detil->Kode}}</td>
                        <td class="text-start">{{$detil->Keterangan}}</td>
                        <td class="text-end">{{RP($detil->PaguAwal)}}</td>
                        <td class="text-end">{{RP($detil->PaguAkhir)}}</td>
                        <td class="text-end">{{RP($detil->Realisasi)}}</td>
                        <td class="text-center">{{Persen($detil->Persen)}}%</td>
                    </tr>


                    @endforeach

                    @php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    @endphp

                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{RP($PaguAwal)}}</th>
                        <th class="text-end">{{RP($PaguAkhir)}}</th>
                        <th class="text-end">{{RP($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                    </tr>

                    @php
                    $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    @endphp

                    @endforeach

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH PROPINSI</th>
                        <th class="text-end">{{RP($PaguAwalSub)}}</th>
                        <th class="text-end">{{RP($PaguAkhirSub)}}</th>
                        <th class="text-end">{{RP($RealisasiSub)}}</th>
                        <th class="text-center">{{Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)}}%</th>
                    </tr>

                    @php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    @endphp


                    @endforeach
                    <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end">{{RP($TotalPaguAwal)}}</th>
                        <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
                        <th class="text-end">{{RP($TotalRealisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                    </tr>
                    </tfoot>

            </table>
            @else

            <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS BARANG<br>
                KEGIATAN PENANGANAN COVID-19<br>

                {{Auth::user()->satker->NamaSatuanKerja}}<br>
                TAHUN ANGGARAN {{$year}}<br>
                PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}} </h3>
                <br>

                <table class="table table-sm" id="page-all">
                    <thead class="bg-primary text-white">
                        <tr valign="middle">
                            <th class="text-center" rowspan="2">NO</th>
                            <th class="text-start" rowspan="2">KODE</th>
                            <th class="text-start" rowspan="2">KETERANGAN</th>
                            <th class="text-center" rowspan="2">DANA</th>
                            <th class="text-center" colspan="2">PAGU</th>
                            <th class="text-center" rowspan="2"></th>
                            <th class="text-center" colspan="2">REALISASI</th>
                            <th class="text-center" rowspan="2">TGL / NO SP2D</th>
                            <th class="text-center" rowspan="2">%</th>
                            <th class="text-center" rowspan="2">SISA</th>
                        </tr>
                        <tr>
                            <th class="text-center">VOLUME</th>
                            <th class="text-center">RUPIAH</th>
                            <th class="text-center">VOLUME</th>
                            <th class="text-center">RUPIAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $TotalPaguAkhir          = 0;
                            $TotalRealisasi          = 0;
                            $TotalSisa               = 0;
                            $PaguAwalSub             = 0;
                            $PaguAkhirSub            = 0;
                            $RealisasiSub            = 0;
                            $SisaSub                 = 0;
                            $TotalPaguAkhir_kegiatan = 0;
                            $TotalRealisasi_kegiatan = 0;
                            $TotalSisa_kegiatan      = 0;

                        @endphp
                        @foreach ($data as $item)
                        <tr class="table-danger">
                            <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                            <td class="text-start" colspan="11"><b>{{$item->NamaHeader}}</b></td>
                        </tr>

                        @php
                            $PaguAkhirSub_kegiatan = 0;
                            $RealisasiSub_kegiatan = 0;
                            $SisaSub_kegiatan      = 0;

                        @endphp
                    @foreach ($item->Data as $key => $value)
                        <tr class="table-warning">
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center"><b>{{$key}}</b></td>
                                <td class="text-start" colspan="10"><b>
                                    @if (isset($value->KodeSubHeader))
                                        {{$value->NamaSubHeader}}
                                    @endif
                                </b></td>
                        </tr>
                        @php
                            $PaguAkhir = 0;
                            $Realisasi = 0;
                            $Sisa      = 0;
                            $PaguKegiatan_satker     = 0;
                            $BelanjaKegiatan_satker  = 0;
                            $SisaKegiatan_satker     = 0;

                        @endphp

                            @foreach ($value->SubData as $output => $valoutput)
                            @foreach ($valoutput->SubDataKegiatan as $kegiatan => $valkegiatan)
                            @foreach ($valkegiatan->SubDataDana as $dana => $valdana)
                            @foreach ($valdana->SubDataAkun as $akun => $detil)

                        @php
                            $PaguAkhir              += $detil->PaguAkhir ?? '0';
                            $Realisasi              += $detil->Realisasi ?? '0';
                            $Sisa                   += $detil->Sisa ?? '0';
                        @endphp

                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">{{$valkegiatan->KodeKegiatan}}.{{$valoutput->KodeOutput}}.{{$detil->Kode}}</th>
                            <th class="text-start">{{$detil->Keterangan}}</th>
                            <th class="text-center">{{$detil->SumberDana}}</th>
                            <th></th>
                            <th class="text-end">{{RP($detil->PaguAkhir)}}</th>
                            <th></th>
                            <th></th>
                            <th class="text-end">{{RP($detil->Realisasi)}}</th>
                            <th></th>
                            <th class="text-center">{{Persen($detil->Persen)}}%</th>
                            <th class="text-end">{{RP($detil->Sisa)}}</th>
                        </tr>
                        @php
                            $PaguKegiatan    = 0;
                            $BelanjaKegiatan = 0;
                            $SisaKegiatan    = 0;

                        @endphp
                        @foreach ($detil->SubDataKegiatan as $kegiatan)
                        @if($kegiatan->Uraian!='0')
                        @php
                        $PaguKegiatan          += $kegiatan->PaguKegiatan;
                        $BelanjaKegiatan       += $kegiatan->BelanjaKegiatan;
                        $SisaKegiatan          += $kegiatan->SisaKegiatan;

                        $PaguAkhirSub_kegiatan += $kegiatan->PaguKegiatan;
                        $RealisasiSub_kegiatan += $kegiatan->BelanjaKegiatan;
                        $SisaSub_kegiatan      += $kegiatan->SisaKegiatan;


                        @endphp

                        <tr>
                            <td class="text-center"></td>
                            <td class="text-end"></td>
                            <td class="text-start">
                                {{$kegiatan->Uraian}}
                                    @if(!empty($kegiatan->Catatan))<br>
                                    <small><i>{{$kegiatan->Catatan}}</i>
                                    </small>
                                    @endif</td>
                            <td class="text-center">{{$detil->SumberDana}}</td>
                            <td class="text-end"><span class="nowrap">{{RP($kegiatan->VolumePagu)}} {{$kegiatan->SatuanPagu}}</span></td>
                            <td class="text-end">{{RP($kegiatan->PaguKegiatan)}}</td>
                            <td class="text-center"></td>

                            <td class="text-end"><span class="nowrap">@if(!empty($kegiatan->VolumeBelanja)){{RP($kegiatan->VolumeBelanja)}} {{$kegiatan->SatuanBelanja}} @endif</span></td>
                            <td class="text-end">{{RP($kegiatan->BelanjaKegiatan)}}</td>
                            <td class="text-start"><small class="nowrap">{!!nl2br($kegiatan->Tglsp2d)!!}</small></td>

                            <td class="text-center">{{Persen($kegiatan->PersenKegiatan)}}%</td>
                            <td class="text-end">{{RP($kegiatan->SisaKegiatan)}}</td>
                        </tr>
                        @endif
                        @endforeach
                        @php
                            $selisih_pagu      = $PaguAkhir-$PaguKegiatan;
                            $selisih_realisasi = $Realisasi-$BelanjaKegiatan;
                            $total_selisih     = $selisih_pagu+$selisih_realisasi;


                        @endphp
                        <tr class="border-top-primary">
                            <th class="text-center"></th>
                            <th></th>
                            <th class="text-start">SUB JUMLAH</th>
                            <th class="text-center text-danger">@if($total_selisih!=0) <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Terdapat Perbedaan Data UPT dan SPAN" class="icofont icofont-warning-alt"></i> @endif</th>
                            <th></th>
                            <th class="text-end">{{RP($PaguKegiatan)}}</th>
                            <th></th>
                            <th></th>
                            <th class="text-end">{{RP($BelanjaKegiatan)}}</th>
                            <th></th>
                            <th class="text-center">{{Persen(divnum($BelanjaKegiatan,$PaguKegiatan)*100)}}%</th>
                            <th class="text-end">{{RP($SisaKegiatan)}}</th>
                        </tr>

                        @php
                            $PaguKegiatan_satker    += $PaguKegiatan;
                            $BelanjaKegiatan_satker += $BelanjaKegiatan;
                            $SisaKegiatan_satker    += $SisaKegiatan;
                        @endphp

                        @endforeach

                        @endforeach

                        @endforeach


                        @endforeach

                        <tr class="border-top-primary table-info">
                            <th class="text-center"></th>
                            <th></th>
                            <th class="text-start">JUMLAH SATKER</th>
                            <th class="text-center text-danger"></th>
                            <th></th>
                            <th class="text-end">{{RP($PaguKegiatan_satker)}}</th>
                            <th></th>
                            <th></th>
                            <th class="text-end">{{RP($BelanjaKegiatan_satker)}}</th>
                            <th></th>
                            <th class="text-center">{{Persen(divnum($BelanjaKegiatan_satker,$PaguKegiatan_satker)*100)}}%</th>
                            <th class="text-end">{{RP($SisaKegiatan_satker)}}</th>
                        </tr>

                        @php
                            $TotalPaguAkhir          += $PaguAkhir;
                            $TotalRealisasi          += $Realisasi;
                            $TotalSisa               += $Sisa;
                        @endphp

                        {{-- <tr class="border-top-primary">
                            <th class="text-center"></th>
                            <th class="text-start"></th>
                            <th class="text-start">JUMLAH</th>
                            <th class="text-end">{{RP($PaguAkhir)}}</th>
                            <th class="text-end">{{RP($Realisasi)}}</th>
                            <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                            <th class="text-end">{{RP($Sisa)}}</th>
                        </tr> --}}

                        @php
                        $PaguAkhirSub += $PaguAkhir;
                        $RealisasiSub += $Realisasi;
                        $SisaSub      += $Sisa;
                        @endphp

                        @endforeach

                        <tr class="table-danger">
                            <th class="text-center"></th>
                            <th class="text-start" colspan="3">JUMLAH PROPINSI {{$item->NamaHeader}}</th>
                            <th class="text-start"></th>
                            <th class="text-end">{{RP($PaguAkhirSub_kegiatan)}}</th>
                            <th class="text-start"></th>
                            <th></th>
                            <th class="text-end">{{RP($RealisasiSub_kegiatan)}}</th>
                            <th></th>
                            <th class="text-center">{{Persen(divnum($RealisasiSub_kegiatan,$PaguAkhirSub_kegiatan)*100)}}%</th>
                            <th class="text-end">{{RP($SisaSub_kegiatan)}}</th>
                        </tr>

                        @php
                        $PaguAkhirSub             = 0;
                        $RealisasiSub             = 0;
                        $SisaSub                  = 0;
                        $TotalPaguAkhir_kegiatan += $PaguAkhirSub_kegiatan;
                        $TotalRealisasi_kegiatan += $RealisasiSub_kegiatan;
                        $TotalSisa_kegiatan      += $SisaSub_kegiatan;

                        @endphp


                        @endforeach
                        <tr class="table-primary">
                            <th class="text-center"></th>
                            <th class="text-start" colspan="3">JUMLAH RAYA UPT</th>
                            <th class="text-start"></th>
                            <th class="text-end">{{RP($TotalPaguAkhir_kegiatan)}}</th>
                            <th class="text-start"></th>
                            <th></th>
                            <th class="text-end">{{RP($TotalRealisasi_kegiatan)}}</th>
                            <th></th>
                            <th class="text-center">{{Persen(divnum($TotalRealisasi_kegiatan,$TotalPaguAkhir_kegiatan)*100)}}%</th>
                            <th class="text-end">{{RP($TotalSisa_kegiatan)}}</th>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-center"></th>
                            <th class="text-start" colspan="3">JUMLAH RAYA SPAN</th>
                            <th class="text-start"></th>
                            <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
                            <th class="text-start"></th>
                            <th></th>
                            <th class="text-end">{{RP($TotalRealisasi)}}</th>
                            <th></th>
                            <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                            <th class="text-end">{{RP($TotalSisa)}}</th>
                        </tr>

                </table>

            @endif
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
