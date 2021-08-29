<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Belanja Per <?php echo e($segment ?? ''); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Belanja</li>
<li class="breadcrumb-item active"><?php echo e($segment ?? ''); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
                    <div class="table-responsive">
						<table class="table table-sm table">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
								</tr>
							</thead>
							<tbody>

                                
                                <?php
                                    $Kode    = null;
                                    $KodeSub = null;
                                    $KodeSubSub = null;
                                ?>

                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($item->KodeHeader)): ?>
                                    <?php if($item->KodeHeader!=$Kode): ?>
                                    <tr class="table-danger">
                                        <th class="text-center"><?php echo e($item->KodeHeader); ?></th>
                                        <th class="text-start text-bold" colspan="5"><?php echo e(strtoupper($item->NamaHeader)); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(isset($item->KodeSubHeader)): ?>
                                <?php if($item->KodeSubHeader!=$KodeSub): ?>
                                <tr class="table-warning">
                                    <th class="text-center"><?php echo e($item->KodeSubHeader); ?></td>
                                    <th class="text-start text-bold" colspan="5"><?php echo e(strtoupper($item->NamaSubHeader)); ?></th>
                                </tr>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(isset($item->KodeSubHeaderSub)): ?>
                                <?php if($item->KodeSubHeaderSub!=$KodeSubSub): ?>
                                <tr class="table-primary">
                                    <th class="text-center"><?php echo e($item->KodeSubHeaderSub); ?></td>
                                    <th class="text-start text-bold" colspan="5"><?php echo e(strtoupper($item->NamaSubHeaderSub)); ?></th>
                                </tr>
                                <?php endif; ?>
                                <?php endif; ?>

								<tr>
									<td class="text-center"><?php echo e($item->Kode); ?></td>
									<td class="text-start"><?php echo e($item->Keterangan); ?></td>
									<td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
									<td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
									<td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
									<td class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
								</tr>


                                <?php if(isset($item->KodeHeader)): ?>
                                    <?php
                                        $Kode    = $item->KodeHeader;
                                    ?>
                                <?php endif; ?>
                                <?php if(isset($item->KodeSubHeader)): ?>
                                    <?php
                                        $KodeSub = $item->KodeSubHeader;
                                    ?>
                                <?php endif; ?>
                                <?php if(isset($item->KodeSubHeaderSub)): ?>
                                    <?php
                                        $KodeSubSub = $item->KodeSubHeaderSub;
                                    ?>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
							<tfoot class="table-primary">
								<tr>
                                    <th></th>
									<th class="text-center">JUMLAH</th>
									<th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
									<th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
									<th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
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
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/belanja-twolevel.blade.php ENDPATH**/ ?>