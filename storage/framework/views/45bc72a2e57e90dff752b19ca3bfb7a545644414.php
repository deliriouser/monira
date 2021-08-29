<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/tour.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">File SK</li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				
                    <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Daftar Pejabat Perbendaharaan Propinsi <?php echo e(strtolower($data->WilayahName)); ?></h5>
                        </div>

                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportSnipper',['type'=>'pdf','unit'=>$data->KodeWilayah,'segment'=>'pejabat'])); ?>"><i data-feather="printer" class="exportbtn text-primary"></i></a>

                            </div>
                        </div>

                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadrpd" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-center">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-start">PEJABAT</th>
									<th class="text-center">AKSI</th>
								</tr>
							</thead>
							<tbody>
                                <tr class="table-warning">
									<td class="text-center"><b><?php echo e($data->KodeWilayah); ?></b></td>
									<td class="text-start" colspan="4"><b><?php echo e($data->WilayahName); ?></b></td>
                                </tr>

                                <?php $__currentLoopData = $data->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr class="table-info">
                                        <th class="text-center"><?php echo e($loop->iteration); ?></th>
                                        <th class="text-center"><?php echo e($satker->KodeSatker); ?></th>
                                        <th class="text-start" colspan="3">
                                           <?php echo e($satker->NamaSatuanKerja); ?>

                                        </th>

                                </tr>
                                <?php $__currentLoopData = $satker->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pejabat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr valign="middle">
                                    <td class="text-center"></td>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-start"><?php echo e($pejabat->refjabatan->keterangan); ?></td>
                                    <td class="text-start">
                                        <div class="d-inline-block align-middle">
                                            <img style="width:40px !important; height:40px !important;" class="img-fluid img-40 rounded-circle mb-3 m-r-15" src="<?php echo e(asset('storage/'.$pejabat->profile->foto)); ?>" alt="img">
                                            <div class="d-inline-block"><span><?php echo e($pejabat->profile->nama); ?></span>
                                                <span style="font-size:16px;" class="text-success">
                                                <?php if(!empty($pejabat->bnt)): ?>
                                                    <i class="fa fa-check-circle"></i>
                                                    <?php endif; ?>
                                                    <?php if(!empty($pejabat->barjas)): ?>
                                                    <i class="fa fa-check-circle"></i>
                                                <?php endif; ?>
                                                </span>
                                              <p class="font-roboto"><?php echo e(phone($pejabat->profile->telepon)); ?></p>



                                            </div>
                                          </div>

                                        </td>
                                    <td class="text-center">
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Data" href="#" class="text-primary OpenModalAdminSnipper static" id="<?php echo e($pejabat->pejabat_id); ?>" what="read"><i class="fa fa-question-circle fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" href="#" class="text-success OpenModalAdminSnipper static" id="<?php echo e($pejabat->pejabat_id); ?>.<?php echo e($pejabat->detiljabatan->detil_id ?? '0'); ?>.<?php echo e($pejabat->refJabatan->id_jabatan); ?>.<?php echo e($satker->KodeSatker); ?>" what="edit"><i class="fa fa-info-circle fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Nonaktif Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/snipper/action',['status'=>'0', 'id'=>Crypt::encrypt($pejabat->pejabat_id),'what'=>'inactive'])); ?>" data-bs-original-title="" class="text-warning static"><i class="fa fa-arrow-circle-down fa-2x"></i></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/snipper/action',['status'=>'0', 'id'=>Crypt::encrypt($pejabat->pejabat_id),'what'=>'pejabat'])); ?>" data-bs-original-title="" class="text-danger static"><i class="fa fa-times-circle fa-2x"></i></i></a>
                                    </td>
                                 </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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

<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/snipper-daftar-pejabat.blade.php ENDPATH**/ ?>