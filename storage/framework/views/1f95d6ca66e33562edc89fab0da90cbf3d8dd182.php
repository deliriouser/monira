<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Prognosa Satker</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item active">Satker</li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
                    <div class="table-responsive">
						<table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-center">PROGNOSA</th>
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
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="8"><b><?php echo e($item->NamaHeader); ?></b></td>
                                </tr>

                                <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
                                        <td class="text-center"<?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><b><?php echo e($key); ?></b></td>
                                        <td class="text-start" colspan="8"><b>
                                            <?php if(isset($value->KodeSubHeader)): ?>
                                                <?php echo e($value->NamaSubHeader); ?>

                                            <?php endif; ?>
                                        </b></td>
                                </tr>
                                <?php $__currentLoopData = $value->SubDataSecond; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyDetail => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-success">
                                        <td class="text-center"<?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><b><?php echo e($keyDetail); ?></b></td>
                                        <td class="text-start" colspan="8"><b>
                                            <?php if(isset($detail->KodeSubHeaderSub)): ?>
                                                <?php echo e($detail->NamaSubHeaderSub); ?>

                                            <?php endif; ?>
                                        </b></td>
                                </tr>
                                <?php
                                    $PaguAwal     = 0;
                                    $PaguAkhir    = 0;
                                    $Realisasi    = 0;
                                ?>

                                <?php $__currentLoopData = $detail->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $PaguAwal  += $detil_data->PaguAwal ?? '0';
                                    $PaguAkhir += $detil_data->PaguAkhir ?? '0';
                                    $Realisasi += $detil_data->Realisasi ?? '0';
                                ?>

                                <tr>
									<td class="text-center"></td>
									<td class="text-center"><?php echo e($detil_data->Kode); ?></td>
									<td class="text-start"><?php echo e($detil_data->Keterangan); ?></td>
									<td class="text-end"><?php echo e(RP($detil_data->PaguAwal)); ?></td>
									<td class="text-end"><?php echo e(RP($detil_data->PaguAkhir)); ?></td>
									<td class="text-end"><?php echo e(RP($detil_data->Realisasi)); ?></td>
									<td class="text-center"><?php echo e(Persen($detil_data->Persen)); ?>%</td>
                                    <td></td>
								</tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                $TotalPaguAwal  += $PaguAwal;
                                $TotalPaguAkhir += $PaguAkhir;
                                $TotalRealisasi += $Realisasi;
                            ?>

                            <tr class="border-top-primary">
                                <th class="text-center"></th>
                                <th class="text-start"></th>
                                <th class="text-start">JUMLAH</th>
                                <th class="text-end"><?php echo e(RP($PaguAwal)); ?></th>
                                <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                                <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                                <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                                <th></th>
                            </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                                <?php
                                $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <tr class="table-info">
									<th class="text-center"></th>
									<th class="text-start">JUMLAH</th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($PaguAwalSub)); ?></th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhirSub)); ?></th>
                                    <th class="text-end"><?php echo e(RP($RealisasiSub)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)); ?>%</th>
                                    <th></th>
                                </tr>

                                <?php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                ?>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">TOTAL JUMLAH RAYA</th>
									<th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                                    <th></th>
								</tr>

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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/prognosa-satker-four.blade.php ENDPATH**/ ?>