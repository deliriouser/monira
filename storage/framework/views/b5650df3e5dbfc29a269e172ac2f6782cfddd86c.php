<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/bootstrap/bootstrap-table.min.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Padat Karya</li>
<li class="breadcrumb-item"><?php echo e(ucfirst($unit ?? '')); ?></li>
<li class="breadcrumb-item active"><?php echo e($segment); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0"><?php echo e($segment); ?> Padat Karya Per <?php echo e($unit ?? ''); ?>

                      </h5>
                      <div class="card-header-right-icon">
                        <div class="row">

                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPadatKarya',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Excell</a>
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPadatKarya',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                        <div class="table-responsive">
                            <table class="table table-sm" id="card">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">KODE</th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-center"></th>
                                        <th class="text-end">PAGU</th>
                                        <th class="text-end">REALISASI</th>
                                        <th class="text-center">%</th>
                                        <th class="text-end">SISA</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalpagu = 0;
                                    $totaldsa  = 0;
                                    $totalsisa = 0;


                                    ?>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-danger">
                                        <th class="text-center"><?php echo e($item->KodeWilayah); ?></th>
                                        <th class="text-start" colspan="7"><?php echo e($item->NamaWilayah); ?></th>
                                    </tr>
                                    <?php
                                        $totalpaguProp = 0;
                                        $totaldsaProp  = 0;
                                        $totalsisaProp = 0;
                                    ?>
                                    <?php $__currentLoopData = $item->Program; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-danger">
                                        <th class="text-end"></th>
                                        <th class="text-start"><?php echo e($item->KodeProgram); ?></th>
                                        <th class="text-start"><?php echo e($item->NamaProgram); ?></th>
                                        <th class="text-end" colspan="5"></th>
                                    </tr>
                                    <?php $__currentLoopData = $item->Kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-warning">
                                        <th class="text-end"></th>
                                        <th class="text-start"><?php echo e($Kegiatan->KodeKegiatan); ?></th>
                                        <th class="text-start"><?php echo e($Kegiatan->NamaKegiatan); ?></th>
                                        <th class="text-end" colspan="5"></th>
                                    </tr>
                                    <?php $__currentLoopData = $Kegiatan->Output; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Output): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-success">
                                        <th class="text-end"></th>
                                        <th class="text-start"><?php echo e($Output->KodeOutput); ?></th>
                                        <th class="text-start"><?php echo e($Output->NamaOutput); ?></th>
                                        <th class="text-end" colspan="5"></th>
                                    </tr>
                                    <?php $__currentLoopData = $Output->Akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-start"><?php echo e($Akun->KodeAkun); ?></td>
                                        <td class="text-start"><?php echo e($Akun->NamaAkun); ?></td>
                                        <td class="text-center"><?php echo e($Akun->KodeSumberDana); ?></td>
                                        <td class="text-end"><?php echo e(RP($Akun->Pagu)); ?></td>
                                        <td class="text-end"><?php echo e(RP($Akun->Dsa)); ?></td>
                                        <td class="text-center"><?php echo e(Persen($Akun->Persen)); ?>%</td>
                                        <td class="text-end"><?php echo e(RP($Akun->Sisa)); ?></td>

                                    </tr>
                                    <?php
                                        $totalpagu += $Akun->Pagu;
                                        $totaldsa  += $Akun->Dsa;
                                        $totalsisa += $Akun->Sisa;
                                    ?>
                                    <?php
                                        $totalpaguProp += $Akun->Pagu;
                                        $totaldsaProp  += $Akun->Dsa;
                                        $totalsisaProp += $Akun->Sisa;
                                    ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="text-start"></th>
                                        <th class="text-start">JUMLAH PROPINSI</th>
                                        <th class="text-center"></th>
                                        <th class="text-end"><?php echo e(RP($totalpaguProp)); ?></th>
                                        <th class="text-end"><?php echo e(RP($totaldsaProp)); ?></th>
                                        <th class="text-center"><?php echo e(Persen(divnum($totaldsaProp,$totalpaguProp)*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($totalsisaProp)); ?></th>
                                    </tr>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-end"></th>
                                        <th class="text-start">JUMLAH</th>
                                        <th class="text-end"></th>
                                        <th class="text-end"><?php echo e(RP($totalpagu)); ?></th>
                                        <th class="text-end"><?php echo e(RP($totaldsa)); ?></th>
                                        <th class="text-center"><?php echo e(Persen(divnum($totaldsa,$totalpagu)*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($totalsisa)); ?></th>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/padatkarya-two-level.blade.php ENDPATH**/ ?>