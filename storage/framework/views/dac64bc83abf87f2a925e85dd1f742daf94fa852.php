<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

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
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Prognosa Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPrognosa',['type'=>'pdf','unit'=>'satker','segment'=>'prognosa'])); ?>">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                            </a>

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
									<th class="text-center">DANA</th>
									<th class="text-center">PAGU</th>
									<th class="text-center">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-center">SISA</th>
									<th class="text-center">Aksi</th>
								</tr>

							</thead>
							<tbody>

                                <?php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    $TotalPrognosa  = 0;
                                    $TotalSisa      = 0;
                                    $PaguAwalSub    = 0;
                                    $PaguAkhirSub   = 0;
                                    $RealisasiSub   = 0;
                                    $PrognosaSub    = 0;
                                    $SisaSub        = 0;

                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="8"><b><?php echo e(strtoupper($item->NamaHeader)); ?></b></td>
                                </tr>

                                <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
                                        <td class="text-center"<?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><b><?php echo e($key); ?></b></td>
                                        <td class="text-start" colspan="7"><b>
                                            <?php if(isset($value->KodeSubHeader)): ?>
                                                <?php echo e(strtoupper($value->NamaSubHeader)); ?>

                                            <?php endif; ?>
                                        </b></td>
                                </tr>
                                <?php
                                    $PaguAwal  = 0;
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Prognosa  = 0;
                                    $Sisa      = 0;

                                ?>

                                <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $PaguAwal  += $detil->PaguAwal ?? '0';
                                    $PaguAkhir += $detil->PaguAkhir ?? '0';
                                    $Realisasi += $detil->Realisasi ?? '0';
                                    $Prognosa  += $detil->Prognosa ?? '0';
                                    $Sisa       = $PaguAkhir-$Prognosa;
                                ?>

                                <tr valign="middle" class="<?php if($detil->Persen>100): ?> bg-danger <?php endif; ?>">
									<td class="text-center"><?php echo e($loop->iteration); ?></td>
									<td class="text-center"><?php echo e($detil->Kode); ?></td>
									<td class="text-start"><?php echo e($detil->Keterangan); ?>

                                        <?php if(isset($detil->Justifikasi)): ?>
                                        <br><small><i>Justifikasi : <?php echo e($detil->Justifikasi); ?></i></small>
                                        <?php endif; ?>
                                    </td>
									<td class="text-center"><?php echo e(($detil->NamaDana)); ?></td>
									<td class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></td>
									<td class="text-end"><?php echo e(RP($detil->Prognosa)); ?></td>
									<td class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</td>
                                    <td class="text-end">
                                        <?php echo e(RP($detil->PaguAkhir-$detil->Prognosa)); ?>

                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                        <?php if(isset($detil->Prognosa) AND $detil->Prognosa>0): ?>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Data" dana="<?php echo e($detil->NamaDana); ?>" akun="<?php echo e($detil->Kode); ?>. <?php echo e($detil->Keterangan); ?>" output="<?php echo e($key); ?>. <?php echo e($value->NamaSubHeader); ?>" kegiatan="<?php echo e($item->KodeHeader); ?>. <?php echo e($item->NamaHeader); ?>" pagu="<?php echo e($detil->PaguAkhir); ?>" sisa="<?php echo e(($detil->PaguAkhir - $detil->Prognosa)); ?>" id="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" href="#" class="open-modal text-success static" style="font-size:20px;" <?php if($locking==1): ?> action="locking" <?php else: ?> action="updatePrognosa" <?php endif; ?>><i class="fa fa-check-circle"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data" class="text-danger" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/prognosa/status',['status'=>'0', 'id'=>$item->KodeHeader.'.'.$key.'.'.$detil->Kode.'.'.$detil->KodeDana,'what'=> 'reset'])); ?>" style="font-size:20px;"><i class="fa fa-exclamation-circle"></i></a>
                                        </div>

                                        <?php else: ?>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Input Prognosa" dana="<?php echo e($detil->NamaDana); ?>" akun="<?php echo e($detil->Kode); ?>. <?php echo e($detil->Keterangan); ?>" output="<?php echo e($key); ?>. <?php echo e($value->NamaSubHeader); ?>" kegiatan="<?php echo e($item->KodeHeader); ?>. <?php echo e($item->NamaHeader); ?>" pagu="<?php echo e($detil->PaguAkhir); ?>"  id="<?php echo e($item->KodeHeader); ?>.<?php echo e($key); ?>.<?php echo e($detil->Kode); ?>.<?php echo e($detil->KodeDana); ?>.<?php echo e($detil->KodeKewenangan); ?>.<?php echo e($detil->KodeProgram); ?>" href="#" class="open-modal text-primary static" <?php if($locking==1): ?> action="locking" <?php else: ?> action="insertPrognosa" <?php endif; ?> style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                        <?php endif; ?>
                                    </td>
								</tr>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                    $TotalPrognosa  += $Prognosa;
                                    $TotalSisa      += $Sisa;

                                ?>
                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end"></th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                                    <th class="text-end"><?php echo e(RP($Prognosa)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($Prognosa,$PaguAkhir)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($Sisa)); ?></th>
                                    <th></th>
                                </tr>

                                <?php
                                $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                $PrognosaSub  += $Prognosa;
                                $SisaSub      += $Sisa;

                                ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <tr class="table-info">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH SUB</th>
                                    <th class="text-end"></th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhirSub)); ?></th>
                                    <th class="text-end"><?php echo e(RP($PrognosaSub)); ?></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($SisaSub)); ?></th>
                                    <th></th>
                                </tr>

                                <?php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                $PrognosaSub  = 0;
                                ?>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end"></th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
									<th class="text-end"><?php echo e(RP($TotalPrognosa)); ?></th>
									<th class="text-center"><?php echo e(Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
                                    <th></th>
								</tr>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/prognosa-satker.blade.php ENDPATH**/ ?>