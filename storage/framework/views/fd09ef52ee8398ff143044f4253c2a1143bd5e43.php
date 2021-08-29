<?php switch($unit):
        case ('eselon1'): ?>
            <table class="table table-sm" id="card">
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
                    <td colspan="7"><b>PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?></b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center"><b>NO</b></th>
                        <th class="text-center"><b>KODE</b></th>
                        <th class="text-center"><b>KETERANGAN</b></th>
                        <th class="text-center"><b>PAGU AWAL</b></th>
                        <th class="text-center"><b>PAGU AKHIR</b></th>
                        <th class="text-center"><b>REALISASI</b></th>
                        <th class="text-center"><b>%</b></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                        <td class="text-center"><?php echo e($item['Kode']); ?></td>
                        <td class="text-start"><?php echo e($item['Keterangan']); ?></td>
                        <td class="text-end"><?php echo e(($item['PaguAwal'])); ?></td>
                        <td class="text-end"><?php echo e(($item['Pagu'])); ?></td>
                        <td class="text-end"><?php echo e(($item['Realisasi'])); ?></td>
                        <td class="text-center"><?php echo e(Persen($item['Persen'])); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-start"><b>JUMLAH</b></th>
                        <th class="text-end"><b><?php echo e(($data->sum('PaguAwal'))); ?></b></th>
                        <th class="text-end"><b><?php echo e(($data->sum('Pagu'))); ?></b></th>
                        <th class="text-end"><b><?php echo e(($data->sum('Realisasi'))); ?></b></th>
                        <th class="text-center"><b><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</b></th>
                    </tr>
                </tfoot>
            </table>

            <?php break; ?>

        <?php case ('propinsi'): ?>
            <table class="table table-sm" id="page-all">
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
                    <td colspan="7"><b>PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?></b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>

                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center"><b>NO</b></th>
                        <th class="text-center"><b>KODE</b></th>
                        <th class="text-center"><b>KETERANGAN</b></th>
                        <th class="text-center"><b>PAGU AWAL</b></th>
                        <th class="text-center"><b>PAGU AKHIR</b></th>
                        <th class="text-center"><b>REALISASI</b></th>
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
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="6"><b><?php echo e($item->NamaHeader); ?></b></td>
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
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($satker->Kode); ?></td>
                        <td class="text-start"><?php echo e($satker->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(($satker->PaguAwal)); ?></td>
                        <td class="text-end"><?php echo e(($satker->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(($satker->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="border-top-primary bg-sumheader">
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
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH RAYA</b></th>
                        <th class="text-end"><b><?php echo e(($TotalPaguAwal)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($TotalPaguAkhir)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($TotalRealisasi)); ?></b></th>
                        <th class="text-center"><b><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</b></th>
                    </tr>
                </tfoot>
            </table>

            <?php break; ?>

            <?php case ('satker'): ?>
            <table class="table table-sm" id="page-all">
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
                    <td colspan="7"><b>PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?></b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>

                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center"><b>NO</b></th>
                        <th class="text-center"><b>KODE</b></th>
                        <th class="text-center"><b>KETERANGAN</b></th>
                        <th class="text-center"><b>PAGU AWAL</b></th>
                        <th class="text-center"><b>PAGU AKHIR</b></th>
                        <th class="text-center"><b>REALISASI</b></th>
                        <th class="text-center"><b>%</b></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $PaguAwalSub  = 0;
                        $PaguAkhirSub = 0;
                        $RealisasiSub = 0;

                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="6"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>

                    <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-warning">
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="text-center"><b><?php echo e($key); ?></b></td>
                            <td class="text-start" colspan="5"><b>
                                <?php if(isset($value->KodeSubHeader)): ?>
                                    <?php echo e($value->NamaSubHeader); ?>

                                <?php endif; ?>
                            </b></td>
                    </tr>
                    <?php
                        $PaguAwal     = 0;
                        $PaguAkhir    = 0;
                        $Realisasi    = 0;
                    ?>

                    <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                    ?>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo e($detil->Kode); ?></td>
                        <td class="text-start"><?php echo e($detil->Keterangan); ?></td>
                        <td class="text-end"><?php echo e(($detil->PaguAwal)); ?></td>
                        <td class="text-end"><?php echo e(($detil->PaguAkhir)); ?></td>
                        <td class="text-end"><?php echo e(($detil->Realisasi)); ?></td>
                        <td class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</td>
                    </tr>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    ?>

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH</b></th>
                        <th class="text-end"><b><?php echo e(($PaguAwal)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($PaguAkhir)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($Realisasi)); ?></b></th>
                        <th class="text-center"><b><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</b></th>
                    </tr>

                    <?php
                    $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH PROPINSI</b></th>
                        <th class="text-end"><b><?php echo e(($PaguAwalSub)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($PaguAkhirSub)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($RealisasiSub)); ?></b></th>
                        <th class="text-center"><b><?php echo e(Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)); ?>%</b></th>
                    </tr>

                    <?php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH RAYA</b></th>
                        <th class="text-end"><b><?php echo e(($TotalPaguAwal)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($TotalPaguAkhir)); ?></b></th>
                        <th class="text-end"><b><?php echo e(($TotalRealisasi)); ?></b></th>
                        <th class="text-center"><b><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</b></th>
                    </tr>
                    </tfoot>

            </table>

            <?php break; ?>
        <?php default: ?>

    <?php endswitch; ?>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-spending.blade.php ENDPATH**/ ?>