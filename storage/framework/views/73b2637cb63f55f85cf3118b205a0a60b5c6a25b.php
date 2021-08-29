<?php switch($unit):
        case ('eselon1'): ?>

            <table class="table table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <td colspan="8">REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="8">DIREKTORAT JENDERAL  PERHUBUNGAN LAUT</td>
                    </tr>
                    <tr>
                        <td colspan="8">TAHUN ANGGARAN <?php echo e($year); ?></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                    </tr>
                   <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                        <td class="text-center"><?php echo e($item['Kode']); ?></td>
                        <td class="text-start"><?php echo e($item['Keterangan']); ?></td>
                        <td class="text-end"><?php echo e(($item['Pagu'])); ?></td>
                        <td class="text-end"><?php echo e(($item['Realisasi'])); ?></td>
                        <td class="text-center"><?php echo e(Persen($item['Persen'])); ?>%</td>
                        <td class="text-end"><?php echo e(($item['Prognosa'])); ?></td>
                        <td class="text-center"><?php echo e(Persen($item['PersenPrognosa'])); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(($data->sum('Pagu'))); ?></th>
                        <th class="text-end"><?php echo e(($data->sum('Realisasi'))); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($data->sum('Prognosa'))); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)); ?>%</th>
                    </tr>
                </tfoot>
            </table>

            <?php break; ?>

        <?php case ('propinsi'): ?>
            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="8">REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?></td>
                </tr>
                <tr>
                    <td colspan="8">DIREKTORAT JENDERAL  PERHUBUNGAN LAUT</td>
                </tr>
                <tr>
                    <td colspan="8">TAHUN ANGGARAN <?php echo e($year); ?></td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>

                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
                        $TotalPrognosa=0;
                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>
                    <?php
                        $noSatker=0;
                        $PaguAwal=0;
                        $PaguAkhir=0;
                        $Realisasi=0;
                        $Prognosa=0;
                    ?>
                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                        $Prognosa+=$satker->Prognosa;
                    ?>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($satker->Kode); ?></td>
                        <td class="text-start"><?php echo e($satker->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(($satker->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(($satker->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
                        <td class="text-end"><?php echo e(($satker->Prognosa)); ?></td>
                        <td class="text-center"><?php echo e(Persen($satker->PersenPrognosa)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(($PaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(($Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($Prognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Prognosa,$PaguAkhir)*100)); ?>%</th>
                    </tr>

                    <?php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa += $Prognosa;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end"><?php echo e(($TotalPaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(($TotalRealisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($TotalPrognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)); ?>%</th>
                    </tr>
                </tfoot>
            </table>

        <?php break; ?>

        <?php case ('satker'): ?>

            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="8">REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?></td>
                </tr>
                <tr>
                    <td colspan="8">DIREKTORAT JENDERAL  PERHUBUNGAN LAUT</td>
                </tr>
                <tr>
                    <td colspan="8">TAHUN ANGGARAN <?php echo e($year); ?></td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
           <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $TotalPrognosa  = 0;
                        $PaguAwalSub    = 0;
                        $PaguAkhirSub   = 0;
                        $RealisasiSub   = 0;
                        $PrognosaSub    = 0;

                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>

                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-warning">
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="text-center"><b><?php echo e($key); ?></b></td>
                            <td class="text-start" colspan="6"><b>
                                <?php if(isset($value->KodeSubHeader)): ?>
                                    <?php echo e($value->NamaSubHeader); ?>

                                <?php endif; ?>
                            </b></td>
                    </tr>
                    <?php
                        $PaguAwal  = 0;
                        $PaguAkhir = 0;
                        $Realisasi = 0;
                        $Prognosa  = 0;

                    ?>

                    <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                        $Prognosa  += $detil->Prognosa;

                    ?>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($detil->Kode); ?></td>
                        <td class="text-start"><?php echo e($detil->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(($detil->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(($detil->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</td>
                        <td class="text-end"><?php echo e(($detil->Prognosa)); ?></td>
                        <td class="text-center"><?php echo e(Persen($detil->PersenPrognosa)); ?>%</td>
                    </tr>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        // $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa  += $Prognosa;

                    ?>

                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end"><?php echo e(($PaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(($Realisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($Prognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($Prognosa,$PaguAkhir)*100)); ?>%</th>
                    </tr>

                    <?php
                    // $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    $PrognosaSub  += $Prognosa;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH PROPINSI</th>
                        <th class="text-end"><?php echo e(($PaguAkhirSub)); ?></th>
                        <th class="text-end"><?php echo e(($RealisasiSub)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($PrognosaSub)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)); ?>%</th>
                    </tr>

                    <?php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    $PrognosaSub  = 0;
                    ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-footer text-dark">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end"><?php echo e(($TotalPaguAkhir)); ?></th>
                        <th class="text-end"><?php echo e(($TotalRealisasi)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($TotalPrognosa)); ?></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)); ?>%</th>
                    </tr>

            </table>

        <?php break; ?>

    <?php endswitch; ?>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-prognosa.blade.php ENDPATH**/ ?>