<?php $__env->startSection('title', 'Snipper'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/tour.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Maksimum Pencairan PNBP</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">MP PNBP</li>
<li class="breadcrumb-item active">Daftar</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Daftar RPD dan MP PNBP</h5>
                        </div>
                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <a data-intro="Cetak Data" href="#"><i data-feather="printer" class="exportbtn text-primary"></i></a>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route('satker/load/pdf',['what'=>'rpd'])); ?>">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadrpd" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">BULAN</th>
									<th class="text-center">SE MP</th>
									<th class="text-center">RPD MP</th>
									<th class="text-center">ALOKASI MP</th>
									<th class="text-center">DAYA SERAP MP</th>
									<th class="text-center">...</th>
								</tr>
							</thead>
                            <tbody>
                                <?php
                                    $total_rpd = 0;
                                    $total_mp  = 0;
                                    $total_dsa = 0;
                                    $bulan_depan = Date('n')+1;
                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->id<=$bulan_depan): ?>
                                <?php
                                $total_rpd += $item->rpd->jumlah ?? '0';
                                $total_mp  += $item->mp->Amount ?? '0';
                                $total_dsa += $item->dsa->Amount ?? '0';
                                ?>

                                <tr>
                                    <td valign="middle" class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td valign="middle" class="text-start"><?php echo e($item->BulanName); ?></td>
                                    <td valign="middle" class="text-center">
                                        <?php if(count($item->semp)>0): ?>
                                        <?php $__currentLoopData = $item->semp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filemp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Download SE MP Tahap <?php echo e($filemp->Tahap); ?>" target="_blank" href="<?php echo e(route('download',['id' => Crypt::encrypt($filemp->path_file)])); ?>" class="text-success" style="font-size:20px;"><i class="fa fa-cloud-download"></i></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td valign="middle" class="text-end"><?php echo e(RP($item->rpd->jumlah ?? '0')); ?></td>
                                    <td valign="middle" class="text-end"><?php echo e(RP($item->mp->Amount ?? '0')); ?></td>
                                    <td valign="middle" class="text-end"><?php echo e(RP($item->dsa->Amount ?? '0')); ?></td>
                                    <td valign="middle" class="text-center">
                                    <?php if($alokasiMP<$paguPNBP): ?>
                                        <?php if((DATE ('d')>='15' AND DATE ('d')<='26') OR $bulan_depan==$item->id): ?>
                                        <?php if($item->id==$bulan_depan): ?>
                                        <a data-intro="Input RPD" href="#" class="text-primary OpenModalMp" id="<?php echo e($item->id); ?>.<?php echo e($paguPNBP-$total_rpd); ?>" what="create" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                        <?php endif; ?>
                                        <?php if(!empty($item->rpd->jumlah)): ?>
                                        <a data-intro="Lihat Daftar RPD" href="#" class="text-success OpenModalMp"  id="<?php echo e($item->id); ?>" what="read" style="font-size:20px;"><i class="fa fa-search"></i></a>
                                        <?php endif; ?>

                                        <?php else: ?>
                                            <?php if(!empty($item->rpd->jumlah)): ?>
                                            <a data-intro="Lihat Daftar RPD" href="#" class="text-success OpenModalMp"  id="<?php echo e($item->id); ?>" what="read" style="font-size:20px;"><i class="fa fa-search"></i></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if(!empty($item->rpd->jumlah)): ?>
                                        <a data-intro="Lihat Daftar RPD" href="#" class="text-success OpenModalMp"  id="<?php echo e($item->id); ?>" what="read" style="font-size:20px;"><i class="fa fa-search"></i></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tr class="table-primary">
                                <th></th>
                                <th class="text-start"></th>
                                <th class="text-start">JUMLAH</th>
									<th class="text-end"><?php echo e(RP($total_rpd)); ?></th>
									<th class="text-end"><?php echo e(RP($total_mp)); ?></th>
									<th class="text-end"><?php echo e(RP($total_dsa)); ?></th>
                                    <th></th>
								</tr>
                                <tr class="table-danger">
                                    <th class="text-start"></th>
                                    <th></th>
									<th class="text-start">JUMLAH PAGU PNBP</th>
									<th class="text-end"><?php echo e(RP($paguPNBP)); ?></th>
									<th class="text-end"></th>
									<th class="text-end"></th>
                                    <th></th>
								</tr>
                                <tr class="table-danger">
                                    <th class="text-start"></th>
                                    <th></th>
									<th class="text-start">SISA RPD</th>
									<th class="text-end"><?php echo e(RP($paguPNBP-$total_rpd)); ?></th>
									<th class="text-end"></th>
									<th class="text-end"></th>
                                    <th></th>
								</tr>

						</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/tour/intro.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/tour/intro-init.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/mppnbp.blade.php ENDPATH**/ ?>