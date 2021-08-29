
<?php switch($unit):
    case ('eselon1'): ?>
    <table class="table table-sm table-striped" id="page-all">
        <tr>
            <td colspan="8"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN PER SATKER</b></td>
        </tr>
        <tr>
            <td colspan="8"><b><?php echo e(Auth::user()->satker->NamaSatuanKerja); ?></b></td>
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

        <?php break; ?>
    <?php case ('propinsi'): ?>


    <table class="table table-sm table-striped" id="page-all">
        <tr>
            <td colspan="7"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN PER PROPINSI</b></td>
        </tr>
        <tr>
            <td colspan="7"><b><?php echo e(Auth::user()->satker->NamaSatuanKerja); ?></b></td>
        </tr>
        <tr>
            <td colspan="7"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>

        <thead class="bg-primary">
            <tr>
                <th class="text-center"><b>NO</b></th>
                <th class="text-start"><b>KODE</b></th>
                <th class="text-start"><b>PROPINSI</b></th>
                <th class="text-end"><b>PAGU AWAL</b></th>
                <th class="text-end"><b>PAGU AKHIR</b></th>
                <th class="text-end"><b>REALISASI</b></th>
                <th class="text-center"><b>%</b></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td class="text-center"><?php echo e($item->KodeWilayah); ?></td>
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
                <th class="text-end"><b>JUMLAH</b></th>
                <th class="text-end"><b><?php echo e(($data->sum('PaguAwal'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Pagu'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Realisasi'))); ?></b></th>
                <th class="text-center"><b><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</b></th>
            </tr>
        </tfoot>
    </table>
        <?php break; ?>
    <?php case ('satker'): ?>
    <table class="table table-sm" id="page-all">

        <tr>
            <td colspan="7"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN PER PROPINSI SATKER</b></td>
        </tr>
        <tr>
            <td colspan="7"><b><?php echo e(Auth::user()->satker->NamaSatuanKerja); ?></b></td>
        </tr>
        <tr>
            <td colspan="7"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>

        <thead class="bg-primary">
            <tr>
                <th class="text-center"><b>NO</b></th>
                <th class="text-start"><b>KODE</b></th>
                <th class="text-start"><b>PROPINSI</b></th>
                <th class="text-end"><b>PAGU AWAL</b></th>
                <th class="text-end"><b>PAGU AKHIR</b></th>
                <th class="text-end"><b>REALISASI</b></th>
                <th class="text-center"><b>%</b></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $TotalPaguAwal=0;
                $TotalPaguAkhir=0;
                $TotalRealisasi=0;
            ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="table-danger">
                <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                <td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
            </tr>
            <?php
                $noSatker=0;
                $PaguAwal=0;
                $PaguAkhir=0;
                $Realisasi=0;
            ?>
            <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $PaguAwal+=$satker->PaguAwal;
                $PaguAkhir+=$satker->PaguAkhir;
                $Realisasi+=$satker->Realisasi;
            ?>
            <tr>
                <td class="text-center"><?php echo e($noSatker=$noSatker+1); ?></td>
                <td class="text-center"><?php echo e($satker->Kode); ?></td>
                <td class="text-start"><?php echo e($satker->Keterangan); ?></td>
                <td class="text-end"><?php echo e(($satker->PaguAwal)); ?></td>
                <td class="text-end"><?php echo e(($satker->PaguAkhir)); ?></td>
                <td class="text-end"><?php echo e(($satker->Realisasi)); ?></td>
                <td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <tr class="border-top-primary">
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start"><b>JUMLAH</b></th>
                <th class="text-end"><b><?php echo e(($PaguAwal)); ?></b></th>
                <th class="text-end"><b><?php echo e(($PaguAkhir)); ?></b></th>
                <th class="text-end"><b><?php echo e(($Realisasi)); ?></b></th>
                <th class="text-center"><b><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</b></th>
            </tr>

            <?php
                $TotalPaguAwal  += $PaguAwal;
                $TotalPaguAkhir += $PaguAkhir;
                $TotalRealisasi += $Realisasi;
            ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
            <tr class="table-primary">
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start"><b>JUMLAH RAYA</b></th>
                <th class="text-end"><b><?php echo e(($TotalPaguAwal)); ?></b></th>
                <th class="text-end"><b><?php echo e(($TotalPaguAkhir)); ?></b></th>
                <th class="text-end"><b><?php echo e(($TotalRealisasi)); ?></b></th>
                <th class="text-center"><b><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</b></th>
            </tr>
    </table>
        <?php break; ?>
<?php endswitch; ?>



<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-ranking.blade.php ENDPATH**/ ?>