

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
            font-size: 11px;
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
<body>

<div class="container-default">
    <h3>REKAPITULASI RENCANA PENARIKAN DANA MP PNBP<br>
        <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
        TAHUN ANGGARAN <?php echo e($year); ?><br>
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
            <?php
                $total_rpd = 0;
                $total_mp  = 0;
                $total_dsa = 0;
                $bulan_depan = Date('n')+1;
            ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($item->id<=$bulan_depan): ?>
            <?php
            $total_rpd += $item->rpd->jumlah ?? '0';
            $total_mp  += $item->mp->Amount ?? '0';
            $total_dsa += $item->dsa->Amount ?? '0';
            ?>

            <tr>
                <td valign="middle" class="text-center"><?php echo e($loop->iteration); ?></td>
                <td valign="middle" class="text-start"><?php echo e($item->BulanName); ?></td>
                <td valign="middle" class="text-end"><?php echo e(RP($item->rpd->jumlah ?? '0')); ?></td>
                <td valign="middle" class="text-end"><?php echo e(RP($item->mp->Amount ?? '0')); ?></td>
                <td valign="middle" class="text-end"><?php echo e(RP($item->dsa->Amount ?? '0')); ?></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tr class="bg-subheader">
            <th></th>
                <th class="text-start">JUMLAH</th>
                <th class="text-end"><?php echo e(RP($total_rpd)); ?></th>
                <th class="text-end"><?php echo e(RP($total_mp)); ?></th>
                <th class="text-end"><?php echo e(RP($total_dsa)); ?></th>
            </tr>
            <tr class="bg-sumheader">
                <th></th>
                <th class="text-start">JUMLAH PAGU PNBP</th>
                <th class="text-end"><?php echo e(RP($paguPNBP)); ?></th>
                <th class="text-end"></th>
                <th class="text-end"></th>
            </tr>
            <tr class="bg-footer">
                <th></th>
                <th class="text-start">SISA RPD</th>
                <th class="text-end"><?php echo e(RP($paguPNBP-$total_rpd)); ?></th>
                <th class="text-end"></th>
                <th class="text-end"></th>
            </tr>

    </table>
</div>

<footer>
    MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
    Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e($year); ?>

</footer>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-rpd.blade.php ENDPATH**/ ?>