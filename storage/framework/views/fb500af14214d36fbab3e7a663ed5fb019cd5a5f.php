<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Belanja</li>
<li class="breadcrumb-item"><?php echo e(ucfirst($unit)); ?></li>
<li class="breadcrumb-item active"><?php echo e($segment); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0"><?php echo e($segment); ?> Per <?php echo e($unit ?? ''); ?>

                      </h5>

                      <div class="card-header-right-icon">
                        <div class="row">
                            <div class="col-8">
                                <div class="dropdown">
                                <button class="btn dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo e(nameofmonth(Request::route('month'))); ?></button>
                                <div class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownMenuButton" style="">
                                    <?php for($i=1;$i<=12;$i++): ?>
                                    <?php if($i<= DATE('n')): ?>
                                    <a class="dropdown-item <?php if($i==Request::route('month')): ?> bg-danger text-white <?php endif; ?>" href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>Request::route('unit'),'segment'=>Request::route('segment'), 'month'=>$i])); ?>"><?php echo e(nameofmonth($i)); ?></a>
                                    <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportSpending',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Excell</a>
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportSpending',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>

                    </div>
                </div>
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
									<td class="text-center"></td>
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
                            <tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/belanja-two-level.blade.php ENDPATH**/ ?>