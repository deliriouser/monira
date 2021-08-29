<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
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
                            <h5>Daftar File SK Pejabat</h5>
                        </div>
                        <div class="col-3 text-end">
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
									<th class="text-center">AKSI</th>
									<th class="text-center">TANGGAL UPLOAD</th>
								</tr>
							</thead>
							<tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
									<td class="text-center"><b><?php echo e($item->KodeWilayah); ?></b></td>
									<td class="text-start" colspan="4"><b><?php echo e($item->WilayahName); ?></b></td>
                                </tr>

                                <?php $__currentLoopData = $item->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><?php echo e($satker->KodeSatker); ?></td>
                                        <td class="text-start" colspan="3">
                                           <?php echo e($satker->NamaSatuanKerja); ?>

                                        </td>

                                </tr>
                                <?php $__currentLoopData = $satker->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-start"><?php echo e($file->jenis); ?></td>
                                    <td class="text-center">
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Download" target="_blank" href="<?php echo e(route('download',['id' => Crypt::encrypt($file->path_berkas)])); ?>" class="text-success"><i class="icofont icofont-cloud-download fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" href="#" class="text-primary OpenModalAdminSnipper static" id="<?php echo e($file->id_berkas); ?>" what="editSK"><i class="icofont icofont-exchange fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('admin/snipper/action',['status'=>'0', 'id'=>Crypt::encrypt($file->id_berkas),'what'=>'fileSK'])); ?>" data-bs-original-title="" class="text-danger static"><i class="icofont icofont-error fa-2x"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo e(\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i:s')); ?>

                                    </td>

                                 </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/snipper-daftar-sk.blade.php ENDPATH**/ ?>