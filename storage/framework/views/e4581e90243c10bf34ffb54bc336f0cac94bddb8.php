

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
            padding-bottom:33px;
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

        footer {
            border-top:1px dotted rgb(0, 0, 0);
            padding-top:15px;
                position: fixed;
                bottom: 0.5cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;
                text-align:center;
            }

    </style>

</head>
  <body onload="startTime()">

    <?php switch($unit):
        case ('eselon1'): ?>
        <h3>REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br>
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
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($item['Kode']); ?></td>
                        <td class="text-start"><?php echo e($item['Keterangan']); ?></td>
                        <td class="text-end"><?php echo e(RP($item['Pagu'])); ?></td>
                        <td class="text-end"><?php echo e(RP($item['Realisasi'])); ?></td>
                        <td class="text-center"><?php echo e(Persen($item['Persen'])); ?>%</td>
                        <td class="text-end"><?php echo e(RP($item['Prognosa'])); ?></td>
                        <td class="text-center"><?php echo e(Persen($item['PersenPrognosa'])); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <th></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                        <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                        <th class="text-end"><?php echo e(RP($data->sum('Prognosa'))); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)); ?>%</th>
                    </tr>
                </tfoot>
            </table>


            <?php break; ?>

        <?php case ('propinsi'): ?>
        <h3>REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br>
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
                    <?php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
                        $TotalPrognosa=0;
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
                        $Prognosa=0;
                    ?>
                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                        $Prognosa+=$satker->Prognosa;
                    ?>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($satker->Kode); ?></td>
                        <td class="text-start"><?php echo e($satker->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(RP($satker->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(RP($satker->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
                        <td class="text-end"><?php echo e(RP($satker->Prognosa)); ?></td>
                        <td class="text-center"><?php echo e(Persen($satker->PersenPrognosa)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(RP($Prognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Prognosa,$PaguAkhir)*100)); ?>%</th>
                    </tr>

                    <?php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa += $Prognosa;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(RP($TotalPrognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)); ?>%</th>
                    </tr>
                </tfoot>
            </table>



            <?php break; ?>

            <?php case ('satker'): ?>
            <h3>REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
                <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
                TAHUN ANGGARAN <?php echo e($year); ?><br>
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
                    <?php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $TotalPrognosa  = 0;
                        $PaguAwalSub    = 0;
                        $PaguAkhirSub   = 0;
                        $RealisasiSub   = 0;
                        $PrognosaSub    = 0;

                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>

                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-warning">
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="text-center"><b><?php echo e($key); ?></b></td>
                            <td class="text-start" colspan="6"><b>
                                <?php if(isset($value->KodeSubHeader)): ?>
                                    <?php echo e($value->NamaSubHeader); ?>

                                <?php endif; ?>
                            </b></td>
                    </tr>
                    <?php
                        $PaguAwal  = 0;
                        $PaguAkhir = 0;
                        $Realisasi = 0;
                        $Prognosa  = 0;

                    ?>

                    <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                        $Prognosa  += $detil->Prognosa;

                    ?>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($detil->Kode); ?></td>
                        <td class="text-start"><?php echo e($detil->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(RP($detil->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</td>
                        <td class="text-end"><?php echo e(RP($detil->Prognosa)); ?></td>
                        <td class="text-center"><?php echo e(Persen($detil->PersenPrognosa)); ?>%</td>
                    </tr>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        // $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa  += $Prognosa;

                    ?>

                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(RP($Prognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Prognosa,$PaguAkhir)*100)); ?>%</th>
                    </tr>

                    <?php
                    // $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    $PrognosaSub  += $Prognosa;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH PROPINSI</th>
                        <th class="text-end"><?php echo e(RP($PaguAkhirSub)); ?></th>
                        <th class="text-end"><?php echo e(RP($RealisasiSub)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(RP($PrognosaSub)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)); ?>%</th>
                    </tr>

                    <?php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    $PrognosaSub  = 0;
                    ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-footer text-dark">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(RP($TotalPrognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)); ?>%</th>
                    </tr>

            </table>

            <?php break; ?>
        <?php default: ?>

    <?php endswitch; ?>
    <footer>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e($year); ?>

    </footer>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-prognosa.blade.php ENDPATH**/ ?>