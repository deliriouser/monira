<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Rangking</li>
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
                            <h5>Rangking Daya Serap Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'eselon1','segment'=>'ranking'])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'eselon1','segment'=>'ranking'])); ?>">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm table-striped">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">NAMA SATKER</th>
									<th class="text-start">PROPINSI</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
								</tr>
							</thead>
							<tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr <?php if($item->KodeSatker==Auth:: user()->kdsatker): ?> class="bg-danger" <?php endif; ?>>
									<td class="text-center"><?php echo e($loop->iteration); ?>

                                        <?php if($item->KodeSatker==Auth:: user()->kdsatker): ?>
                                            <a name="<?php echo e(Auth:: user()->kdsatker); ?>"></a>
                                         <?php endif; ?>
                                    </td>
									<td class="text-center"><?php echo e($item->KodeSatker); ?></td>
									<td class="text-start"><?php echo e($item->NamaSatuanKerja); ?></td>
									<td class="text-start"><?php echo e($item->WilayahName); ?></td>
									<td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
									<td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
									<td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
									<td class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
								</tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
							<tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
									<th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
									<th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
									<th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
									<th class="text-end"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
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
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/ranking-satker.blade.php ENDPATH**/ ?>