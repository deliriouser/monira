
<table class="table table-sm" id="card">
    <tr>
        <td colspan="11"><b>REKAPITULASI</b></td>
    </tr>
    <tr>
        <td colspan="11"><b>KEGIATAN PADAT KARYA</b></td>
    </tr>
    <tr>
        <td colspan="11"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <thead class="bg-primary text-white">
        <tr class="bg-primary">
            <th class="text-center" colspan="5">TARGET</th>
            <th class="text-center" rowspan="2"></th>
            <th class="text-center" colspan="5">REALISASI</th>
        </tr>
        <tr>
            <th class="col-1 text-center">TOTAL PAGU KEGIATAN`</th>
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
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <tr>
            <td class="text-end"><?php echo e(($item->Target_TotalPagu)); ?></td>
            <td class="text-end"><?php echo e(($item->Target_TotalBiayaLain)); ?></td>
            <td class="text-end"><?php echo e(($item->Target_TotalBiayaUpah)); ?></td>
            <td class="text-center"><?php echo e(($item->Target_JumlahOrang)); ?></td>
            <td class="text-center"><?php echo e(($item->Target_JumlahOrangHari)); ?></td>
            <td></td>
            <td class="text-end"><?php echo e(($item->Daser_TotalPagu)); ?></td>
            <td class="text-end"><?php echo e(($item->Daser_TotalBiayaLain)); ?></td>
            <td class="text-end"><?php echo e(($item->Daser_TotalBiayaUpah)); ?></td>
            <td class="text-center"><?php echo e(($item->Daser_JumlahOrang)); ?></td>
            <td class="text-center"><?php echo e(($item->Daser_JumlahOrangHari)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-padatkarya-rekap-one-level.blade.php ENDPATH**/ ?>