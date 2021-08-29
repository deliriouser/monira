<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Padat Karya</li>
<li class="breadcrumb-item"><?php echo e(ucfirst($unit ?? '')); ?></li>
<li class="breadcrumb-item active"><?php echo e($segment); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0"><?php echo e($segment); ?> Padat Karya Per <?php echo e($unit ?? ''); ?>

                      </h5>
                      <div class="card-header-right-icon">
                        <div class="row">

                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPadatKarya',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Excell</a>
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPadatKarya',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                        <div class="table-responsive">
                            <table class="table table-sm" id="card">
                                <thead class="bg-primary">
                                    <tr class="bg-primary">
                                        <th class="text-center" colspan="5">TARGET</th>
                                        <th class="text-center" rowspan="2"></th>
                                        <th class="text-center" colspan="5">REALISASI</th>
                                    </tr>
                                    <tr>
                                        <th class="col-1 text-center">TOTAL PAGU KEGIATAN`</th>
                                        <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                                        <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                                        <th class="col-1 text-center">TOTAL REALISASI</th>
                                        <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                                        <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <tr>
                                        <td class="text-end"><?php echo e(RP($item->Target_TotalPagu)); ?></td>
                                        <td class="text-end"><?php echo e(RP($item->Target_TotalBiayaLain)); ?></td>
                                        <td class="text-end"><?php echo e(RP($item->Target_TotalBiayaUpah)); ?></td>
                                        <td class="text-center"><?php echo e(RP($item->Target_JumlahOrang)); ?></td>
                                        <td class="text-center"><?php echo e(RP($item->Target_JumlahOrangHari)); ?></td>
                                        <td></td>
                                        <td class="text-end"><?php echo e(RP($item->Daser_TotalPagu)); ?></td>
                                        <td class="text-end"><?php echo e(RP($item->Daser_TotalBiayaLain)); ?></td>
                                        <td class="text-end"><?php echo e(RP($item->Daser_TotalBiayaUpah)); ?></td>
                                        <td class="text-center"><?php echo e(RP($item->Daser_JumlahOrang)); ?></td>
                                        <td class="text-center"><?php echo e(RP($item->Daser_JumlahOrangHari)); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/padatkarya-rekap-one-level.blade.php ENDPATH**/ ?>