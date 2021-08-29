<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    tr > td {
    border: 1px solid #000000;
    }
</style>
<body>


<table class="table">
    <tr>
        <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?></b></td>
    </tr>
    <tr>
        <td colspan="7"><b>DIREKTORAT JENDERAL  PERHUBUNGAN LAUT</b></td>
    </tr>
    <tr>
        <td colspan="7"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <thead class="bg-primary text-white">
        <tr>
        <th class="text-center"><b>KODE</b></th>
        <th><b>KETERANGAN</b></th>
        <th class="text-center"><b>PAGU AWAL</b></th>
        <th class="text-center"><b>PAGU AKHIR</b></th>
        <th class="text-center"><b>REALISASI</b></th>
        <th class="text-center"><b>SISA</b></th>
        <th class="text-center"><b>%</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
            <td class="text-center"><?php echo e($item->Kode); ?></td>
            <td class="text-start"><?php echo e(($item->Keterangan)); ?></td>
            <td class="text-end"><?php echo e(($item->PaguAwal)); ?></td>
            <td class="text-end"><?php echo e(($item->Pagu)); ?></td>
            <td class="text-end"><?php echo e(($item->Realisasi)); ?></td>
            <td class="text-end"><?php echo e(($item->Sisa)); ?></td>
            <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?>%</span></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot class="table-footer text-dark">
        <tr>
            <th class="text-center"></th>
            <th>JUMLAH</th>
            <th class="text-end"><?php echo e(($data->sum('PaguAwal'))); ?></th>
            <th class="text-end"><?php echo e(($data->sum('Pagu'))); ?></th>
            <th class="text-end"><?php echo e(($data->sum('Realisasi'))); ?></th>
            <th class="text-end"><?php echo e(($data->sum('Sisa'))); ?></th>
            <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
        </tr>
        </tfoot>
</table>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell.blade.php ENDPATH**/ ?>