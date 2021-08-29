<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item active">Gembok</li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				
                    <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Status Prognosa Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="lock" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/prognosa/status',['status'=>'1', 'id'=>Crypt::encrypt(1),'what'=>'eselon1'])); ?>">Kunci</a>
                                    <a onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/prognosa/status',['status'=>'0', 'id'=>Crypt::encrypt(0),'what'=>'eselon1'])); ?>">Buka</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadrpd" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-end">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-end">SISA</th>
									<th class="text-end">AKSI</th>
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
                                    $TotalSisa      = 0;
                                    $SisaSub        = 0;

                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="8"><b><?php echo e($item->NamaHeader); ?></b></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kunci Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/prognosa/status',['status'=>'1', 'id'=>Crypt::encrypt($item->KodeHeader),'what'=>'wilayah'])); ?>" class="text-danger static" href="#"><i class="icon-lock"></i></a>
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Buka Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/prognosa/status',['status'=>'0', 'id'=>Crypt::encrypt($item->KodeHeader),'what'=>'wilayah'])); ?>" class="text-primary static" href="#"><i class="icon-unlock"></i></a>
                                        </div>

                                    </td>
                                </tr>

                                <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><?php echo e($key); ?></td>
                                        <td class="text-start">
                                            <?php if(isset($value->KodeSubHeader)): ?>
                                                <?php echo e($value->NamaSubHeader); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end"><?php echo e(RP($value->PaguAkhir)); ?></td>
                                        <td class="text-end"><?php echo e(RP($value->Realisasi)); ?></td>
                                        <td class="text-center"><?php echo e(Persen($value->Persen)); ?>%</td>
                                        <td class="text-end"><?php echo e(RP($value->Prognosa)); ?></td>
                                        <td class="text-center"><?php echo e(Persen($value->PersenPrognosa)); ?>%</td>
                                        <td class="text-end"><?php echo e(RP($value->PaguAkhir-$value->Prognosa)); ?></td>
                                        <td class="text-center">
                                            <?php if($value->IsLockPrognosa==1): ?>
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Buka Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/prognosa/status',['status'=>'0', 'id'=>Crypt::encrypt($value->KodeSubHeader),'what'=>'prognosa'])); ?>" class="text-danger static" href="#"><i class="icon-lock"></i></a>
                                            <?php else: ?>
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kunci Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/prognosa/status',['status'=>'1', 'id'=>Crypt::encrypt($value->KodeSubHeader),'what'=>'prognosa'])); ?>" class="text-primary static" href="#"><i class="icon-unlock"></i></a>
                                            <?php endif; ?>
                                        </td>

                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						</table>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/prognosa-locking.blade.php ENDPATH**/ ?>