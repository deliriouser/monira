
    <table class="table table-sm">
    <tr>
        <td colspan="8"><b>REALISASI BELANJA SATKER BERDASARKAN RANGKING TERBESAR KE TERENDAH</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>DIREKTORAT JENDERAL PERHUBUNGAN LAUT</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>TAHUN ANGGARAN 2021</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>POSISI <?php echo e(date('d')); ?> <?php echo e(strtoupper(nameofmonth(date('n')))); ?> <?php echo e(date('Y')); ?></b></td>
    </tr>

    <thead class="bg-primary text-white">
        <tr>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center bg-primary"><b>NO</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>KODE</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>NAMA SATKER</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>PAGU</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>REALISASI</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>%</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>SISA</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>PROGNOSA</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-center"><?php echo e($loop->iteration); ?></td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-center"><?php echo e($item->KodeSatker); ?></td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-start"><?php echo e($item->NamaSatuanKerja); ?></td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-end"><?php echo e(($item->Pagu)); ?></td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-end"><?php echo e(($item->Realisasi)); ?></td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-end"><?php echo e(($item->Sisa)); ?></td>
            <td style="<?php if($item->Persen>$top): ?> background-color:#d1e7dd; <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> background-color:#fefbec; <?php else: ?> background-color:#f0a6ad; <?php endif; ?>" class="text-center">
                <?php if($item->Persen_prognosa>0 and $item->Persen_prognosa<100): ?>
                <?php echo e(Persen($item->Persen_prognosa)); ?>%
                <?php elseif($item->Persen_prognosa>100): ?>
                <?php echo e(Persen($item->Persen_satker)); ?>%
                <?php else: ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tr>
        <td colspan="8" style="text-align:left;">Keterangan</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Hijau : Realisasi Telah Melampaui <?php echo e($top); ?>%</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Kuning : Realisasi Masih Dibawah <?php echo e($top); ?>%</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Merah : Realisasi Masih Dibawah Target Prognosa Bulan Berjalan <?php echo e($bottom); ?>%</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Prognosa Kosong : UPT/Satker Belum Kirim SPTJM</td>
    </tr>
</table>

<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-ranking-harian.blade.php ENDPATH**/ ?>