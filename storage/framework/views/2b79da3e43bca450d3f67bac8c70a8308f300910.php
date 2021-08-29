
<table class="table table-sm" id="card" data-show-columns="true" width="100%;">
    <tr>
        <td colspan="30"><b>REKAPITULASI</b></td>
    </tr>
    <tr>
        <td colspan="30"><b>KEGIATAN PADAT KARYA</b></td>
    </tr>
    <tr>
        <td colspan="30"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
    </tr>
    <tr>
        <td></td>
    </tr>


        <thead class="bg-primary text-white">
            <tr class="bg-primary text-white">
                <th class="text-center" valign="middle" rowspan="2">NO</th>
                <th class="text-start" valign="middle" rowspan="2">KODE</th>
                <th class="text-center" valign="middle" rowspan="2">KETERANGAN</th>
                <th class="text-center" valign="middle" colspan="3">LOKASI</th>
                <th class="text-center" valign="middle" rowspan="2">AKUN</th>
                <th class="text-center" valign="middle" rowspan="2">KEGIATAN</th>
                <th class="text-center" colspan="9">TARGET</th>
                <th class="text-center" valign="middle" rowspan="2"></th>
                <th class="text-center" colspan="14">REALISASI</th>
            </tr>
            <tr>
                <th class="col-1 text-center">KABUPATEN</th>
                <th class="col-1 text-center">KECAMATAN</th>
                <th class="col-1 text-center">DESA / KELURAHAN</th>
                <th class="col-1 text-center">TOTAL PAGU</th>
                <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                <th class="col-1 text-center">% BIAYA UPAH</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                <th class="col-1 text-center">JUMLAH HARI</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                <th class="col-1 text-center">JADWAL KEGIATAN</th>
                <th class="col-1 text-center">MEKANIME KEGIATAN</th>
                <th class="col-1 text-center">TGL SPPD</th>
                <th class="col-1 text-center">NO SPPD</th>
                <th class="col-1 text-center">TOTAL REALISASI</th>
                <th class="col-1 text-center">REALISASI KEGIATAN PENDUKUNG</th>
                <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                <th class="col-1 text-center">% BIAYA UPAH</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                <th class="col-1 text-center">JUMLAH HARI</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                <th class="col-1 text-center">REALISASI KEGIATAN</th>
                <th class="col-1 text-center">MEKANISME KEGIATAN</th>
                <th class="col-1 text-center">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $SumPagu_TotalPagu       = 0;
            $SumPagu_TotalBiayaLain  = 0;
            $SumPagu_TotalBiayaUpah  = 0;
            $SumPagu_JumlahOrang     = 0;
            $SumPagu_JumlahHari      = 0;
            $SumPagu_JumlahOrangHari = 0;
            $SumDsa_TotalPagu        = 0;
            $SumDsa_TotalBiayaLain   = 0;
            $SumDsa_TotalBiayaUpah   = 0;
            $SumDsa_JumlahOrang      = 0;
            $SumDsa_JumlahHari       = 0;
            $SumDsa_JumlahOrangHari  = 0;

        ?>

            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propinsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="table-danger">
                <th><?php echo e($propinsi->KodeWilayah); ?></th>
                <th colspan="29"><?php echo e($propinsi->WilayahName); ?></th>
            </tr>
            <?php
                $PropPagu_TotalPagu       = 0;
                $PropPagu_TotalBiayaLain  = 0;
                $PropPagu_TotalBiayaUpah  = 0;
                $PropPagu_JumlahOrang     = 0;
                $PropPagu_JumlahHari      = 0;
                $PropPagu_JumlahOrangHari = 0;
                $PropDsa_TotalPagu        = 0;
                $PropDsa_TotalBiayaLain   = 0;
                $PropDsa_TotalBiayaUpah   = 0;
                $PropDsa_JumlahOrang      = 0;
                $PropDsa_JumlahHari       = 0;
                $PropDsa_JumlahOrangHari  = 0;

            ?>
            <?php $__currentLoopData = $propinsi->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="<?php if(!empty($satker->realisasipadatkarya)): ?> table-success <?php else: ?> table-warning  <?php endif; ?>">
                <th><?php echo e($loop->iteration); ?></th>
                <th><?php echo e($satker->KodeSatker); ?></th>
                <th colspan="6"><?php echo e($satker->NamaSatuanKerja); ?></th>
                <th class="text-end"><?php echo e(($satker->pagupadatkarya->TotalPagu ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->pagupadatkarya->TotalBiayaLain ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->pagupadatkarya->TotalBiayaUpah ?? '0')); ?></th>
                <th class="text-end"><?php echo e(Persen(divnum($satker->pagupadatkarya->TotalBiayaUpah ?? '0',$satker->pagupadatkarya->TotalPagu ?? '0')*100)); ?>%</th>
                <th class="text-end"><?php echo e(($satker->pagupadatkarya->JumlahOrang ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->pagupadatkarya->JumlahHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->pagupadatkarya->JumlahOrangHari ?? '0')); ?></th>
                <th class="text-end"><span class="nowrap"><?php echo e(($satker->pagupadatkarya->Jadwal ?? '')); ?></span></th>
                <th class="text-end"><span class="nowrap"><?php echo e(($satker->pagupadatkarya->Mekanisme ?? '')); ?></span></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->TotalPagu ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->TotalBiayaLain ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->TotalBiayaUpah ?? '0')); ?></th>
                <th class="text-end"><?php echo e(Persen(divnum($satker->realisasipadatkarya->TotalBiayaUpah ?? '0',$satker->realisasipadatkarya->TotalPagu ?? '0')*100)); ?>%</th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->JumlahOrang ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->JumlahHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->JumlahOrangHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($satker->realisasipadatkarya->Jadwal ?? '')); ?></th>
                <th class="text-end"><span class="nowrap"><?php echo e(($satker->realisasipadatkarya->Mekanisme ?? '')); ?></span></th>
                <th class="text-end"><span class="nowrap"><?php echo e(($satker->realisasipadatkarya->Keterangan ?? '')); ?></span></th>
            </tr>
            <?php if(count($satker->datapadatkarya)>0): ?>
            <?php $__currentLoopData = $satker->datapadatkarya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="nowrap"><?php echo e($item->Kabupaten); ?></span></td>
                <td><span class="nowrap">KEC. <?php echo e(strtoupper($item->Kecamatan)); ?></span></td>
                <td><span class="nowrap">DES. <?php echo e(strtoupper($item->Desa)); ?></span></td>
            </tr>
            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo e($akun->Akun); ?></td>
                <td><?php echo e($akun->Uraian); ?></td>
                <td><?php echo e($akun->Amount); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($items->realisasi_akun->sppd)): ?>
                    <?php $__currentLoopData = $items->realisasi_akun->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemsppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e(\Carbon\Carbon::parse($itemsppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td><?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($items->realisasi_akun->sppd)): ?>
                    <?php $__currentLoopData = $items->realisasi_akun->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemsppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($itemsppd->nosppd ?? '0')); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td><?php echo e(($akun->realisasi_akun->TotalPagu ?? '0')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->TotalBiayaLain ?? '0')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->TotalBiayaUpah ?? '0')); ?></td>
                <td><?php echo e(Persen($akun->realisasi_akun->PersenBiayaUpah ?? '0')); ?>%</td>
                <td><?php echo e(($akun->realisasi_akun->JumlahOrang ?? '0')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->JumlahHari ?? '0')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->JumlahOrangHari ?? '0')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->Jadwal ?? '')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->Mekanisme ?? '')); ?></td>
                <td><?php echo e(($akun->realisasi_akun->Keterangan ?? '')); ?></td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php
            $PropPagu_TotalPagu       += $satker->pagupadatkarya->TotalPagu ?? '0';
            $PropPagu_TotalBiayaLain  += $satker->pagupadatkarya->TotalBiayaLain ?? '0';
            $PropPagu_TotalBiayaUpah  += $satker->pagupadatkarya->TotalBiayaUpah  ?? '0';
            $PropPagu_JumlahOrang     += $satker->pagupadatkarya->JumlahOrang ?? '0';
            $PropPagu_JumlahHari      += $satker->pagupadatkarya->JumlahHari ?? '0';
            $PropPagu_JumlahOrangHari += $satker->pagupadatkarya->JumlahOrangHari ?? '0';
            $PropDsa_TotalPagu        += $satker->realisasipadatkarya->TotalPagu ?? '0';
            $PropDsa_TotalBiayaLain   += $satker->realisasipadatkarya->TotalBiayaLain ?? '0';
            $PropDsa_TotalBiayaUpah   += $satker->realisasipadatkarya->TotalBiayaUpah ?? '0';
            $PropDsa_JumlahOrang      += $satker->realisasipadatkarya->JumlahOrang ?? '0';
            $PropDsa_JumlahHari       += $satker->realisasipadatkarya->JumlahHari ?? '0';
            $PropDsa_JumlahOrangHari  += $satker->realisasipadatkarya->JumlahOrangHari ?? '0';

            ?>

            <?php endif; ?>



            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <th></th>
                <th></th>
                <th colspan="6">JUMLAH PROPINSI</th>
                <th class="text-end"><?php echo e(($PropPagu_TotalPagu ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropPagu_TotalBiayaLain ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropPagu_TotalBiayaUpah ?? '0')); ?></th>
                <th class="text-end"><?php echo e(Persen(divnum($PropPagu_TotalBiayaUpah ?? '0',$PropPagu_TotalPagu ?? '0')*100)); ?>%</th>
                <th class="text-end"><?php echo e(($PropPagu_JumlahOrang ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropPagu_JumlahHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropPagu_JumlahOrangHari ?? '0')); ?></th>
                <th class="text-end"></th>
                <th class="text-end"></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-end"><?php echo e(($PropDsa_TotalPagu ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropDsa_TotalBiayaLain ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropDsa_TotalBiayaUpah ?? '0')); ?></th>
                <th class="text-end"><?php echo e(Persen(divnum($PropDsa_TotalBiayaUpah ?? '0',$PropDsa_TotalPagu ?? '0')*100)); ?>%</th>
                <th class="text-end"><?php echo e(($PropDsa_JumlahOrang ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropDsa_JumlahHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($PropDsa_JumlahOrangHari ?? '0')); ?></th>
                <th class="text-end"></th>
                <th class="text-end"></th>
                <th></th>

            </tr>
            <?php
            $SumPagu_TotalPagu       += $PropPagu_TotalPagu;
            $SumPagu_TotalBiayaLain  += $PropPagu_TotalBiayaLain;
            $SumPagu_TotalBiayaUpah  += $PropPagu_TotalBiayaUpah ;
            $SumPagu_JumlahOrang     += $PropPagu_JumlahOrang;
            $SumPagu_JumlahHari      += $PropPagu_JumlahHari;
            $SumPagu_JumlahOrangHari += $PropPagu_JumlahOrangHari;
            $SumDsa_TotalPagu        += $PropDsa_TotalPagu;
            $SumDsa_TotalBiayaLain   += $PropDsa_TotalBiayaLain;
            $SumDsa_TotalBiayaUpah   += $PropDsa_TotalBiayaUpah;
            $SumDsa_JumlahOrang      += $PropDsa_JumlahOrang;
            $SumDsa_JumlahHari       += $PropDsa_JumlahHari;
            $SumDsa_JumlahOrangHari  += $PropDsa_JumlahOrangHari;

            ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr class="table-primary">
                <th></th>
                <th></th>
                <th colspan="6">TOTAL</th>
                <th class="text-end"><?php echo e(($SumPagu_TotalPagu ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumPagu_TotalBiayaLain ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumPagu_TotalBiayaUpah ?? '0')); ?></th>
                <th class="text-end"><?php echo e(Persen(divnum($SumPagu_TotalBiayaUpah ?? '0',$SumPagu_TotalPagu ?? '0')*100)); ?>%</th>
                <th class="text-end"><?php echo e(($SumPagu_JumlahOrang ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumPagu_JumlahHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumPagu_JumlahOrangHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumPagu_Jadwal ?? '')); ?></th>
                <th class="text-end"><?php echo e(($SumPagu_Mekanisme ?? '')); ?></th>
                <th></th>
                <th></th>
                <th></th>

                <th class="text-end"><?php echo e(($SumDsa_TotalPagu ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumDsa_TotalBiayaLain ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumDsa_TotalBiayaUpah ?? '0')); ?></th>
                <th class="text-end"><?php echo e(Persen(divnum($SumDsa_TotalBiayaUpah ?? '0',$SumDsa_TotalPagu ?? '0')*100)); ?>%</th>
                <th class="text-end"><?php echo e(($SumDsa_JumlahOrang ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumDsa_JumlahHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumDsa_JumlahOrangHari ?? '0')); ?></th>
                <th class="text-end"><?php echo e(($SumDsa_Jadwal ?? '')); ?></th>
                <th class="text-end"><?php echo e(($SumDsa_Mekanisme ?? '')); ?></th>
                <th></th>

            </tr>

        </tfoot>
    </table>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-padatkarya-rekap-rincian-level.blade.php ENDPATH**/ ?>