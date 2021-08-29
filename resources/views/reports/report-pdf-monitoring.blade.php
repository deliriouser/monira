

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

        h4 {
        font-size: 11px;
        padding:0px;
        margin:0px;
        }

    </style>

</head>
  <body onload="startTime()">

    @switch($segment)
        @case('covid')
        <h3>REKAPITULASI REALISASI DAYA SERAP<br>
            KEGIATAN PENANGANAN COVID-19<br>
            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}<br>
            PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}} </h3>
            <br>


            <table class="table table-sm loadSK" id="page-all">
                <thead class="bg-primary text-white">
                    <tr valign="middle">
                        <th class="text-center" rowspan="2">NO</th>
                        <th class="text-start" rowspan="2">KODE</th>
                        <th class="text-start" rowspan="2">KETERANGAN</th>
                        <th class="text-center" rowspan="2">DANA</th>
                        <th class="text-center" colspan="2">PAGU</th>
                        <th class="text-center" colspan="2">REALISASI</th>
                        <th class="text-center" rowspan="2">%</th>
                        <th class="text-center" rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center">VOL</th>
                        <th class="text-center">RUPIAH</th>
                        <th class="text-center">VOL</th>
                        <th class="text-center">RUPIAH</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        // $TotalPrognosa = 0;
                        $PaguAwalSub  = 0;
                        $PaguAkhirSub = 0;
                        $RealisasiSub = 0;
                        // $PrognosaSub = 0;
                        $TotalPaguAkhirCovid = 0;
                        $TotalRealisasiCovid = 0;

                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="9"><b>{{strtoupper($item->NamaHeader)}}</b></td>
                    </tr>
                    @foreach ($item->Data as $key => $value)
                    <tr class="table-warning">
                            <td class="text-center"{{$loop->iteration}}</td>
                            <td class="text-center"><b>{{$key}}</b></td>
                            <td class="text-start" colspan="8"><b>
                                @if (isset($value->KodeSubHeader))
                                    {{strtoupper($value->NamaSubHeader)}}
                                @endif
                            </b></td>
                    </tr>
                    @php
                        $PaguAwal     = 0;
                        $PaguAkhir    = 0;
                        $Realisasi    = 0;
                        // $Prognosa    = 0;
                    @endphp
                    @foreach ($value->SubData as $detilsub)
                    @foreach ($detilsub->SubDataDana as $detil)
                    @php
                        $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                        // $Prognosa  += $detil->Prognosa ?? '0';
                    @endphp
                    <tr valign="middle" class="table-success">
                        <th class="text-center">{{$loop->iteration}}</th>
                        <th class="text-center">{{$detil->Kode}}</th>
                        <th class="text-start">{{($detil->Keterangan)}}</th>
                        <th class="text-center">{{($detil->NamaDana)}}</th>
                        <th></th>
                        <th class="text-end">{{RP($detil->PaguAkhir)}}</th>
                        <th></th>
                        <th class="text-end">{{RP($detil->Realisasi)}}</th>
                        <th class="text-center">{{Persen($detil->Persen)}}%</th>
                        <th class="text-center">
                            <a data-intro="Tambah Uraian Kegiatan" title="Tambah Uraian Kegiatan" dana="{{$detil->NamaDana}}" akun="{{$detil->Kode}}. {{$detil->Keterangan}}" output="{{$key}}. {{$value->NamaSubHeader}}" kegiatan="{{$item->KodeHeader}}. {{$item->NamaHeader}}" pagu="{{$detil->PaguAkhir}}"  id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" href="#" class="open-modal-monitoring text-primary static" action="insertKegiatanCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                        </th>
                    </tr>
                    @php
                        $totalPaguKegiatan    = 0;
                        $totalBelanjaKegiatan = 0;
                        $number               = 0;
                    @endphp
                    @foreach ($detil->SubDataKegiatan as $uraian)
                    @if(!empty($uraian->PaguKegiatan))
                    @php
                        $sisa = $uraian->PaguKegiatan-$uraian->BelanjaKegiatan;
                    @endphp
                    <tr valign="middle">
                        <td></td>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-start">{{$uraian->Uraian}} @if(!empty($uraian->Catatan))<br><small><i>{{$uraian->Catatan}}</i></small>@endif</td>
                        <td></td>
                        <td class="text-center"><span class="nowrap">{{$uraian->VolumePagu}} {{$uraian->SatuanPagu}}</span></td>
                        <td class="text-end">{{RP($uraian->PaguKegiatan)}}</td>
                        <td class="text-center"><span class="nowrap">{{$uraian->VolumeBelanja}} {{$uraian->SatuanBelanja}}</span></td>
                        <td class="text-end">{{RP($uraian->BelanjaKegiatan)}}</td>
                        <td class="text-center">{{Persen(divnum($uraian->BelanjaKegiatan,$uraian->PaguKegiatan)*100)}}%</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a data-intro="Edit Data Uraian Kegiatan" title="Edit Data" dana="{{$uraian->VolumePagu}} {{$uraian->SatuanPagu}}" id="{{$uraian->Guid}}" output="{{$uraian->SatuanPagu}}" kegiatan="{{$uraian->Uraian}}" pagu="{{$uraian->PaguKegiatan}}"  akun="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" sisa="{{$sisa}}" href="#" class="mr-xl-5 open-modal-monitoring text-primary static" action="updateKegiatanCovid" style="font-size:20px;"><i class="icofont icofont-earth"></i></a>
                                <a data-intro="Tambah Realisasi Kegiatan" title="Input Realisasi" dana="{{$uraian->VolumePagu}} {{$uraian->SatuanPagu}}" akun="{{$uraian->Guid}}" output="{{$uraian->SatuanPagu}}" kegiatan="{{$uraian->Uraian}}" pagu="{{$uraian->PaguKegiatan}}"  id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" sisa="{{$sisa}}" href="#" class="open-modal-monitoring text-success static" action="insertRealisasiCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                <a data-intro="Lihat Rincian Realisasi" title="Lihat Rincian Realisasi" href="#" id="{{$uraian->Guid}}"class="mr-xl-5 open-modal-monitoring text-primary static" action="dataRealisasiCovid" style="font-size:20px;"><i class="fa fa-info-circle"></i></a>

                                <a data-intro="Hapus Kegiatan" title="Hapus Kegiatan" onclick="confirmation_disabled(event)" href="{{route('satker/monitoring/status',['status'=>'0', 'id'=> $uraian->Guid,'what'=>'kegiatan'])}}" class="text-danger static" style="font-size:20px;"><i class="fa fa-times-circle"></i></a>
                              </div>
                        </td>
                    </tr>
                    @php
                        $totalPaguKegiatan += $uraian->PaguKegiatan;
                        $totalBelanjaKegiatan  += $uraian->BelanjaKegiatan;
                    @endphp
                    @endif
                    @endforeach
                    @php
                        $TotalPaguAwal       += $PaguAwal;
                        $TotalPaguAkhirCovid += $totalPaguKegiatan;
                        $TotalRealisasiCovid += $totalBelanjaKegiatan;
                        // $TotalPrognosa  += $Prognosa;
                        $selisih_pagu = $detil->PaguAkhir-$totalPaguKegiatan;
                        $selisih_realisasi = $detil->Realisasi-$totalBelanjaKegiatan;
                        $number+=$number+1;
                    @endphp
                    <tr valign="middle" class="border-top-primary @if($selisih_pagu>0 OR $selisih_realisasi>0) bg-danger @else text-white bg-success @endif">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start text-white">JUMLAH</th>
                        <th class="text-end"></th>
                        <th class="text-end"></th>
                        <th class="text-end text-white">{{RP($totalPaguKegiatan)}}</th>
                        <th class="text-end"></th>
                        <th class="text-end text-white">{{RP($totalBelanjaKegiatan)}}</th>
                        <th class="text-center text-white">{{Persen(divnum($totalBelanjaKegiatan,$totalPaguKegiatan)*100)}}%</th>
                        <th class="text-center">@if($selisih_pagu>0 OR $selisih_realisasi>0) <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  @else
                            <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                            @endif</th>
                    </tr>
                    @endforeach
                    @endforeach

                    @php
                        $TotalPaguAkhir      += $PaguAkhir;
                        $TotalRealisasi      += $Realisasi;

                    $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    // $PrognosaSub  += $Prognosa;
                    @endphp
                    @endforeach

                    @php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    // $PrognosaSub  = 0;


                    @endphp
                    @endforeach

                    @php
                        $selisih_pagu_akhir      = $TotalPaguAkhir-$TotalPaguAkhirCovid;
                        $selisih_realisasi_akhir = $TotalRealisasi-$TotalRealisasiCovid;
                    @endphp
                     <tfoot>
                        <tr valign="middle" class="table-warning">
                            <th class="text-center"></th>
                            <th class="text-start"></th>
                            <th class="text-start">SELISIH DATA SPAN DAN UPT</th>
                            <th class="text-end"></th>
                            <th class="text-end"></th>
                            <th class="text-end">{{RP($selisih_pagu_akhir)}}</th>
                            <th class="text-end"></th>
                            <th class="text-end">{{RP($selisih_realisasi_akhir)}}</th>
                            <th class="text-center text-white"></th>
                            <th class="text-center">
                            </th>
                        </tr>
                    </tfoot>
                    <tfoot class="@if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0) bg-danger @else text-white bg-success @endif">
                    <tr valign="middle">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start text-white">JUMLAH RAYA</th>
                        <th class="text-end"></th>
                        <th class="text-end"></th>
                        <th class="text-end text-white">{{RP($TotalPaguAkhirCovid)}}</th>
                        <th class="text-end"></th>
                        <th class="text-end text-white">{{RP($TotalRealisasiCovid)}}</th>
                        <th class="text-center text-white">{{Persen(divnum($TotalRealisasiCovid,$TotalPaguAkhirCovid)*100)}}%</th>
                        <th class="text-center">
                            @if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0) <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  @else
                            <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                            @endif

                        </th>
                    </tr>

                    </tfoot>

            </table>
        @break
        @case('padatkarya')
        <h3>REKAPITULASI REALISASI DAYA SERAP<br>
            KEGIATAN PADAT KARYA<br>
            {{Auth::user()->satker->NamaSatuanKerja}}<br>
            TAHUN ANGGARAN {{$year}}
            </h3>
            <br>

        @foreach ($data as $item)
        <table class="table table-sm" id="card">
            <thead class="bg-primary text-white">
                <tr>
                    <th colspan="6" class="text-start"><h3>KEGIATAN PADAT KARYA #{{$loop->iteration}}</h3>
                        <h4>
                        DESA {{strtoupper($item->Desa)}},<br>
                        KECAMATAN {{strtoupper($item->Kecamatan)}},<br>
                        {{$item->Kabupaten}},<br>
                        PROPINSI {{$item->Propinsi}}
                        </h4>
                    </th>
            </tr>
            </thead>
            <tr class="table-warning">
                <th colspan="6" class="text-start"><h3>RINCIAN AKUN KEGIATAN </h3></th>
            </tr>
            <tr class="table-danger">

                            <th class="text-center">#</th>
                            <th class="text-center">AKUN</th>
                            <th class="text-center">KETERANGAN AKUN</th>
                            <th class="text-center">PAGU</th>
                            <th class="text-center">REALISASI</th>
                            <th class="text-center">SISA</th>

            </tr>
            @php
            $total_pagu = 0;
            $total_dsa  = 0;
            $total_sisa = 0;
            @endphp

            @foreach ($item->akun as $kegiatan)

            @php
            $pagu      = $kegiatan->Amount ?? '0';
            $realisasi = $kegiatan->realisasi->TotalPagu ?? '0';
            $sisa      = $pagu - $realisasi;
            $total_pagu += $pagu;
            $total_dsa  += $realisasi;
            $total_sisa += $sisa;

            @endphp

            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$kegiatan->Akun}}</td>
                    <td class="text-start">{{$kegiatan->akun->NamaAkun}}<br><i>{{$kegiatan->Uraian}}</i></td>
                    <td class="text-end">{{RP($kegiatan->Amount)}}</td>
                    <td class="text-end">{{RP($kegiatan->realisasi->TotalPagu ?? '0')}}</td>
                    <td class="text-end">{{RP($sisa)}}</td>

            </tr>
            @endforeach
            @if(count($item->akun)>1)

            <tr class="table-danger">

                <th></th>
                <th class="text-start"></th>
                <th class="text-start">TOTAL</th>
                <th class="text-end">{{RP($total_pagu)}}</th>
                <th class="text-end">{{RP($total_dsa)}}</th>
                <th class="text-end">{{RP($total_sisa)}}</th>

            </tr>
            @endif
        </table>
        <table class="table table-sm" id="card">
            <tr class="table-warning">
                <th  class="text-center" width="50%"><h3>TARGET KEGIATAN </h3></th>
                <th  class="text-center" width="50%"><h3>REALISASI KEGIATAN </h3></th>
            </tr>
            <tr>
                <td valign="top">
                    <ul class="mt-0 mb-2">
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan : <span class="badge badge-primary">{{$item->Jadwal}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan : <span class="badge badge-primary">{{$item->Mekanisme}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang : <span class="badge badge-info">{{$item->JumlahOrang}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari : <span class="badge badge-info">{{$item->JumlahHari}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari : <span class="badge badge-info">{{$item->JumlahOrangHari}}</span></li>
                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian : <span class="badge badge-dark">{{RP($item->UpahHarian)}}</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah : <span class="badge badge-success">{{RP($item->TotalBiayaUpah)}}</span></li>
                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah : <span class="badge badge-primary">{{Persen($item->PersenBiayaUpah)}} %</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya : <span class="badge badge-success">{{RP($item->TotalBiayaLain)}}</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan : <span class="badge badge-success">{{RP($item->TotalPagu)}}</span></li>

                    </ul>
                </td>
                <td valign="top">
                    @if(!empty($item->realisasi) AND count($item->realisasi)>0)

                    <ul>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan : <span class="badge badge-primary">{{$item->sumrealisasi->Jadwal}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan : <span class="badge badge-primary">{{$item->sumrealisasi->Mekanisme}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang : <span class="badge badge-info">{{$item->sumrealisasi->JumlahOrang}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari : <span class="badge badge-info">{{$item->sumrealisasi->JumlahHari}}</span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari : <span class="badge badge-info">{{$item->sumrealisasi->JumlahOrangHari}}</span></li>
                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian : <span class="badge badge-dark">{{RP($item->sumrealisasi->UpahHarian)}}</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah : <span class="badge badge-success">{{RP($item->sumrealisasi->TotalBiayaUpah)}}</span></li>
                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah : <span class="badge badge-primary">{{Persen($item->sumrealisasi->PersenBiayaUpah)}} %</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya : <span class="badge badge-success">{{RP($item->sumrealisasi->TotalBiayaLain)}}</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan : <span class="badge badge-success">{{RP($item->sumrealisasi->TotalPagu)}}</span></li>

                    </ul>
                    @endif
                </td>
            </tr>
            <tr class="table-warning">
                <th colspan="6" class="text-start"><h3>RINCIAN REALISASI KEGIATAN </h3></th>
            </tr>
                @foreach ($item->realisasi as $itemRealisasi)
                <tr>
                <td colspan="6">
                <ul>
                <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Akun : {{$itemRealisasi->akun->akun->KdAkun ?? ''}} - {{$itemRealisasi->akun->akun->NamaAkun ?? ''}} <span class="badge badge-success">{{RP($itemRealisasi->TotalPaguDipa)}}</span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Realisasi Pelaksanaan : <span class="badge badge-primary">{{$itemRealisasi->Jadwal}}</span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan : <span class="badge badge-primary">{{$itemRealisasi->Mekanisme}}</span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang : <span class="badge badge-info">{{$itemRealisasi->JumlahOrang}}</span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari : <span class="badge badge-info">{{$itemRealisasi->JumlahHari}}</span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari : <span class="badge badge-info">{{$itemRealisasi->JumlahOrangHari}}</span></li>
                <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian : <span class="badge badge-dark">{{RP($itemRealisasi->UpahHarian)}}</span></li>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah : <span class="badge badge-success">{{RP($itemRealisasi->TotalBiayaUpah)}}</span></li>
                <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah : <span class="badge badge-primary">{{Persen($itemRealisasi->PersenBiayaUpah)}} %</span></li>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya : <span class="badge badge-success">{{RP($itemRealisasi->TotalBiayaLain)}}</span></li>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Realisasi Kegiatan : <span class="badge badge-success">{{RP($itemRealisasi->TotalPagu)}}</span></li>
                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Sisa Dana : <span class="badge badge-danger">{{RP($itemRealisasi->TotalPaguDipa-$itemRealisasi->TotalPagu)}}</span></li>
                @foreach ($itemRealisasi->sppd as $sppd)
                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Data SP2D  Tanggal : {{\Carbon\Carbon::parse($sppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')}} Nomor. {{$sppd->nosppd}}</li>
                @endforeach
                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">Catatan : {{($itemRealisasi->Keterangan)}}</li>
                </ul>
                </td>
                </tr>
                @endforeach
        </table>
        <br>
        @endforeach
        @break


    @endswitch


    <footer>
        <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
    </footer>

</body>
</html>
