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
						<table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-end">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-end">SISA</th>
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
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="8"><b><?php echo e($item->NamaHeader); ?></b></td>
                                </tr>

                                <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><b><?php echo e($key); ?></b></td>
                                        <td class="text-start" colspan="7"><b>
                                            <?php if(isset($value->KodeSubHeader)): ?>
                                                <?php echo e($value->NamaSubHeader); ?>

                                            <?php endif; ?>
                                        </b></td>
                                </tr>
                                <?php
                                    $PaguAwal  = 0;
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Prognosa  = 0;
                                    $Sisa  = 0;

                                ?>

                                <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // $PaguAwal  += $detil->PaguAwal ?? '0';
                                    $PaguAkhir += $detil->PaguAkhir ?? '0';
                                    $Realisasi += $detil->Realisasi ?? '0';
                                    $Prognosa  += $detil->Prognosa;
                                    $Sisa      += $detil->PaguAkhir-$detil->Prognosa;
                                ?>

                                <tr>
									<td class="text-center"></td>
									<td class="text-center"><?php echo e($detil->Kode); ?></td>
									<td class="text-start"><?php echo e($detil->Keterangan); ?></td>
									<td class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></td>
									<td class="text-end"><?php echo e(RP($detil->Realisasi)); ?></td>
									<td class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</td>
									<td class="text-end"><?php echo e(RP($detil->Prognosa)); ?></td>
									<td class="text-center"><?php echo e(Persen($detil->PersenPrognosa)); ?>%</td>
									<td class="text-end"><?php echo e(RP($detil->PaguAkhir-$detil->Prognosa)); ?></td>
								</tr>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    // $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                    $TotalPrognosa  += $Prognosa;
                                    $TotalSisa      += $Sisa;

                                ?>

                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                                    <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($Prognosa)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($Prognosa,$PaguAkhir)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($Sisa)); ?></th>
                                </tr>

                                <?php
                                // $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                $PrognosaSub  += $Prognosa;
                                $SisaSub      += $Sisa;
                                ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <tr class="table-info">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH PROPINSI</th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhirSub)); ?></th>
                                    <th class="text-end"><?php echo e(RP($RealisasiSub)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($PrognosaSub)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($SisaSub)); ?></th>
                                </tr>

                                <?php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                $PrognosaSub  = 0;
                                $SisaSub      = 0;
                                ?>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($TotalPrognosa)); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
								</tr>

						</table>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/prognosa-three-level.blade.php ENDPATH**/ ?>