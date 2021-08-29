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
<li class="breadcrumb-item active">Propinsi Satker</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Rangking Daya Serap Propinsi Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'satker','segment'=>'ranking'])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'satker','segment'=>'ranking'])); ?>">PDF</a>
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
									<th class="text-start">KODE</th>
									<th class="text-start">NAMA SATKER</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
								</tr>
							</thead>
							<tbody>
                                <?php
                                    $TotalPaguAwal=0;
                                    $TotalPaguAkhir=0;
                                    $TotalRealisasi=0;
                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
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
									<td class="text-center"><?php echo e($noSatker=$noSatker+1); ?></td>
									<td class="text-center"><?php echo e($satker->Kode); ?></td>
									<td class="text-start"><?php echo e($satker->Keterangan); ?></td>
									<td class="text-end"><?php echo e(RP($satker->PaguAwal)); ?></td>
									<td class="text-end"><?php echo e(RP($satker->PaguAkhir)); ?></td>
									<td class="text-end"><?php echo e(RP($satker->Realisasi)); ?></td>
									<td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
								</tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end"><?php echo e(RP($PaguAwal)); ?></th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                                    <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                                </tr>

                                <?php
                                    $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
								</tr>
						</table>
					</div>
				
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/ranking-propinsi-satker.blade.php ENDPATH**/ ?>