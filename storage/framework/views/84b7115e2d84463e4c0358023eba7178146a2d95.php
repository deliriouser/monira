


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
    <h3>REKAPITULASI<br>
        KEGIATAN PADAT KARYA<br>
        DIREKTORAT JENDERAL PERHUBUNGAN LAUT<br>

        TAHUN ANGGARAN <?php echo e($year); ?><br>
        <br>

        <table class="table table-sm sticky-header" id="card" data-show-columns="true">
            <thead class="bg-primary text-white">
                <tr class="bg-primary">
                    <th class="text-center" valign="middle" rowspan="2">NO</th>
                    <th class="text-start" valign="middle" rowspan="2">KODE</th>
                    <th class="text-center" valign="middle" rowspan="2">KETERANGAN</th>
                    <th class="text-center" colspan="5">TARGET</th>
                    <th class="text-center" valign="middle" rowspan="2"></th>
                    <th class="text-center" colspan="5">REALISASI</th>
                </tr>
                <tr>
                    <th class="col-1 text-center">TOTAL PAGU</th>
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
                <?php
                $SumTotTarget_TotalBiayaLain  = 0;
                $SumTotTarget_TotalBiayaUpah  = 0;
                $SumTotTarget_JumlahOrang     = 0;
                $SumTotTarget_JumlahOrangHari = 0;
                $SumTotTarget_TotalPagu       = 0;
                $SumTotDaser_TotalBiayaLain   = 0;
                $SumTotDaser_TotalBiayaUpah   = 0;
                $SumTotDaser_JumlahOrang      = 0;
                $SumTotDaser_JumlahOrangHari  = 0;
                $SumTotDaser_TotalPagu        = 0;
                ?>

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wilayah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-danger">
                    <th class="text-center"><?php echo e($wilayah->KodeWilayah); ?></th>
                    <th class="text-start" colspan="13">PROP. <?php echo e($wilayah->NamaWilayah); ?></th>
                </tr>

                <?php
                    $SumPropTarget_TotalBiayaLain  = 0;
                    $SumPropTarget_TotalBiayaUpah  = 0;
                    $SumPropTarget_JumlahOrang     = 0;
                    $SumPropTarget_JumlahOrangHari = 0;
                    $SumPropTarget_TotalPagu       = 0;
                    $SumPropDaser_TotalBiayaLain   = 0;
                    $SumPropDaser_TotalBiayaUpah   = 0;
                    $SumPropDaser_JumlahOrang      = 0;
                    $SumPropDaser_JumlahOrangHari  = 0;
                    $SumPropDaser_TotalPagu        = 0;
                ?>

                <?php $__currentLoopData = $wilayah->Satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center">.</td>
                    <td class="text-start"><?php echo e($item->KodeSatker); ?></td>
                    <td class="text-start"><?php echo e($item->NamaSatker); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Target_TotalPagu)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Target_TotalBiayaLain)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Target_TotalBiayaUpah)); ?></td>
                    <td class="text-center"><?php echo e(RP($item->Target_JumlahOrang)); ?></td>
                    <td class="text-center"><?php echo e(RP($item->Target_JumlahOrangHari)); ?></td>
                    <td class="text-end"></td>
                    <td class="text-end"><?php echo e(RP($item->Daser_TotalPagu)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Daser_TotalBiayaLain)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Daser_TotalBiayaUpah)); ?></td>
                    <td class="text-center"><?php echo e(RP($item->Daser_JumlahOrang)); ?></td>
                    <td class="text-center"><?php echo e(RP($item->Daser_JumlahOrangHari)); ?></td>

                </tr>
                <?php
                    $SumKabTarget_TotalBiayaLain  = 0;
                    $SumKabTarget_TotalBiayaUpah  = 0;
                    $SumKabTarget_JumlahOrang     = 0;
                    $SumKabTarget_JumlahOrangHari = 0;
                    $SumKabTarget_TotalPagu       = 0;
                    $SumKabDaser_TotalBiayaLain   = 0;
                    $SumKabDaser_TotalBiayaUpah   = 0;
                    $SumKabDaser_JumlahOrang      = 0;
                    $SumKabDaser_JumlahOrangHari  = 0;
                    $SumKabDaser_TotalPagu        = 0;
                ?>

                <?php
                    $SumKecTarget_TotalBiayaLain  = 0;
                    $SumKecTarget_TotalBiayaUpah  = 0;
                    $SumKecTarget_JumlahOrang     = 0;
                    $SumKecTarget_JumlahOrangHari = 0;
                    $SumKecTarget_TotalPagu       = 0;
                    $SumKecDaser_TotalBiayaLain   = 0;
                    $SumKecDaser_TotalBiayaUpah   = 0;
                    $SumKecDaser_JumlahOrang      = 0;
                    $SumKecDaser_JumlahOrangHari  = 0;
                    $SumKecDaser_TotalPagu        = 0;
                ?>

                <?php
                    $SumKecTarget_TotalPagu       += $item->Target_TotalPagu;
                    $SumKecTarget_TotalBiayaLain  += $item->Target_TotalBiayaLain;
                    $SumKecTarget_TotalBiayaUpah  += $item->Target_TotalBiayaUpah;
                    $SumKecTarget_JumlahOrang     += $item->Target_JumlahOrang;
                    $SumKecTarget_JumlahOrangHari += $item->Target_JumlahOrangHari;
                    $SumKecDaser_TotalPagu        += $item->Daser_TotalPagu;
                    $SumKecDaser_TotalBiayaLain   += $item->Daser_TotalBiayaLain;
                    $SumKecDaser_TotalBiayaUpah   += $item->Daser_TotalBiayaUpah;
                    $SumKecDaser_JumlahOrang      += $item->Daser_JumlahOrang;
                    $SumKecDaser_JumlahOrangHari  += $item->Daser_JumlahOrangHari;
                ?>
                
                
                <?php
                $SumKabTarget_TotalPagu       += $SumKecTarget_TotalPagu;
                $SumKabTarget_TotalBiayaLain  += $SumKecTarget_TotalBiayaLain;
                $SumKabTarget_TotalBiayaUpah  += $SumKecTarget_TotalBiayaUpah;
                $SumKabTarget_JumlahOrang     += $SumKecTarget_JumlahOrang;
                $SumKabTarget_JumlahOrangHari += $SumKecTarget_JumlahOrangHari;
                $SumKabDaser_TotalPagu        += $SumKecDaser_TotalPagu;
                $SumKabDaser_TotalBiayaLain   += $SumKecDaser_TotalBiayaLain;
                $SumKabDaser_TotalBiayaUpah   += $SumKecDaser_TotalBiayaUpah;
                $SumKabDaser_JumlahOrang      += $SumKecDaser_JumlahOrang;
                $SumKabDaser_JumlahOrangHari  += $SumKecDaser_JumlahOrangHari;
                ?>

                
                <?php
                $SumPropTarget_TotalPagu       += $SumKabTarget_TotalPagu;
                $SumPropTarget_TotalBiayaLain  += $SumKabTarget_TotalBiayaLain;
                $SumPropTarget_TotalBiayaUpah  += $SumKabTarget_TotalBiayaUpah;
                $SumPropTarget_JumlahOrang     += $SumKabTarget_JumlahOrang;
                $SumPropTarget_JumlahOrangHari += $SumKabTarget_JumlahOrangHari;
                $SumPropDaser_TotalPagu        += $SumKabDaser_TotalPagu;
                $SumPropDaser_TotalBiayaLain   += $SumKabDaser_TotalBiayaLain;
                $SumPropDaser_TotalBiayaUpah   += $SumKabDaser_TotalBiayaUpah;
                $SumPropDaser_JumlahOrang      += $SumKabDaser_JumlahOrang;
                $SumPropDaser_JumlahOrangHari  += $SumKabDaser_JumlahOrangHari;
                ?>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-danger">
                    <th class="text-center"></th>
                    <th colspan="2" class="text-start">TOTAL <?php echo e($wilayah->NamaWilayah); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPropTarget_TotalPagu)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPropTarget_TotalBiayaLain)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPropTarget_TotalBiayaUpah)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumPropTarget_JumlahOrang)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumPropTarget_JumlahOrangHari)); ?></th>
                    <th class="text-end"></th>
                    <th class="text-end"><?php echo e(RP($SumPropDaser_TotalPagu)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPropDaser_TotalBiayaLain)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumPropDaser_TotalBiayaUpah)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumPropDaser_JumlahOrang)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumPropDaser_JumlahOrangHari)); ?>

                </tr>
                <?php
                $SumTotTarget_TotalPagu       += $SumPropTarget_TotalPagu;
                $SumTotTarget_TotalBiayaLain  += $SumPropTarget_TotalBiayaLain;
                $SumTotTarget_TotalBiayaUpah  += $SumPropTarget_TotalBiayaUpah;
                $SumTotTarget_JumlahOrang     += $SumPropTarget_JumlahOrang;
                $SumTotTarget_JumlahOrangHari += $SumPropTarget_JumlahOrangHari;
                $SumTotDaser_TotalPagu        += $SumPropDaser_TotalPagu;
                $SumTotDaser_TotalBiayaLain   += $SumPropDaser_TotalBiayaLain;
                $SumTotDaser_TotalBiayaUpah   += $SumPropDaser_TotalBiayaUpah;
                $SumTotDaser_JumlahOrang      += $SumPropDaser_JumlahOrang;
                $SumTotDaser_JumlahOrangHari  += $SumPropDaser_JumlahOrangHari;
                ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tfoot>
                <tr class="table-primary">
                    <th class="text-center"></th>
                    <th colspan="2" class="text-start">JUMLAH RAYA</th>
                    <th class="text-end"><?php echo e(RP($SumTotTarget_TotalPagu)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumTotTarget_TotalBiayaLain)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumTotTarget_TotalBiayaUpah)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumTotTarget_JumlahOrang)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumTotTarget_JumlahOrangHari)); ?></th>
                    <th class="text-end"></th>
                    <th class="text-end"><?php echo e(RP($SumTotDaser_TotalPagu)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumTotDaser_TotalBiayaLain)); ?></th>
                    <th class="text-end"><?php echo e(RP($SumTotDaser_TotalBiayaUpah)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumTotDaser_JumlahOrang)); ?></th>
                    <th class="text-center"><?php echo e(RP($SumTotDaser_JumlahOrangHari)); ?>

                </tr>
                </tfoot>
            </tbody>
        </table>
        <footer>
            <img width="" src="<?php echo e(public_path('assets/images/logo/logo-icon.png')); ?>" alt="Monira"><br>
            MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
            Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e(date('Y')); ?>

        </footer>


</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-padatkarya-rekap-three-level.blade.php ENDPATH**/ ?>