

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

    </style>

</head>
  <body onload="startTime()">

    <?php switch($unit):
        case ('eselon1'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
            KEGIATAN PENANGANAN COVID-19<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br>
            PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?> </h3>
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
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($item['Kode']); ?></td>
                        <td class="text-start"><?php echo e($item['Keterangan']); ?></td>
                        <td class="text-end"><?php echo e(RP($item['PaguAwal'])); ?></td>
                        <td class="text-end"><?php echo e(RP($item['Pagu'])); ?></td>
                        <td class="text-end"><?php echo e(RP($item['Realisasi'])); ?></td>
                        <td class="text-center"><?php echo e(Persen($item['Persen'])); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
                        <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                        <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                    </tr>
                </tfoot>
            </table>

            <?php break; ?>

        <?php case ('propinsi'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
            KEGIATAN PENANGANAN COVID-19<br>

            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br>
            PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?> </h3>
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
                    <?php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>
                    <?php
                        $noSatker=0;
                        $PaguAwal=0;
                        $PaguAkhir=0;
                        $Realisasi=0;
                    ?>
                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                    ?>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($satker->Kode); ?></td>
                        <td class="text-start"><?php echo e($satker->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(RP($satker->PaguAwal)); ?></td>
                        <td class="text-end"><?php echo e(RP($satker->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(RP($satker->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(RP($PaguAwal)); ?></th>
                        <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                    </tr>

                    <?php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
                        <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                    </tr>
                </tfoot>
            </table>

            <?php break; ?>

            <?php case ('satker'): ?>
            <?php if($segment!='volume'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
            KEGIATAN PENANGANAN COVID-19<br>

            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br>
            PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?> </h3>
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
                    <?php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $PaguAwalSub  = 0;
                        $PaguAkhirSub = 0;
                        $RealisasiSub = 0;

                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="6"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>

                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-warning">
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="text-center"><b><?php echo e($key); ?></b></td>
                            <td class="text-start" colspan="5"><b>
                                <?php if(isset($value->KodeSubHeader)): ?>
                                    <?php echo e($value->NamaSubHeader); ?>

                                <?php endif; ?>
                            </b></td>
                    </tr>
                    <?php
                        $PaguAwal     = 0;
                        $PaguAkhir    = 0;
                        $Realisasi    = 0;
                    ?>

                    <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                    ?>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($detil->Kode); ?></td>
                        <td class="text-start"><?php echo e($detil->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(RP($detil->PaguAwal)); ?></td>
                        <td class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(RP($detil->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</td>
                    </tr>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    ?>

                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(RP($PaguAwal)); ?></th>
                        <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                    </tr>

                    <?php
                    $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH PROPINSI</th>
                        <th class="text-end"><?php echo e(RP($PaguAwalSub)); ?></th>
                        <th class="text-end"><?php echo e(RP($PaguAkhirSub)); ?></th>
                        <th class="text-end"><?php echo e(RP($RealisasiSub)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)); ?>%</th>
                    </tr>

                    <?php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
                        <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                    </tr>
                    </tfoot>

            </table>
            <?php else: ?>

            <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS BARANG<br>
                KEGIATAN PENANGANAN COVID-19<br>

                <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
                TAHUN ANGGARAN <?php echo e($year); ?><br>
                PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?> </h3>
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
                        <?php
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

                        ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-danger">
                            <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                            <td class="text-start" colspan="11"><b><?php echo e($item->NamaHeader); ?></b></td>
                        </tr>

                        <?php
                            $PaguAkhirSub_kegiatan = 0;
                            $RealisasiSub_kegiatan = 0;
                            $SisaSub_kegiatan      = 0;

                        ?>
                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-warning">
                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                <td class="text-center"><b><?php echo e($key); ?></b></td>
                                <td class="text-start" colspan="10"><b>
                                    <?php if(isset($value->KodeSubHeader)): ?>
                                        <?php echo e($value->NamaSubHeader); ?>

                                    <?php endif; ?>
                                </b></td>
                        </tr>
                        <?php
                            $PaguAkhir = 0;
                            $Realisasi = 0;
                            $Sisa      = 0;
                            $PaguKegiatan_satker     = 0;
                            $BelanjaKegiatan_satker  = 0;
                            $SisaKegiatan_satker     = 0;

                        ?>

                            <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $output => $valoutput): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $valoutput->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan => $valkegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $valkegiatan->SubDataDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dana => $valdana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $valdana->SubDataAkun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun => $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                            $PaguAkhir              += $detil->PaguAkhir ?? '0';
                            $Realisasi              += $detil->Realisasi ?? '0';
                            $Sisa                   += $detil->Sisa ?? '0';
                        ?>

                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center"><?php echo e($valkegiatan->KodeKegiatan); ?>.<?php echo e($valoutput->KodeOutput); ?>.<?php echo e($detil->Kode); ?></th>
                            <th class="text-start"><?php echo e($detil->Keterangan); ?></th>
                            <th class="text-center"><?php echo e($detil->SumberDana); ?></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></th>
                            <th></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($detil->Realisasi)); ?></th>
                            <th></th>
                            <th class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</th>
                            <th class="text-end"><?php echo e(RP($detil->Sisa)); ?></th>
                        </tr>
                        <?php
                            $PaguKegiatan    = 0;
                            $BelanjaKegiatan = 0;
                            $SisaKegiatan    = 0;

                        ?>
                        <?php $__currentLoopData = $detil->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($kegiatan->Uraian!='0'): ?>
                        <?php
                        $PaguKegiatan          += $kegiatan->PaguKegiatan;
                        $BelanjaKegiatan       += $kegiatan->BelanjaKegiatan;
                        $SisaKegiatan          += $kegiatan->SisaKegiatan;

                        $PaguAkhirSub_kegiatan += $kegiatan->PaguKegiatan;
                        $RealisasiSub_kegiatan += $kegiatan->BelanjaKegiatan;
                        $SisaSub_kegiatan      += $kegiatan->SisaKegiatan;


                        ?>

                        <tr>
                            <td class="text-center"></td>
                            <td class="text-end"></td>
                            <td class="text-start">
                                <?php echo e($kegiatan->Uraian); ?>

                                    <?php if(!empty($kegiatan->Catatan)): ?><br>
                                    <small><i><?php echo e($kegiatan->Catatan); ?></i>
                                    </small>
                                    <?php endif; ?></td>
                            <td class="text-center"><?php echo e($detil->SumberDana); ?></td>
                            <td class="text-end"><span class="nowrap"><?php echo e(RP($kegiatan->VolumePagu)); ?> <?php echo e($kegiatan->SatuanPagu); ?></span></td>
                            <td class="text-end"><?php echo e(RP($kegiatan->PaguKegiatan)); ?></td>
                            <td class="text-center"></td>

                            <td class="text-end"><span class="nowrap"><?php if(!empty($kegiatan->VolumeBelanja)): ?><?php echo e(RP($kegiatan->VolumeBelanja)); ?> <?php echo e($kegiatan->SatuanBelanja); ?> <?php endif; ?></span></td>
                            <td class="text-end"><?php echo e(RP($kegiatan->BelanjaKegiatan)); ?></td>
                            <td class="text-start"><small class="nowrap"><?php echo nl2br($kegiatan->Tglsp2d); ?></small></td>

                            <td class="text-center"><?php echo e(Persen($kegiatan->PersenKegiatan)); ?>%</td>
                            <td class="text-end"><?php echo e(RP($kegiatan->SisaKegiatan)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $selisih_pagu      = $PaguAkhir-$PaguKegiatan;
                            $selisih_realisasi = $Realisasi-$BelanjaKegiatan;
                            $total_selisih     = $selisih_pagu+$selisih_realisasi;


                        ?>
                        <tr class="border-top-primary">
                            <th class="text-center"></th>
                            <th></th>
                            <th class="text-start">SUB JUMLAH</th>
                            <th class="text-center text-danger"><?php if($total_selisih!=0): ?> <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Terdapat Perbedaan Data UPT dan SPAN" class="icofont icofont-warning-alt"></i> <?php endif; ?></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($PaguKegiatan)); ?></th>
                            <th></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($BelanjaKegiatan)); ?></th>
                            <th></th>
                            <th class="text-center"><?php echo e(Persen(divnum($BelanjaKegiatan,$PaguKegiatan)*100)); ?>%</th>
                            <th class="text-end"><?php echo e(RP($SisaKegiatan)); ?></th>
                        </tr>

                        <?php
                            $PaguKegiatan_satker    += $PaguKegiatan;
                            $BelanjaKegiatan_satker += $BelanjaKegiatan;
                            $SisaKegiatan_satker    += $SisaKegiatan;
                        ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr class="border-top-primary table-info">
                            <th class="text-center"></th>
                            <th></th>
                            <th class="text-start">JUMLAH SATKER</th>
                            <th class="text-center text-danger"></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($PaguKegiatan_satker)); ?></th>
                            <th></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($BelanjaKegiatan_satker)); ?></th>
                            <th></th>
                            <th class="text-center"><?php echo e(Persen(divnum($BelanjaKegiatan_satker,$PaguKegiatan_satker)*100)); ?>%</th>
                            <th class="text-end"><?php echo e(RP($SisaKegiatan_satker)); ?></th>
                        </tr>

                        <?php
                            $TotalPaguAkhir          += $PaguAkhir;
                            $TotalRealisasi          += $Realisasi;
                            $TotalSisa               += $Sisa;
                        ?>

                        

                        <?php
                        $PaguAkhirSub += $PaguAkhir;
                        $RealisasiSub += $Realisasi;
                        $SisaSub      += $Sisa;
                        ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr class="table-danger">
                            <th class="text-center"></th>
                            <th class="text-start" colspan="3">JUMLAH PROPINSI <?php echo e($item->NamaHeader); ?></th>
                            <th class="text-start"></th>
                            <th class="text-end"><?php echo e(RP($PaguAkhirSub_kegiatan)); ?></th>
                            <th class="text-start"></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($RealisasiSub_kegiatan)); ?></th>
                            <th></th>
                            <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub_kegiatan,$PaguAkhirSub_kegiatan)*100)); ?>%</th>
                            <th class="text-end"><?php echo e(RP($SisaSub_kegiatan)); ?></th>
                        </tr>

                        <?php
                        $PaguAkhirSub             = 0;
                        $RealisasiSub             = 0;
                        $SisaSub                  = 0;
                        $TotalPaguAkhir_kegiatan += $PaguAkhirSub_kegiatan;
                        $TotalRealisasi_kegiatan += $RealisasiSub_kegiatan;
                        $TotalSisa_kegiatan      += $SisaSub_kegiatan;

                        ?>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-primary">
                            <th class="text-center"></th>
                            <th class="text-start" colspan="3">JUMLAH RAYA UPT</th>
                            <th class="text-start"></th>
                            <th class="text-end"><?php echo e(RP($TotalPaguAkhir_kegiatan)); ?></th>
                            <th class="text-start"></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($TotalRealisasi_kegiatan)); ?></th>
                            <th></th>
                            <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi_kegiatan,$TotalPaguAkhir_kegiatan)*100)); ?>%</th>
                            <th class="text-end"><?php echo e(RP($TotalSisa_kegiatan)); ?></th>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-center"></th>
                            <th class="text-start" colspan="3">JUMLAH RAYA SPAN</th>
                            <th class="text-start"></th>
                            <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                            <th class="text-start"></th>
                            <th></th>
                            <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                            <th></th>
                            <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                            <th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
                        </tr>

                </table>

            <?php endif; ?>
            <?php break; ?>
        <?php default: ?>

    <?php endswitch; ?>
    <footer>
        <img width="" src="<?php echo e(public_path('assets/images/logo/logo-icon.png')); ?>" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e(date('Y')); ?>

    </footer>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-covid.blade.php ENDPATH**/ ?>