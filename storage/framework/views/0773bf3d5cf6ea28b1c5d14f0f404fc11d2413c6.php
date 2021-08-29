

    <table class="table table-sm table-striped" id="page-all">
        <tr>
            <td colspan="8"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN</b></td>
        </tr>
        <tr>
            <td colspan="8"><b>DIREKTORAT JENDERAL  PERHUBUNGAN LAUT</b></td>
        </tr>
        <tr>
            <td colspan="8"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>

        <thead class="bg-primary">
            <tr>
                <th class="text-center"><b>NO</b></th>
                <th class="text-start"><b>KODE</b></th>
                <th class="text-start"><b>NAMA SATKER</b></th>
                <th class="text-start"><b>PROPINSI</b></th>
                <th class="text-end"><b>PAGU AWAL</b></th>
                <th class="text-end"><b>PAGU AKHIR</b></th>
                <th class="text-end"><b>REALISASI</b></th>
                <th class="text-center"><b>%</b></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr <?php if($item->KodeSatker==Auth:: user()->kdsatker): ?> class="bg-danger" <?php endif; ?>>
                <td class="text-center"><?php echo e($loop->iteration); ?>

                    <?php if($item->KodeSatker==Auth:: user()->kdsatker): ?> <a name="<?php echo e(Auth:: user()->kdsatker); ?>"></a> <?php endif; ?>
                </td>
                <td class="text-center"><?php echo e($item->KodeSatker); ?></td>
                <td class="text-start"><?php echo e($item->NamaSatuanKerja); ?></td>
                <td class="text-start"><?php echo e($item->WilayahName); ?></td>
                <td class="text-end"><?php echo e(($item->PaguAwal)); ?></td>
                <td class="text-end"><?php echo e(($item->Pagu)); ?></td>
                <td class="text-end"><?php echo e(($item->Realisasi)); ?></td>
                <td class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start"></th>
                <th class="text-start"><b>JUMLAH</b></th>
                <th class="text-end"><b><?php echo e(($data->sum('PaguAwal'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Pagu'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Realisasi'))); ?></b></th>
                <th class="text-end"><b><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</b></th>
            </tr>
        </tfoot>
    </table>


</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-ranking-satker.blade.php ENDPATH**/ ?>