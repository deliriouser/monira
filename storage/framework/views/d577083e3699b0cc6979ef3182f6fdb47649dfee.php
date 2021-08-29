

<table class="table">
    <thead class="bg-primary text-white">
      <tr>
          <td colspan="5"><b>REKAPITULASI REALISASI PNBP</b></td>
      </tr>
      <tr>
        <td colspan="5"><b><?php echo e(Auth::user()->satker->NamaSatuanKerja); ?></b></td>
    </tr>
    <tr>
        <td colspan="5"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
    </tr>
    <tr>
        <td colspan="5"></td>
    </tr>

      <tr>
        <th class="text-center"><b>KODE</b></th>
        <th class="text-center"><b>JENIS JASA</b></th>
        <th class="text-center"><b>TARGET</b></th>
        <th class="text-center"><b>REALISASI</b></th>
        <th class="text-center"><b>%</b></th>
      </tr>
    </thead>
    <?php
    $target_fungsional=0;
    $realisasi_fungsional=0;
    ?>

    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->jenis=='F'): ?>
    <?php
    $target_fungsional+=$item->target;
    $realisasi_fungsional+=$item->span;
    ?>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php
        // $persen_fungsional = ($realisasi_fungsional/$target_fungsional)*100;
    ?>
    <thead class="bg-subheader">
        <tr>
        <th colspan="2" class="text-start"><b>PNBP FUNGSIONAL</b></th>
        <th class="text-end"><?php echo e(($target_fungsional)); ?></th>
        <th class="text-end"><?php echo e(($realisasi_fungsional)); ?></th>
        <td class="text-center"><span class="badge badge-<?php echo e(ColorTable(divnum($realisasi_fungsional,$target_fungsional)*100)); ?>"><?php echo e(persen(divnum($realisasi_fungsional,$target_fungsional)*100)); ?>%</span></td>
    </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($item->jenis=="F"): ?>
            <tr class="table-<?php echo e(ColorTable($item->persen_span)); ?>">
                <td class="text-center"><?php echo e($item->akun); ?></td>
                <td class="text-start"><?php echo e($item->uraian); ?></td>
                <td class="text-end"><?php echo e(($item->target)); ?></td>
                <td class="text-end"><?php echo e(($item->span)); ?></td>
                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->persen_span)); ?>"><?php echo e(persen($item->persen_span)); ?>%</span></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <?php
        $target_nonfungsional=0;
        $realisasi_nonfungsional=0;
    ?>

    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($item->jenis=='U'): ?>
        <?php
        $target_nonfungsional+=$item->target;
        $realisasi_nonfungsional+=$item->span;
        ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php
    // $persen_nonfungsional = ($realisasi_nonfungsional/$target_nonfungsional)*100;
    ?>

    <thead class="bg-subheader">
    <tr>
        <th colspan="2" class="text-start"><b>PNBP NON FUNGSIONAL</b></th>
        <th class="text-end"><?php echo e(($target_nonfungsional)); ?></th>
        <th class="text-end"><?php echo e(($realisasi_nonfungsional)); ?></th>
        <td class="text-center"><span class="badge badge-<?php echo e(ColorTable(divnum($realisasi_nonfungsional,$target_nonfungsional)*100)); ?>"><?php echo e(persen(divnum($realisasi_nonfungsional,$target_nonfungsional)*100)); ?>%</span></td>
    </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($item->jenis=="U"): ?>
            <tr class="table-<?php echo e(ColorTable($item->persen_span)); ?>">
                <td class="text-center"><?php echo e($item->akun); ?></td>
                <td class="text-start"><?php echo e($item->uraian); ?></td>
                <td class="text-end"><?php echo e(($item->target)); ?></td>
                <td class="text-end"><?php echo e(($item->span)); ?></td>
                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->persen_span)); ?>"><?php echo e(persen($item->persen_span)); ?>%</span></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </tbody>

    <tfoot class="table-footer text-dark">
        <tr>
          <th></th>
          <th><b>JUMLAH</b></th>
          <th class="text-end"><b><?php echo e(($data->sum('target'))); ?></b></th>
          <th class="text-end"><b><?php echo e(($data->sum('span'))); ?></b></th>
          <th class="text-center"><b><?php echo e(Persen(divnum($data->sum('span'),$data->sum('target'))*100)); ?>%</b></th>
        </tr>
      </tfoot>
  </table>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-pnbp.blade.php ENDPATH**/ ?>