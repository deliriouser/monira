



<table class="table table-sm" id="card">
            <tr>
                <td colspan="7"><b>REKAPITULASI</b></td>
            </tr>
            <tr>
                <td colspan="7"><b>KEGIATAN PADAT KARYA</b></td>
            </tr>
            <tr>
                <td colspan="7"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <thead class="bg-primary text-white">
            <tr>
                <th class="text-center">KODE</th>
                <th class="text-center"></th>
                <th class="text-start">KETERANGAN</th>
                <th class="text-end">PAGU</th>
                <th class="text-end">REALISASI</th>
                <th class="text-center">%</th>
                <th class="text-end">SISA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalpagu =0;
            $totaldsa =0;
            $totalsisa =0;
            ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="table-danger">
                <th class="text-center"><?php echo e($item->KodeProgram); ?></th>
                <th class="text-end"></th>
                <th class="text-start"><?php echo e($item->NamaProgram); ?></th>
                <th class="text-end" colspan="4"></th>
            </tr>
            <?php $__currentLoopData = $item->Kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="table-warning">
                <th class="text-center"><?php echo e($Kegiatan->KodeKegiatan); ?></th>
                <th class="text-end"></th>
                <th class="text-start"><?php echo e($Kegiatan->NamaKegiatan); ?></th>
                <th class="text-end" colspan="4"></th>
            </tr>
            <?php $__currentLoopData = $Kegiatan->Output; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Output): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="table-success">
                <th class="text-center"><?php echo e($Output->KodeOutput); ?></th>
                <th class="text-end"></th>
                <th class="text-start"><?php echo e($Output->NamaOutput); ?></th>
                <th class="text-end" colspan="4"></th>
            </tr>
            <?php $__currentLoopData = $Output->Akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($Akun->KodeAkun); ?></td>
                <td class="text-center"><?php echo e($Akun->KodeSumberDana); ?></td>
                <td class="text-start"><?php echo e($Akun->NamaAkun); ?></td>
                <td class="text-end"><?php echo e(RP($Akun->Pagu)); ?></td>
                <td class="text-end"><?php echo e(RP($Akun->Dsa)); ?></td>
                <td class="text-center"><?php echo e(Persen($Akun->Persen)); ?>%</td>
                <td class="text-end"><?php echo e(RP($Akun->Sisa)); ?></td>
            </tr>
            <?php
                $totalpagu += $Akun->Pagu;
                $totaldsa  += $Akun->Dsa;
                $totalsisa += $Akun->Sisa;
            ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th></th>
                <th class="text-end"></th>
                <th class="text-start">JUMLAH</th>
                <th class="text-end"><?php echo e(RP($totalpagu)); ?></th>
                <th class="text-end"><?php echo e(RP($totaldsa)); ?></th>
                <th class="text-center"><?php echo e(Persen(divnum($totaldsa,$totalpagu)*100)); ?>%</th>
                <th class="text-end"><?php echo e(RP($totalsisa)); ?></th>
            </tr>
        </tfoot>
    </table>

<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-padatkarya-one-level.blade.php ENDPATH**/ ?>