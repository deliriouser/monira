<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item active"><?php echo e($segment); ?></li>
<li class="breadcrumb-item"><?php echo e(ucfirst($unit)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Prognosa <?php echo e($segment); ?> Per <?php echo e($unit ?? ''); ?></h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPrognosa',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment')])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPrognosa',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment')])); ?>">Pdf</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
				
                    <div class="table-responsive">

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-start">KODE</th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-end">PAGU</th>
                                        <th class="text-end">REALISASI</th>
                                        <th class="text-center">%</th>
                                        <th class="text-end">PROGNOSA</th>
                                        <th class="text-center">%</th>
                                        <th class="text-end">SISA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($item['Kode']); ?></td>
                                        <td class="text-start"><?php echo e($item['Keterangan']); ?></td>
                                        <td class="text-end"><?php echo e(RP($item['Pagu'])); ?></td>
                                        <td class="text-end"><?php echo e(RP($item['Realisasi'])); ?></td>
                                        <td class="text-center"><?php echo e(Persen($item['Persen'])); ?>%</td>
                                        <td class="text-end"><?php echo e(RP($item['Prognosa'])); ?></td>
                                        <td class="text-center"><?php echo e(Persen($item['PersenPrognosa'])); ?>%</td>
                                        <td class="text-end"><?php echo e(RP($item['Pagu']-$item['Prognosa'])); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-start">JUMLAH</th>
                                        <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                                        <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($data->sum('Prognosa'))); ?></th>
                                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($data->sum('Pagu')-$data->sum('Prognosa'))); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/prognosa-one-level.blade.php ENDPATH**/ ?>