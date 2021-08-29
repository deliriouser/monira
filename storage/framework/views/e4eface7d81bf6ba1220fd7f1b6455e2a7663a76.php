<?php switch($unit):
        case ('eselon1'): ?>
            <table class="table table-sm" id="card">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19 PER JENIS <?php echo e(STRTOUPPER($segment)); ?></b></td>
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
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19 PER JENIS <?php echo e(STRTOUPPER($segment)); ?></b></td>
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
            <?php if($segment!='volume'): ?>
            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19 PER JENIS <?php echo e(STRTOUPPER($segment)); ?></b></td>
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
            <?php else: ?>

            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="12"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>DIREKTORAT JENDERAL PERHUBUNGAN LAUT</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>TAHUN ANGGARAN <?php echo e($year); ?></b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>PERIODE S.D BULAN <?php echo e(strtoupper(nameofmonth($month))); ?></b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>

                <thead class="bg-primary">
                    <tr valign="middle">
                        <th class="text-center" rowspan="2">NO</th>
                        <th class="text-start" rowspan="2">KODE</th>
                        <th class="text-start" rowspan="2">KETERANGAN</th>
                        <th class="text-center" rowspan="2">DANA</th>
                        <th class="text-center" colspan="2">PAGU</th>
                        <th class="text-center" rowspan="2"></th>
                        <th class="text-center" colspan="2">REALISASI</th>
                        <th class="text-center" rowspan="2">TGL / NO SP2D</th>
                        <th class="text-center" rowspan="2">%</th>
                        <th class="text-center" rowspan="2">SISA</th>
                    </tr>
                    <tr>
                        <th class="text-center">VOLUME</th>
                        <th class="text-center">RUPIAH</th>
                        <th class="text-center">VOLUME</th>
                        <th class="text-center">RUPIAH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $TotalPaguAkhir          = 0;
                        $TotalRealisasi          = 0;
                        $TotalSisa               = 0;
                        $PaguAwalSub             = 0;
                        $PaguAkhirSub            = 0;
                        $RealisasiSub            = 0;
                        $SisaSub                 = 0;
                        $TotalPaguAkhir_kegiatan = 0;
                        $TotalRealisasi_kegiatan = 0;
                        $TotalSisa_kegiatan      = 0;

                    ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-danger">
                        <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                        <td class="text-start" colspan="11"><b><?php echo e($item->NamaHeader); ?></b></td>
                    </tr>

                    <?php
                        $PaguAkhirSub_kegiatan = 0;
                        $RealisasiSub_kegiatan = 0;
                        $SisaSub_kegiatan      = 0;

                    ?>
                <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-warning">
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="text-center"><b><?php echo e($key); ?></b></td>
                            <td class="text-start" colspan="10"><b>
                                <?php if(isset($value->KodeSubHeader)): ?>
                                    <?php echo e($value->NamaSubHeader); ?>

                                <?php endif; ?>
                            </b></td>
                    </tr>
                    <?php
                        $PaguAkhir = 0;
                        $Realisasi = 0;
                        $Sisa      = 0;
                        $PaguKegiatan_satker     = 0;
                        $BelanjaKegiatan_satker  = 0;
                        $SisaKegiatan_satker     = 0;

                    ?>

                        <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $output => $valoutput): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $valoutput->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan => $valkegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $valkegiatan->SubDataDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dana => $valdana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $valdana->SubDataAkun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun => $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $PaguAkhir              += $detil->PaguAkhir ?? '0';
                        $Realisasi              += $detil->Realisasi ?? '0';
                        $Sisa                   += $detil->Sisa ?? '0';
                    ?>

                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center"><?php echo e($valkegiatan->KodeKegiatan); ?>.<?php echo e($valoutput->KodeOutput); ?>.<?php echo e($detil->Kode); ?></th>
                        <th class="text-start"><?php echo e($detil->Keterangan); ?></th>
                        <th class="text-center"><?php echo e($detil->SumberDana); ?></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($detil->PaguAkhir)); ?></th>
                        <th></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($detil->Realisasi)); ?></th>
                        <th></th>
                        <th class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</th>
                        <th class="text-end"><?php echo e(($detil->Sisa)); ?></th>
                    </tr>
                    <?php
                        $PaguKegiatan    = 0;
                        $BelanjaKegiatan = 0;
                        $SisaKegiatan    = 0;

                    ?>
                    <?php $__currentLoopData = $detil->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($kegiatan->Uraian!='0'): ?>
                    <?php
                    $PaguKegiatan          += $kegiatan->PaguKegiatan;
                    $BelanjaKegiatan       += $kegiatan->BelanjaKegiatan;
                    $SisaKegiatan          += $kegiatan->SisaKegiatan;

                    $PaguAkhirSub_kegiatan += $kegiatan->PaguKegiatan;
                    $RealisasiSub_kegiatan += $kegiatan->BelanjaKegiatan;
                    $SisaSub_kegiatan      += $kegiatan->SisaKegiatan;


                    ?>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-end"></td>
                        <td class="text-start">
                            <?php echo e($kegiatan->Uraian); ?>

                                <?php if(!empty($kegiatan->Catatan)): ?><br>
                                <small><i><?php echo e($kegiatan->Catatan); ?></i>
                                </small>
                                <?php endif; ?></td>
                        <td class="text-center"><?php echo e($detil->SumberDana); ?></td>
                        <td class="text-end"><span class="nowrap"><?php echo e(($kegiatan->VolumePagu)); ?> <?php echo e($kegiatan->SatuanPagu); ?></span></td>
                        <td class="text-end"><?php echo e(($kegiatan->PaguKegiatan)); ?></td>
                        <td class="text-center"></td>

                        <td class="text-end"><span class="nowrap"><?php if(!empty($kegiatan->VolumeBelanja)): ?><?php echo e(($kegiatan->VolumeBelanja)); ?> <?php echo e($kegiatan->SatuanBelanja); ?> <?php endif; ?></span></td>
                        <td class="text-end"><?php echo e(($kegiatan->BelanjaKegiatan)); ?></td>
                        <td class="text-start"><small class="nowrap"><?php echo nl2br($kegiatan->Tglsp2d); ?></small></td>

                        <td class="text-center"><?php echo e(Persen($kegiatan->PersenKegiatan)); ?>%</td>
                        <td class="text-end"><?php echo e(($kegiatan->SisaKegiatan)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $selisih_pagu      = $PaguAkhir-$PaguKegiatan;
                        $selisih_realisasi = $Realisasi-$BelanjaKegiatan;
                        $total_selisih     = $selisih_pagu+$selisih_realisasi;


                    ?>
                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th></th>
                        <th class="text-start">SUB JUMLAH</th>
                        <th class="text-center text-danger"><?php if($total_selisih!=0): ?> <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Terdapat Perbedaan Data UPT dan SPAN" class="icofont icofont-warning-alt"></i> <?php endif; ?></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($PaguKegiatan)); ?></th>
                        <th></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($BelanjaKegiatan)); ?></th>
                        <th></th>
                        <th class="text-center"><?php echo e(Persen(divnum($BelanjaKegiatan,$PaguKegiatan)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($SisaKegiatan)); ?></th>
                    </tr>

                    <?php
                        $PaguKegiatan_satker    += $PaguKegiatan;
                        $BelanjaKegiatan_satker += $BelanjaKegiatan;
                        $SisaKegiatan_satker    += $SisaKegiatan;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="border-top-primary table-info">
                        <th class="text-center"></th>
                        <th></th>
                        <th class="text-start">JUMLAH SATKER</th>
                        <th class="text-center text-danger"></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($PaguKegiatan_satker)); ?></th>
                        <th></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($BelanjaKegiatan_satker)); ?></th>
                        <th></th>
                        <th class="text-center"><?php echo e(Persen(divnum($BelanjaKegiatan_satker,$PaguKegiatan_satker)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($SisaKegiatan_satker)); ?></th>
                    </tr>

                    <?php
                        $TotalPaguAkhir          += $PaguAkhir;
                        $TotalRealisasi          += $Realisasi;
                        $TotalSisa               += $Sisa;
                    ?>

                    

                    <?php
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    $SisaSub      += $Sisa;
                    ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="table-danger">
                        <th class="text-center"></th>
                        <th class="text-start" colspan="3">JUMLAH PROPINSI <?php echo e($item->NamaHeader); ?></th>
                        <th class="text-start"></th>
                        <th class="text-end"><?php echo e(($PaguAkhirSub_kegiatan)); ?></th>
                        <th class="text-start"></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($RealisasiSub_kegiatan)); ?></th>
                        <th></th>
                        <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub_kegiatan,$PaguAkhirSub_kegiatan)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($SisaSub_kegiatan)); ?></th>
                    </tr>

                    <?php
                    $PaguAkhirSub             = 0;
                    $RealisasiSub             = 0;
                    $SisaSub                  = 0;
                    $TotalPaguAkhir_kegiatan += $PaguAkhirSub_kegiatan;
                    $TotalRealisasi_kegiatan += $RealisasiSub_kegiatan;
                    $TotalSisa_kegiatan      += $SisaSub_kegiatan;

                    ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start" colspan="3">JUMLAH RAYA UPT</th>
                        <th class="text-start"></th>
                        <th class="text-end"><?php echo e(($TotalPaguAkhir_kegiatan)); ?></th>
                        <th class="text-start"></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($TotalRealisasi_kegiatan)); ?></th>
                        <th></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi_kegiatan,$TotalPaguAkhir_kegiatan)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($TotalSisa_kegiatan)); ?></th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start" colspan="3">JUMLAH RAYA SPAN</th>
                        <th class="text-start"></th>
                        <th class="text-end"><?php echo e(($TotalPaguAkhir)); ?></th>
                        <th class="text-start"></th>
                        <th></th>
                        <th class="text-end"><?php echo e(($TotalRealisasi)); ?></th>
                        <th></th>
                        <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                        <th class="text-end"><?php echo e(($TotalSisa)); ?></th>
                    </tr>

            </table>

            <?php endif; ?>

            <?php break; ?>
        <?php default: ?>

    <?php endswitch; ?>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-excell-covid.blade.php ENDPATH**/ ?>