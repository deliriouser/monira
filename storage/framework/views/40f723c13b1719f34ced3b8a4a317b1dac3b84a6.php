<?php switch($segment):
    case ('belanja_covid'): ?>
    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS BELANJA KEGIATAN PENANGANAN COVID-19</b></td>
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
            <?php
            $TotalPaguAwal=0;
            $TotalPaguAkhir=0;
            $TotalRealisasi=0;
            $TotalSisa=0;
            ?>

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
            <?php
            $TotalPaguAwal  += $item->PaguAwal;
            $TotalPaguAkhir += $item->Pagu;
            $TotalRealisasi += $item->Realisasi;
            $TotalSisa      += $item->Sisa;
            ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot class="table-footer text-dark">
            <tr>
              <th class="text-center"></th>
              <th>JUMLAH</th>
              <th class="text-end"><?php echo e(($TotalPaguAwal)); ?></th>
              <th class="text-end"><?php echo e(($TotalPaguAkhir)); ?></th>
              <th class="text-end"><?php echo e(($TotalRealisasi)); ?></th>
              <th class="text-end"><?php echo e(($TotalSisa)); ?></th>
              <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
            </tr>
          </tfoot>
      </table>

        <?php break; ?>
    <?php case ('kegiatan_covid'): ?>
    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS KEGIATAN KEGIATAN PENANGANAN COVID-19</b></td>
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
            <?php
            $TotalPaguAwal=0;
            $TotalPaguAkhir=0;
            $TotalRealisasi=0;
            $TotalSisa=0;
            ?>

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
            <?php
            $TotalPaguAwal  += $item->PaguAwal;
            $TotalPaguAkhir += $item->Pagu;
            $TotalRealisasi += $item->Realisasi;
            $TotalSisa      += $item->Sisa;
            ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot class="table-footer text-dark">
            <tr>
              <th class="text-center"></th>
              <th>JUMLAH</th>
              <th class="text-end"><?php echo e(($TotalPaguAwal)); ?></th>
              <th class="text-end"><?php echo e(($TotalPaguAkhir)); ?></th>
              <th class="text-end"><?php echo e(($TotalRealisasi)); ?></th>
              <th class="text-end"><?php echo e(($TotalSisa)); ?></th>
              <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
            </tr>
          </tfoot>
      </table>
        <?php break; ?>
    <?php case ('sumberdana_covid'): ?>
    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS SUMBER DANA KEGIATAN PENANGANAN COVID-19</b></td>
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
            <?php
            $TotalPaguAwal=0;
            $TotalPaguAkhir=0;
            $TotalRealisasi=0;
            $TotalSisa=0;
            ?>

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
            <?php
            $TotalPaguAwal  += $item->PaguAwal;
            $TotalPaguAkhir += $item->Pagu;
            $TotalRealisasi += $item->Realisasi;
            $TotalSisa      += $item->Sisa;
            ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot class="table-footer text-dark">
            <tr>
              <th class="text-center"></th>
              <th>JUMLAH</th>
              <th class="text-end"><?php echo e(($TotalPaguAwal)); ?></th>
              <th class="text-end"><?php echo e(($TotalPaguAkhir)); ?></th>
              <th class="text-end"><?php echo e(($TotalRealisasi)); ?></th>
              <th class="text-end"><?php echo e(($TotalSisa)); ?></th>
              <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
            </tr>
          </tfoot>
      </table>
    <?php break; ?>

    <?php default: ?>

    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?></b></td>
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
                <th><b>JUMLAH</b></th>
                <th class="text-end"><b><?php echo e(($data->sum('PaguAwal'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Pagu'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Realisasi'))); ?></b></th>
                <th class="text-end"><b><?php echo e(($data->sum('Sisa'))); ?></b></th>
                <th class="text-center"><b><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</b></th>
            </tr>
            </tfoot>
    </table>

<?php endswitch; ?>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-one-level.blade.php ENDPATH**/ ?>