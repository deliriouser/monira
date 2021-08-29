

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.ico')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/favicon.ico')); ?>" type="image/x-icon">
    <title>MONIRA : MONITORING INFORMASI DAN REALISASI ANGGARAN</title>
    <style>

@page  {
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

    <?php switch($segment):
        case ('covid'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP<br>
            KEGIATAN PENANGANAN COVID-19<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br>
            PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?> </h3>
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
                    <?php
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

                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="9"><b><?php echo e(strtoupper($item->NamaHeader)); ?></b></td>
                    </tr>
                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-warning">
                            <td class="text-center"<?php echo e($loop->iteration); ?></td>
                            <td class="text-center"><b><?php echo e($key); ?></b></td>
                            <td class="text-start" colspan="8"><b>
                                <?php if(isset($value->KodeSubHeader)): ?>
                                    <?php echo e(strtoupper($value->NamaSubHeader)); ?>

                                <?php endif; ?>
                            </b></td>
                    </tr>
                    <?php
                        $PaguAwal     = 0;
                        $PaguAkhir    = 0;
                        $Realisasi    = 0;
                        // $Prognosa    = 0;
                    ?>
                    <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detilsub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $detilsub->SubDataDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                        // $Prognosa  += $detil->Prognosa ?? '0';
                    ?>
                    <tr valign="middle" class="table-success">
                        <th class="text-center"><?php echo e($loop->iteration); ?></th>
                        <th class="text-center"><?php echo e($detil->Kode); ?></th>
                        <th class="text-start"><?php echo e(($detil->Keterangan)); ?></th>
                        <th class="text-center"><?php echo e(($detil->NamaDana)); ?></th>
                        <th></th>
                        <th class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></th>
                        <th></th>
                        <th class="text-end"><?php echo e(RP($detil->Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</th>
                        <th class="text-center">
                            <a data-intro="Tambah Uraian Kegiatan" title="Tambah Uraian Kegiatan" dana="<?php echo e($detil->NamaDana); ?>" akun="<?php echo e($detil->Kode); ?>. <?php echo e($detil->Keterangan); ?>" output="<?php echo e($key); ?>. <?php echo e($value->NamaSubHeader); ?>" kegiatan="<?php echo e($item->KodeHeader); ?>. <?php echo e($item->NamaHeader); ?>" pagu="<?php echo e($detil->PaguAkhir); ?>"  id="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" href="#" class="open-modal-monitoring text-primary static" action="insertKegiatanCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                        </th>
                    </tr>
                    <?php
                        $totalPaguKegiatan    = 0;
                        $totalBelanjaKegiatan = 0;
                        $number               = 0;
                    ?>
                    <?php $__currentLoopData = $detil->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uraian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($uraian->PaguKegiatan)): ?>
                    <?php
                        $sisa = $uraian->PaguKegiatan-$uraian->BelanjaKegiatan;
                    ?>
                    <tr valign="middle">
                        <td></td>
                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                        <td class="text-start"><?php echo e($uraian->Uraian); ?> <?php if(!empty($uraian->Catatan)): ?><br><small><i><?php echo e($uraian->Catatan); ?></i></small><?php endif; ?></td>
                        <td></td>
                        <td class="text-center"><span class="nowrap"><?php echo e($uraian->VolumePagu); ?> <?php echo e($uraian->SatuanPagu); ?></span></td>
                        <td class="text-end"><?php echo e(RP($uraian->PaguKegiatan)); ?></td>
                        <td class="text-center"><span class="nowrap"><?php echo e($uraian->VolumeBelanja); ?> <?php echo e($uraian->SatuanBelanja); ?></span></td>
                        <td class="text-end"><?php echo e(RP($uraian->BelanjaKegiatan)); ?></td>
                        <td class="text-center"><?php echo e(Persen(divnum($uraian->BelanjaKegiatan,$uraian->PaguKegiatan)*100)); ?>%</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a data-intro="Edit Data Uraian Kegiatan" title="Edit Data" dana="<?php echo e($uraian->VolumePagu); ?> <?php echo e($uraian->SatuanPagu); ?>" id="<?php echo e($uraian->Guid); ?>" output="<?php echo e($uraian->SatuanPagu); ?>" kegiatan="<?php echo e($uraian->Uraian); ?>" pagu="<?php echo e($uraian->PaguKegiatan); ?>"  akun="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" sisa="<?php echo e($sisa); ?>" href="#" class="mr-xl-5 open-modal-monitoring text-primary static" action="updateKegiatanCovid" style="font-size:20px;"><i class="icofont icofont-earth"></i></a>
                                <a data-intro="Tambah Realisasi Kegiatan" title="Input Realisasi" dana="<?php echo e($uraian->VolumePagu); ?> <?php echo e($uraian->SatuanPagu); ?>" akun="<?php echo e($uraian->Guid); ?>" output="<?php echo e($uraian->SatuanPagu); ?>" kegiatan="<?php echo e($uraian->Uraian); ?>" pagu="<?php echo e($uraian->PaguKegiatan); ?>"  id="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" sisa="<?php echo e($sisa); ?>" href="#" class="open-modal-monitoring text-success static" action="insertRealisasiCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                <a data-intro="Lihat Rincian Realisasi" title="Lihat Rincian Realisasi" href="#" id="<?php echo e($uraian->Guid); ?>"class="mr-xl-5 open-modal-monitoring text-primary static" action="dataRealisasiCovid" style="font-size:20px;"><i class="fa fa-info-circle"></i></a>

                                <a data-intro="Hapus Kegiatan" title="Hapus Kegiatan" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/monitoring/status',['status'=>'0', 'id'=> $uraian->Guid,'what'=>'kegiatan'])); ?>" class="text-danger static" style="font-size:20px;"><i class="fa fa-times-circle"></i></a>
                              </div>
                        </td>
                    </tr>
                    <?php
                        $totalPaguKegiatan += $uraian->PaguKegiatan;
                        $totalBelanjaKegiatan  += $uraian->BelanjaKegiatan;
                    ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $TotalPaguAwal       += $PaguAwal;
                        $TotalPaguAkhirCovid += $totalPaguKegiatan;
                        $TotalRealisasiCovid += $totalBelanjaKegiatan;
                        // $TotalPrognosa  += $Prognosa;
                        $selisih_pagu = $detil->PaguAkhir-$totalPaguKegiatan;
                        $selisih_realisasi = $detil->Realisasi-$totalBelanjaKegiatan;
                        $number+=$number+1;
                    ?>
                    <tr valign="middle" class="border-top-primary <?php if($selisih_pagu>0 OR $selisih_realisasi>0): ?> bg-danger <?php else: ?> text-white bg-success <?php endif; ?>">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start text-white">JUMLAH</th>
                        <th class="text-end"></th>
                        <th class="text-end"></th>
                        <th class="text-end text-white"><?php echo e(RP($totalPaguKegiatan)); ?></th>
                        <th class="text-end"></th>
                        <th class="text-end text-white"><?php echo e(RP($totalBelanjaKegiatan)); ?></th>
                        <th class="text-center text-white"><?php echo e(Persen(divnum($totalBelanjaKegiatan,$totalPaguKegiatan)*100)); ?>%</th>
                        <th class="text-center"><?php if($selisih_pagu>0 OR $selisih_realisasi>0): ?> <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  <?php else: ?>
                            <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                            <?php endif; ?></th>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $TotalPaguAkhir      += $PaguAkhir;
                        $TotalRealisasi      += $Realisasi;

                    $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    // $PrognosaSub  += $Prognosa;
                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    // $PrognosaSub  = 0;


                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $selisih_pagu_akhir      = $TotalPaguAkhir-$TotalPaguAkhirCovid;
                        $selisih_realisasi_akhir = $TotalRealisasi-$TotalRealisasiCovid;
                    ?>
                     <tfoot>
                        <tr valign="middle" class="table-warning">
                            <th class="text-center"></th>
                            <th class="text-start"></th>
                            <th class="text-start">SELISIH DATA SPAN DAN UPT</th>
                            <th class="text-end"></th>
                            <th class="text-end"></th>
                            <th class="text-end"><?php echo e(RP($selisih_pagu_akhir)); ?></th>
                            <th class="text-end"></th>
                            <th class="text-end"><?php echo e(RP($selisih_realisasi_akhir)); ?></th>
                            <th class="text-center text-white"></th>
                            <th class="text-center">
                            </th>
                        </tr>
                    </tfoot>
                    <tfoot class="<?php if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0): ?> bg-danger <?php else: ?> text-white bg-success <?php endif; ?>">
                    <tr valign="middle">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start text-white">JUMLAH RAYA</th>
                        <th class="text-end"></th>
                        <th class="text-end"></th>
                        <th class="text-end text-white"><?php echo e(RP($TotalPaguAkhirCovid)); ?></th>
                        <th class="text-end"></th>
                        <th class="text-end text-white"><?php echo e(RP($TotalRealisasiCovid)); ?></th>
                        <th class="text-center text-white"><?php echo e(Persen(divnum($TotalRealisasiCovid,$TotalPaguAkhirCovid)*100)); ?>%</th>
                        <th class="text-center">
                            <?php if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0): ?> <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  <?php else: ?>
                            <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                            <?php endif; ?>

                        </th>
                    </tr>

                    </tfoot>

            </table>
        <?php break; ?>
        <?php case ('padatkarya'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP<br>
            KEGIATAN PADAT KARYA<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?>

            </h3>
            <br>

        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <table class="table table-sm" id="card">
            <thead class="bg-primary text-white">
                <tr>
                    <th colspan="6" class="text-start"><h3>KEGIATAN PADAT KARYA #<?php echo e($loop->iteration); ?></h3>
                        <h4>
                        DESA <?php echo e(strtoupper($item->Desa)); ?>,<br>
                        KECAMATAN <?php echo e(strtoupper($item->Kecamatan)); ?>,<br>
                        <?php echo e($item->Kabupaten); ?>,<br>
                        PROPINSI <?php echo e($item->Propinsi); ?>

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
            <?php
            $total_pagu = 0;
            $total_dsa  = 0;
            $total_sisa = 0;
            ?>

            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
            $pagu      = $kegiatan->Amount ?? '0';
            $realisasi = $kegiatan->realisasi->TotalPagu ?? '0';
            $sisa      = $pagu - $realisasi;
            $total_pagu += $pagu;
            $total_dsa  += $realisasi;
            $total_sisa += $sisa;

            ?>

            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td class="text-center"><?php echo e($kegiatan->Akun); ?></td>
                    <td class="text-start"><?php echo e($kegiatan->akun->NamaAkun); ?><br><i><?php echo e($kegiatan->Uraian); ?></i></td>
                    <td class="text-end"><?php echo e(RP($kegiatan->Amount)); ?></td>
                    <td class="text-end"><?php echo e(RP($kegiatan->realisasi->TotalPagu ?? '0')); ?></td>
                    <td class="text-end"><?php echo e(RP($sisa)); ?></td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if(count($item->akun)>1): ?>

            <tr class="table-danger">

                <th></th>
                <th class="text-start"></th>
                <th class="text-start">TOTAL</th>
                <th class="text-end"><?php echo e(RP($total_pagu)); ?></th>
                <th class="text-end"><?php echo e(RP($total_dsa)); ?></th>
                <th class="text-end"><?php echo e(RP($total_sisa)); ?></th>

            </tr>
            <?php endif; ?>
        </table>
        <table class="table table-sm" id="card">
            <tr class="table-warning">
                <th  class="text-center" width="50%"><h3>TARGET KEGIATAN </h3></th>
                <th  class="text-center" width="50%"><h3>REALISASI KEGIATAN </h3></th>
            </tr>
            <tr>
                <td valign="top">
                    <ul class="mt-0 mb-2">
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan : <span class="badge badge-primary"><?php echo e($item->Jadwal); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan : <span class="badge badge-primary"><?php echo e($item->Mekanisme); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang : <span class="badge badge-info"><?php echo e($item->JumlahOrang); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari : <span class="badge badge-info"><?php echo e($item->JumlahHari); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari : <span class="badge badge-info"><?php echo e($item->JumlahOrangHari); ?></span></li>
                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian : <span class="badge badge-dark"><?php echo e(RP($item->UpahHarian)); ?></span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah : <span class="badge badge-success"><?php echo e(RP($item->TotalBiayaUpah)); ?></span></li>
                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah : <span class="badge badge-primary"><?php echo e(Persen($item->PersenBiayaUpah)); ?> %</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya : <span class="badge badge-success"><?php echo e(RP($item->TotalBiayaLain)); ?></span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan : <span class="badge badge-success"><?php echo e(RP($item->TotalPagu)); ?></span></li>

                    </ul>
                </td>
                <td valign="top">
                    <?php if(!empty($item->realisasi) AND count($item->realisasi)>0): ?>

                    <ul>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan : <span class="badge badge-primary"><?php echo e($item->sumrealisasi->Jadwal); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan : <span class="badge badge-primary"><?php echo e($item->sumrealisasi->Mekanisme); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang : <span class="badge badge-info"><?php echo e($item->sumrealisasi->JumlahOrang); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari : <span class="badge badge-info"><?php echo e($item->sumrealisasi->JumlahHari); ?></span></li>
                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari : <span class="badge badge-info"><?php echo e($item->sumrealisasi->JumlahOrangHari); ?></span></li>
                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian : <span class="badge badge-dark"><?php echo e(RP($item->sumrealisasi->UpahHarian)); ?></span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah : <span class="badge badge-success"><?php echo e(RP($item->sumrealisasi->TotalBiayaUpah)); ?></span></li>
                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah : <span class="badge badge-primary"><?php echo e(Persen($item->sumrealisasi->PersenBiayaUpah)); ?> %</span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya : <span class="badge badge-success"><?php echo e(RP($item->sumrealisasi->TotalBiayaLain)); ?></span></li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan : <span class="badge badge-success"><?php echo e(RP($item->sumrealisasi->TotalPagu)); ?></span></li>

                    </ul>
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="table-warning">
                <th colspan="6" class="text-start"><h3>RINCIAN REALISASI KEGIATAN </h3></th>
            </tr>
                <?php $__currentLoopData = $item->realisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemRealisasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                <td colspan="6">
                <ul>
                <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Akun : <?php echo e($itemRealisasi->akun->akun->KdAkun ?? ''); ?> - <?php echo e($itemRealisasi->akun->akun->NamaAkun ?? ''); ?> <span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalPaguDipa)); ?></span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Realisasi Pelaksanaan<span class="badge badge-primary"><?php echo e($itemRealisasi->Jadwal); ?></span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary"><?php echo e($itemRealisasi->Mekanisme); ?></span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info"><?php echo e($itemRealisasi->JumlahOrang); ?></span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info"><?php echo e($itemRealisasi->JumlahHari); ?></span></li>
                <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info"><?php echo e($itemRealisasi->JumlahOrangHari); ?></span></li>
                <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark"><?php echo e(RP($itemRealisasi->UpahHarian)); ?></span></li>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalBiayaUpah)); ?></span></li>
                <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary"><?php echo e(Persen($itemRealisasi->PersenBiayaUpah)); ?> %</span></li>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalBiayaLain)); ?></span></li>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Realisasi Kegiatan<span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalPagu)); ?></span></li>
                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Sisa Dana <span class="badge badge-danger"><?php echo e(RP($itemRealisasi->TotalPaguDipa-$itemRealisasi->TotalPagu)); ?></span></li>
                <?php $__currentLoopData = $itemRealisasi->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Data SP2D  Tanggal : <?php echo e(\Carbon\Carbon::parse($sppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')); ?> Nomor. <?php echo e($sppd->nosppd); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">Catatan : <?php echo e(($itemRealisasi->Keterangan)); ?></li>
                </ul>
                </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
        <br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php break; ?>


    <?php endswitch; ?>


    <footer>
        <img width="" src="<?php echo e(public_path('assets/images/logo/logo-icon.png')); ?>" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e(date('Y')); ?>

    </footer>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-monitoring.blade.php ENDPATH**/ ?>