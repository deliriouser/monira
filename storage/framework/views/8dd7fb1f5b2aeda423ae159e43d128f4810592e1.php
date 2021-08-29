<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/tour.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Monitoring Covid</li>
<li class="breadcrumb-item active">Satker</li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Monitoring Penanganan Covid-19</h5>
                        </div>
                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <?php $__currentLoopData = $bulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($item->id<= DATE('n')): ?>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reporting',['type'=>'pdf','unit'=>'satker','segment'=>Request::route('segment'), 'month'=>$item->id])); ?>"><?php echo e($item->BulanName); ?></a>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadSK" id="page-all">
							<thead class="bg-primary">
								<tr valign="middle">
									<th class="text-center" rowspan="2">NO</th>
									<th class="text-start" rowspan="2">KODE</th>
									<th class="text-start" rowspan="2">KETERANGAN</th>
									<th class="text-center" rowspan="2">DANA</th>
									<th class="text-center" colspan="2">PAGU</th>
									<th class="text-center" colspan="2">REALISASI</th>
									<th class="text-center" rowspan="2">%</th>
									<th class="text-center" rowspan="2">Aksi</th>
								</tr>
                                <tr>
									<th class="text-center">VOL</th>
									<th class="text-center">RUPIAH</th>
									<th class="text-center">VOL</th>
									<th class="text-center">RUPIAH</th>
                                </tr>
							</thead>
							<tbody>
                                <?php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    // $TotalPrognosa = 0;
                                    $PaguAwalSub  = 0;
                                    $PaguAkhirSub = 0;
                                    $RealisasiSub = 0;
                                    // $PrognosaSub = 0;
                                    $TotalPaguAkhirCovid = 0;
                                    $TotalRealisasiCovid = 0;

                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="9"><b><?php echo e(strtoupper($item->NamaHeader)); ?></b></td>
                                </tr>
                                <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
                                        <td class="text-center"<?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><b><?php echo e($key); ?></b></td>
                                        <td class="text-start" colspan="8"><b>
                                            <?php if(isset($value->KodeSubHeader)): ?>
                                                <?php echo e(strtoupper($value->NamaSubHeader)); ?>

                                            <?php endif; ?>
                                        </b></td>
                                </tr>
                                <?php
                                    $PaguAwal     = 0;
                                    $PaguAkhir    = 0;
                                    $Realisasi    = 0;
                                    // $Prognosa    = 0;
                                ?>
                                <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detilsub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $detilsub->SubDataDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $PaguAwal  += $detil->PaguAwal ?? '0';
                                    $PaguAkhir += $detil->PaguAkhir ?? '0';
                                    $Realisasi += $detil->Realisasi ?? '0';
                                    // $Prognosa  += $detil->Prognosa ?? '0';
                                ?>
                                <tr valign="middle" class="table-success">
									<th class="text-center"><?php echo e($loop->iteration); ?></th>
									<th class="text-center"><?php echo e($detil->Kode); ?></th>
									<th class="text-start"><?php echo e(($detil->Keterangan)); ?></th>
									<th class="text-center"><?php echo e(($detil->NamaDana)); ?></th>
                                    <th></th>
									<th class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></th>
                                    <th></th>
									<th class="text-end"><?php echo e(RP($detil->Realisasi)); ?></th>
									<th class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</th>
                                    <th class="text-center">
                                        <a data-intro="Tambah Uraian Kegiatan" title="Tambah Uraian Kegiatan" dana="<?php echo e($detil->NamaDana); ?>" akun="<?php echo e($detil->Kode); ?>. <?php echo e($detil->Keterangan); ?>" output="<?php echo e($key); ?>. <?php echo e($value->NamaSubHeader); ?>" kegiatan="<?php echo e($item->KodeHeader); ?>. <?php echo e($item->NamaHeader); ?>" pagu="<?php echo e($detil->PaguAkhir); ?>"  id="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" href="#" class="open-modal-monitoring text-primary static" action="insertKegiatanCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                    </th>
								</tr>
                                <?php
                                    $totalPaguKegiatan    = 0;
                                    $totalBelanjaKegiatan = 0;
                                    $number               = 0;
                                ?>
                                <?php $__currentLoopData = $detil->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uraian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($uraian->PaguKegiatan)): ?>
                                <?php
                                    $sisa = $uraian->PaguKegiatan-$uraian->BelanjaKegiatan;
                                ?>
                                <tr valign="middle">
                                    <td></td>
									<td class="text-center"><?php echo e($loop->iteration); ?></td>
									<td class="text-start"><?php echo e($uraian->Uraian); ?> <?php if(!empty($uraian->Catatan)): ?><br><small><i><?php echo e($uraian->Catatan); ?></i></small><?php endif; ?></td>
                                    <td></td>
									<td class="text-center"><span class="nowrap"><?php echo e($uraian->VolumePagu); ?> <?php echo e($uraian->SatuanPagu); ?></span></td>
									<td class="text-end"><?php echo e(RP($uraian->PaguKegiatan)); ?></td>
									<td class="text-center"><span class="nowrap"><?php echo e($uraian->VolumeBelanja); ?> <?php echo e($uraian->SatuanBelanja); ?></span></td>
									<td class="text-end"><?php echo e(RP($uraian->BelanjaKegiatan)); ?></td>
                                    <td class="text-center"><?php echo e(Persen(divnum($uraian->BelanjaKegiatan,$uraian->PaguKegiatan)*100)); ?>%</td>
									<td class="text-center">
                                        <div class="btn-group">
                                            <a data-intro="Edit Data Uraian Kegiatan" title="Edit Data" dana="<?php echo e($uraian->VolumePagu); ?> <?php echo e($uraian->SatuanPagu); ?>" id="<?php echo e($uraian->Guid); ?>" output="<?php echo e($uraian->SatuanPagu); ?>" kegiatan="<?php echo e($uraian->Uraian); ?>" pagu="<?php echo e($uraian->PaguKegiatan); ?>"  akun="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" sisa="<?php echo e($sisa); ?>" href="#" class="mr-xl-5 open-modal-monitoring text-primary static" action="updateKegiatanCovid" style="font-size:20px;"><i class="icofont icofont-earth"></i></a>
                                            <a data-intro="Tambah Realisasi Kegiatan" title="Input Realisasi" dana="<?php echo e($uraian->VolumePagu); ?> <?php echo e($uraian->SatuanPagu); ?>" akun="<?php echo e($uraian->Guid); ?>" output="<?php echo e($uraian->SatuanPagu); ?>" kegiatan="<?php echo e($uraian->Uraian); ?>" pagu="<?php echo e($uraian->PaguKegiatan); ?>"  id="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" sisa="<?php echo e($sisa); ?>" href="#" class="open-modal-monitoring text-success static" action="insertRealisasiCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                            <a data-intro="Lihat Rincian Realisasi" title="Lihat Rincian Realisasi" href="#" id="<?php echo e($uraian->Guid); ?>"class="mr-xl-5 open-modal-monitoring text-primary static" action="dataRealisasiCovid" style="font-size:20px;"><i class="fa fa-info-circle"></i></a>

                                            <a data-intro="Hapus Kegiatan" title="Hapus Kegiatan" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/monitoring/status',['status'=>'0', 'id'=> $uraian->Guid,'what'=>'kegiatan'])); ?>" class="text-danger static" style="font-size:20px;"><i class="fa fa-times-circle"></i></a>
                                          </div>
                                    </td>
                                </tr>
                                <?php
                                    $totalPaguKegiatan += $uraian->PaguKegiatan;
                                    $totalBelanjaKegiatan  += $uraian->BelanjaKegiatan;
                                ?>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $TotalPaguAwal       += $PaguAwal;
                                    $TotalPaguAkhirCovid += $totalPaguKegiatan;
                                    $TotalRealisasiCovid += $totalBelanjaKegiatan;
                                    // $TotalPrognosa  += $Prognosa;
                                    $selisih_pagu = $detil->PaguAkhir-$totalPaguKegiatan;
                                    $selisih_realisasi = $detil->Realisasi-$totalBelanjaKegiatan;
                                    $number+=$number+1;
                                ?>
                                <tr valign="middle" class="border-top-primary <?php if($selisih_pagu>0 OR $selisih_realisasi>0): ?> bg-danger <?php else: ?> text-white bg-success <?php endif; ?>">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start text-white">JUMLAH</th>
                                    <th class="text-end"></th>
                                    <th class="text-end"></th>
                                    <th class="text-end text-white"><?php echo e(RP($totalPaguKegiatan)); ?></th>
                                    <th class="text-end"></th>
                                    <th class="text-end text-white"><?php echo e(RP($totalBelanjaKegiatan)); ?></th>
                                    <th class="text-center text-white"><?php echo e(Persen(divnum($totalBelanjaKegiatan,$totalPaguKegiatan)*100)); ?>%</th>
                                    <th class="text-center"><?php if($selisih_pagu>0 OR $selisih_realisasi>0): ?> <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  <?php else: ?>
                                        <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                                        <?php endif; ?></th>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $TotalPaguAkhir      += $PaguAkhir;
                                    $TotalRealisasi      += $Realisasi;

                                $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                // $PrognosaSub  += $Prognosa;
                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                // $PrognosaSub  = 0;


                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $selisih_pagu_akhir      = $TotalPaguAkhir-$TotalPaguAkhirCovid;
                                    $selisih_realisasi_akhir = $TotalRealisasi-$TotalRealisasiCovid;
                                ?>
                                 <tfoot>
                                    <tr valign="middle" class="table-warning">
                                        <th class="text-center"></th>
                                        <th class="text-start"></th>
                                        <th class="text-start">SELISIH DATA SPAN DAN UPT</th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th class="text-end"><?php echo e(RP($selisih_pagu_akhir)); ?></th>
                                        <th class="text-end"></th>
                                        <th class="text-end"><?php echo e(RP($selisih_realisasi_akhir)); ?></th>
                                        <th class="text-center text-white"></th>
                                        <th class="text-center">
                                        </th>
                                    </tr>
                                </tfoot>
                                <tfoot class="<?php if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0): ?> bg-danger <?php else: ?> text-white bg-success <?php endif; ?>">
								<tr valign="middle">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start text-white">JUMLAH RAYA</th>
									<th class="text-end"></th>
									<th class="text-end"></th>
									<th class="text-end text-white"><?php echo e(RP($TotalPaguAkhirCovid)); ?></th>
									<th class="text-end"></th>
									<th class="text-end text-white"><?php echo e(RP($TotalRealisasiCovid)); ?></th>
									<th class="text-center text-white"><?php echo e(Persen(divnum($TotalRealisasiCovid,$TotalPaguAkhirCovid)*100)); ?>%</th>
                                    <th class="text-center">
                                        <?php if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0): ?> <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  <?php else: ?>
                                        <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                                        <?php endif; ?>

                                    </th>
								</tr>

                                </tfoot>

						</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/tour/intro.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/tour/intro-init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/monitoring-covid.blade.php ENDPATH**/ ?>