
<table class="table table-sm" id="card">
    <tr>
        <td colspan="5"><b>REKAPITULASI</b></td>
    </tr>
    <tr>
        <td colspan="5"><b>KEGIATAN PADAT KARYA</b></td>
    </tr>
    <tr>
        <td colspan="5"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <thead class="bg-primary text-white">
        <tr>
            <th class="text-center">NO</th>
            <th class="text-start">KODE</th>
            <th class="text-start">KETERANGAN</th>
            <th class="text-center"></th>
            <th class="text-end">PAGU</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalpagu =0;
        ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="table-danger">
            <th class="text-center"><?php echo e($item->KodeWilayah); ?></th>
            <th class="text-start" colspan="4"><?php echo e($item->NamaWilayah); ?></th>
        </tr>
        <?php
        $totalpaguSatker =0;
        ?>

        <?php $__currentLoopData = $item->Satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="table-warning">
            <th class="text-end"></th>
            <th class="text-start"><?php echo e($satker->KodeSatker); ?></th>
            <th class="text-start"><?php echo e($satker->NamaSatker); ?></th>
            <th class="text-end"></th>
            <th class="text-end"></th>
        </tr>
        <?php $__currentLoopData = $satker->Akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="text-center">@</td>
            <td class="text-start"><?php echo e($Akun->KodeAkun); ?></td>
            <td class="text-start"><?php echo e($Akun->NamaAkun); ?></td>
            <th class="text-center"><?php echo e($Akun->KodeSumberDana); ?></th>
            <th class="text-end"><?php echo e(($Akun->Pagu)); ?></th>
        </tr>
        <?php
            $totalpagu +=$Akun->Pagu;
            ?>
            <?php
            $totalpaguSatker +=$Akun->Pagu;
            ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <tr class="table-warning">
            <th></th>
            <th class="text-end"></th>
            <th class="text-start">JUMLAH SATKER</th>
            <th class="text-end"></th>
            <th class="text-end"><?php echo e(($totalpaguSatker)); ?></th>
        </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot class="table-primary">
        <tr>
            <th></th>
            <th class="text-end"></th>
            <th class="text-start">JUMLAH RAYA</th>
            <th class="text-end"></th>
            <th class="text-end"><?php echo e(($totalpagu)); ?></th>
        </tr>
    </tfoot>
</table>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-padatkarya-three-level.blade.php ENDPATH**/ ?>