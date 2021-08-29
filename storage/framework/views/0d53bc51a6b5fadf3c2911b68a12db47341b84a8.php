<?php $__env->startSection('title', 'upload'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/dropzone.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Upload</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Upload</li>
<li class="breadcrumb-item active">Dropzone</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">

        <div class="col-sm-4">
			<div class="card">
				<div class="card-header">
					<h5>Daftar <?php echo e($what); ?></h5>
				</div>
				<div class="card-body">
                    
                    <?php $__currentLoopData = $revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="default-according style-1 mb-2" id="accordionoc">
                        <div class="card">
                          <div class="card-header bg-secondary">
                            <h5 class="mb-0">
                              <button class="btn btn-link text-white" data-bs-toggle="collapse" data-bs-target="#collapseicon<?php echo e($item->revisi); ?>" aria-expanded="true" aria-controls="collapse11"><i class="icofont icofont-database"></i>
                              <?php if($item->revisi==0): ?> Pagu Awal <?php else: ?> Revisi <?php echo e($item->revisi); ?> <?php endif; ?>
                              </button>
                            </h5>
                          </div>
                          <div class="collapse <?php if($loop->first): ?> show <?php endif; ?>" id="collapseicon<?php echo e($item->revisi); ?>" aria-labelledby="collapseicon" data-bs-parent="#accordionoc">
                            <div class="card-body">
                                Pagu Dipa : <?php echo e(RP($item->data_revisi->jumlah)); ?>

                            </div>
                          </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header">
					<h5>Upload File <?php echo e($what); ?></h5>
				</div>
				<div class="card-body">

                    <form id="myform" action="<?php echo e(route('save/upload')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Data</label>
                                    <select class="select col-sm-4" name="revisi">
                                        <option value="revisi">Revisi</option>
                                        <?php if(!empty($revisi)): ?>
                                        <?php $__currentLoopData = $revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->revisi>0): ?>
                                        <option value="<?php echo e($item->revisi); ?>">Update Revisi <?php echo e($item->revisi); ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        <option value="0">Dipa Awal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Upload</label>
                                    <input name="file" class="form-control mb-4 col-sm-8" type="file" aria-label="file" required="">
                                </div>
                            </div>
                        </div>

                        <div class="btn-showcase text-start">
                            <button class="btn btn-primary" type="submit">Upload</button>
                            <input class="btn btn-light" type="reset" value="Reset">
                        </div>
                    </form>
				</div>
			</div>
		</div>

	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/upload.blade.php ENDPATH**/ ?>