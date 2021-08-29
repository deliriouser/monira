


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
            font-size: 5px;
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

        TAHUN ANGGARAN <?php echo e($year); ?><br>
        <br>

        <table class="table table-sm" id="card" data-show-columns="true" width="100%">
            <thead class="bg-primary text-white">
                <tr class="bg-primary text-white">
                    <th class="text-center" valign="middle" rowspan="2">NO</th>
                    <th class="text-start" valign="middle" rowspan="2">KODE</th>
                    <th class="text-center" valign="middle" rowspan="2">KETERANGAN</th>
                    <th class="text-center" valign="middle" colspan="3">LOKASI</th>
                    <th class="text-center" valign="middle" rowspan="2">AKUN</th>
                    <th class="text-center" valign="middle" rowspan="2">KEGIATAN</th>
                    <th class="text-center" colspan="9">TARGET</th>
                    <th class="text-center" valign="middle" rowspan="2"></th>
                    <th class="text-center" colspan="14">REALISASI</th>
                </tr>
                <tr>
                    <th class="col-1 text-center">KABUPATEN</th>
                    <th class="col-1 text-center">KECAMATAN</th>
                    <th class="col-1 text-center">DESA / KELURAHAN</th>
                    <th class="col-1 text-center">TOTAL PAGU</th>
                    <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                    <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                    <th class="col-1 text-center">% BIAYA UPAH</th>
                    <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                    <th class="col-1 text-center">JUMLAH HARI</th>
                    <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                    <th class="col-1 text-center">JADWAL KEGIATAN</th>
                    <th class="col-1 text-center">MEKANIME KEGIATAN</th>
                    <th class="col-1 text-center">NO SPPD</th>
                    <th class="col-1 text-center">TGL SPPD</th>
                    <th class="col-1 text-center">TOTAL REALISASI</th>
                    <th class="col-1 text-center">REALISASI KEGIATAN PENDUKUNG</th>
                    <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                    <th class="col-1 text-center">% BIAYA UPAH</th>
                    <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                    <th class="col-1 text-center">JUMLAH HARI</th>
                    <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                    <th class="col-1 text-center">REALISASI KEGIATAN</th>
                    <th class="col-1 text-center">MEKANISME KEGIATAN</th>
                    <th class="col-1 text-center">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $SumPagu_TotalPagu       = 0;
                $SumPagu_TotalBiayaLain  = 0;
                $SumPagu_TotalBiayaUpah  = 0;
                $SumPagu_JumlahOrang     = 0;
                $SumPagu_JumlahHari      = 0;
                $SumPagu_JumlahOrangHari = 0;
                $SumDsa_TotalPagu        = 0;
                $SumDsa_TotalBiayaLain   = 0;
                $SumDsa_TotalBiayaUpah   = 0;
                $SumDsa_JumlahOrang      = 0;
                $SumDsa_JumlahHari       = 0;
                $SumDsa_JumlahOrangHari  = 0;

            ?>

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propinsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-danger">
                    <th><?php echo e($propinsi->KodeWilayah); ?></th>
                    <th colspan="29"><?php echo e($propinsi->WilayahName); ?></th>
                </tr>
                <?php
                    $PropPagu_TotalPagu       = 0;
                    $PropPagu_TotalBiayaLain  = 0;
                    $PropPagu_TotalBiayaUpah  = 0;
                    $PropPagu_JumlahOrang     = 0;
                    $PropPagu_JumlahHari      = 0;
                    $PropPagu_JumlahOrangHari = 0;
                    $PropDsa_TotalPagu        = 0;
                    $PropDsa_TotalBiayaLain   = 0;
                    $PropDsa_TotalBiayaUpah   = 0;
                    $PropDsa_JumlahOrang      = 0;
                    $PropDsa_JumlahHari       = 0;
                    $PropDsa_JumlahOrangHari  = 0;

                ?>
                <?php $__currentLoopData = $propinsi->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php if(!empty($satker->realisasipadatkarya)): ?> table-success <?php else: ?> table-warning  <?php endif; ?>">
                    <th><?php echo e($loop->iteration); ?></th>
                    <th><?php echo e($satker->KodeSatker); ?></th>
                    <th colspan="6"><?php echo e($satker->NamaSatuanKerja); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->TotalPagu ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->TotalBiayaLain ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->TotalBiayaUpah ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(Persen(divnum($satker->pagupadatkarya->TotalBiayaUpah ?? '0',$satker->pagupadatkarya->TotalPagu ?? '0')*100)); ?>%</th>
                    <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->JumlahOrang ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->JumlahHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->JumlahOrangHari ?? '0')); ?></th>
                    <th class="text-end"><span class="nowrap"><?php echo e(($satker->pagupadatkarya->Jadwal ?? '')); ?></span></th>
                    <th class="text-end"><span class="nowrap"><?php echo e(($satker->pagupadatkarya->Mekanisme ?? '')); ?></span></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->TotalPagu ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->TotalBiayaLain ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->TotalBiayaUpah ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(Persen(divnum($satker->realisasipadatkarya->TotalBiayaUpah ?? '0',$satker->realisasipadatkarya->TotalPagu ?? '0')*100)); ?>%</th>
                    <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->JumlahOrang ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->JumlahHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->JumlahOrangHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(($satker->realisasipadatkarya->Jadwal ?? '')); ?></th>
                    <th class="text-end"><span class="nowrap"><?php echo e(($satker->realisasipadatkarya->Mekanisme ?? '')); ?></span></th>
                    <th class="text-end"><span class="nowrap"><?php echo e(($satker->realisasipadatkarya->Keterangan ?? '')); ?></span></th>
                </tr>
                <?php if(count($satker->datapadatkarya)>0): ?>
                <?php $__currentLoopData = $satker->datapadatkarya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span class="nowrap"><?php echo e($item->Kabupaten); ?></span></td>
                    <td><span class="nowrap">KEC. <?php echo e(strtoupper($item->Kecamatan)); ?></span></td>
                    <td><span class="nowrap">DES. <?php echo e(strtoupper($item->Desa)); ?></span></td>
                    <td>
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($akun->Akun); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td>
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="nowrap"><?php echo e($akun->Uraian); ?></span><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(RP($akun->Amount)); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td colspan="9">

                        
                    </td>
                    <td class="text-start">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($items->realisasi_akun->sppd)): ?>
                        <?php $__currentLoopData = $items->realisasi_akun->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemsppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(($itemsppd->nosppd ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-start">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($items->realisasi_akun->sppd)): ?>
                        <?php $__currentLoopData = $items->realisasi_akun->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemsppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(\Carbon\Carbon::parse($itemsppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>

                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(RP($items->realisasi_akun->TotalPagu ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(RP($items->realisasi_akun->TotalBiayaLain ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(RP($items->realisasi_akun->TotalBiayaUpah ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(Persen($items->realisasi_akun->PersenBiayaUpah ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(($items->realisasi_akun->JumlahOrang ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(($items->realisasi_akun->JumlahHari ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(($items->realisasi_akun->JumlahOrangHari ?? '0')); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="nowrap"><?php echo e(($items->realisasi_akun->Jadwal ?? '')); ?></span><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="nowrap"><?php echo e(($items->realisasi_akun->Mekanisme ?? '')); ?></span><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="text-end">
                        <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="nowrap"><?php echo e(($items->realisasi_akun->Keterangan ?? '')); ?></span><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>



                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php
                $PropPagu_TotalPagu       += $satker->pagupadatkarya->TotalPagu ?? '0';
                $PropPagu_TotalBiayaLain  += $satker->pagupadatkarya->TotalBiayaLain ?? '0';
                $PropPagu_TotalBiayaUpah  += $satker->pagupadatkarya->TotalBiayaUpah  ?? '0';
                $PropPagu_JumlahOrang     += $satker->pagupadatkarya->JumlahOrang ?? '0';
                $PropPagu_JumlahHari      += $satker->pagupadatkarya->JumlahHari ?? '0';
                $PropPagu_JumlahOrangHari += $satker->pagupadatkarya->JumlahOrangHari ?? '0';
                $PropDsa_TotalPagu        += $satker->realisasipadatkarya->TotalPagu ?? '0';
                $PropDsa_TotalBiayaLain   += $satker->realisasipadatkarya->TotalBiayaLain ?? '0';
                $PropDsa_TotalBiayaUpah   += $satker->realisasipadatkarya->TotalBiayaUpah ?? '0';
                $PropDsa_JumlahOrang      += $satker->realisasipadatkarya->JumlahOrang ?? '0';
                $PropDsa_JumlahHari       += $satker->realisasipadatkarya->JumlahHari ?? '0';
                $PropDsa_JumlahOrangHari  += $satker->realisasipadatkarya->JumlahOrangHari ?? '0';

                ?>

                <?php endif; ?>



                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th></th>
                    <th></th>
                    <th colspan="6">JUMLAH PROPINSI</th>
                    <th class="text-end"><?php echo e(RP($PropPagu_TotalPagu ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropPagu_TotalBiayaLain ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropPagu_TotalBiayaUpah ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(Persen(divnum($PropPagu_TotalBiayaUpah ?? '0',$PropPagu_TotalPagu ?? '0')*100)); ?>%</th>
                    <th class="text-end"><?php echo e(RP($PropPagu_JumlahOrang ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropPagu_JumlahHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropPagu_JumlahOrangHari ?? '0')); ?></th>
                    <th class="text-end"></th>
                    <th class="text-end"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-end"><?php echo e(RP($PropDsa_TotalPagu ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropDsa_TotalBiayaLain ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropDsa_TotalBiayaUpah ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(Persen(divnum($PropDsa_TotalBiayaUpah ?? '0',$PropDsa_TotalPagu ?? '0')*100)); ?>%</th>
                    <th class="text-end"><?php echo e(RP($PropDsa_JumlahOrang ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropDsa_JumlahHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($PropDsa_JumlahOrangHari ?? '0')); ?></th>
                    <th class="text-end"></th>
                    <th class="text-end"></th>
                    <th></th>

                </tr>
                <?php
                $SumPagu_TotalPagu       += $PropPagu_TotalPagu;
                $SumPagu_TotalBiayaLain  += $PropPagu_TotalBiayaLain;
                $SumPagu_TotalBiayaUpah  += $PropPagu_TotalBiayaUpah ;
                $SumPagu_JumlahOrang     += $PropPagu_JumlahOrang;
                $SumPagu_JumlahHari      += $PropPagu_JumlahHari;
                $SumPagu_JumlahOrangHari += $PropPagu_JumlahOrangHari;
                $SumDsa_TotalPagu        += $PropDsa_TotalPagu;
                $SumDsa_TotalBiayaLain   += $PropDsa_TotalBiayaLain;
                $SumDsa_TotalBiayaUpah   += $PropDsa_TotalBiayaUpah;
                $SumDsa_JumlahOrang      += $PropDsa_JumlahOrang;
                $SumDsa_JumlahHari       += $PropDsa_JumlahHari;
                $SumDsa_JumlahOrangHari  += $PropDsa_JumlahOrangHari;

                ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr class="table-primary">
                    <th></th>
                    <th></th>
                    <th colspan="6">TOTAL</th>
                    <th class="text-end"><?php echo e(RP($SumPagu_TotalPagu ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPagu_TotalBiayaLain ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPagu_TotalBiayaUpah ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(Persen(divnum($SumPagu_TotalBiayaUpah ?? '0',$SumPagu_TotalPagu ?? '0')*100)); ?>%</th>
                    <th class="text-end"><?php echo e(RP($SumPagu_JumlahOrang ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPagu_JumlahHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPagu_JumlahOrangHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(($SumPagu_Jadwal ?? '')); ?></th>
                    <th class="text-end"><?php echo e(($SumPagu_Mekanisme ?? '')); ?></th>
                    <th></th>
                    <th></th>
                    <th></th>

                    <th class="text-end"><?php echo e(RP($SumDsa_TotalPagu ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumDsa_TotalBiayaLain ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumDsa_TotalBiayaUpah ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(Persen(divnum($SumDsa_TotalBiayaUpah ?? '0',$SumDsa_TotalPagu ?? '0')*100)); ?>%</th>
                    <th class="text-end"><?php echo e(RP($SumDsa_JumlahOrang ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumDsa_JumlahHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(RP($SumDsa_JumlahOrangHari ?? '0')); ?></th>
                    <th class="text-end"><?php echo e(($SumDsa_Jadwal ?? '')); ?></th>
                    <th class="text-end"><?php echo e(($SumDsa_Mekanisme ?? '')); ?></th>
                    <th></th>

                </tr>

            </tfoot>
        </table>
        <footer>
            <img width="" src="<?php echo e(public_path('assets/images/logo/logo-icon.png')); ?>" alt="Monira"><br>
            MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
            Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e(date('Y')); ?>

        </footer>


</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-padatkarya-rekap-rincian-level.blade.php ENDPATH**/ ?>