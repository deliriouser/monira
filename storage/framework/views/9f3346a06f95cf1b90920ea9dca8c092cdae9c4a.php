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
                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Rangking" class="openModal" what="filter" href="#"><i data-feather="grid"></i></a>
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportHarian',['type'=>'excell','top'=>$top,'bottom'=>$bottom])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportHarian',['type'=>'pdf','top'=>$top,'bottom'=>$bottom])); ?>">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-center">KODE</th>
									<th class="text-center">NAMA SATKER</th>
									<th class="text-center">PAGU</th>
									<th class="text-center">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-center">SISA</th>
									<th class="text-center">PROGNOSA</th>
								</tr>
							</thead>
							<tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr class="<?php if($item->Persen>$top): ?> table-success <?php elseif($item->Persen>$bottom AND $item->Persen<$top): ?> table-warning <?php else: ?> table-danger <?php endif; ?>">
									<td class="text-center"><?php echo e($loop->iteration); ?></td>
									<td class="text-center"><?php echo e($item->KodeSatker); ?></td>
									<td class="text-start"><?php echo e($item->NamaSatuanKerja); ?></td>
									<td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
									<td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
									<td class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
									<td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
									<td class="text-center">
                                        <?php if($item->Persen_prognosa>0 and $item->Persen_prognosa<=100): ?>
                                        <?php echo e(Persen($item->Persen_prognosa)); ?>%
                                        <?php elseif($item->Persen_prognosa>100): ?>
                                        <?php echo e(Persen($item->Persen_satker)); ?>%
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </td>
								</tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
							<tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
									<th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
									<th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($data->sum('Sisa'))); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)); ?>%</th>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/ranking-satker-harian.blade.php ENDPATH**/ ?>