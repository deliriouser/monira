<?php $__env->startSection('title', 'Edit Profile'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item active">Edit Profile</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="edit-profile">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header">
                        <div class="row">
                            <div class="col-9">
                                <h5>Profile User</h5>
                            </div>
                            <div class="col-3 text-end">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="profile-title">
									<div class="media">
                                        <?php if(empty(Auth::user()->photo_url)): ?>
										<img class="img-100 rounded-circle" alt="" src="<?php echo e(asset('assets/images/user/logo.png')); ?>">
                                        <?php else: ?>
                                        <?php endif; ?>

									</div>
								</div>
                            </div>
                        <div class="col-md-10">
						<form id="myform" action="<?php echo e(route('save/data/profile')); ?>" method="post">
                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="input-group">
                                        <span class="input-group-text"><i class="icofont icofont-business-man"></i></span>
                                        <input name="name" class="form-control" type="text" value="<?php echo e((Auth::user()->name)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">User Level</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-layers"></i></span>
                                            <input disabled class="form-control" type="text" value="<?php echo e(strtoupper(level(Auth::user()->level_id))); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Unit</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-street-view"></i></span>
                                        <input disabled class="form-control" type="text" value="<?php echo e(strtoupper((Auth::user()->satker->NamaSatuanKerja ?? ''))); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-gear"></i></span>
                                        <input  name="password" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                        <input name="email" id="email" class="form-control" type="text" value="<?php echo e((Auth::user()->profile->email ?? '')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-ui-cell-phone"></i></span>
                                        <input  id="only-number" name="phone" class="form-control" type="text" value="<?php echo e((Auth::user()->profile->phone ?? '')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="5" placeholder="Enter Your Address"><?php echo e(Auth::user()->profile->address ?? ''); ?></textarea>
                                    </div>
                                </div>

                            </div>

							<div class="form-footer">
								<button id="submitform" type="submit" class="btn btn-primary btn-block" >Save</button>
							</div>
						 </form>
                        </div>
					</div>
				</div>
                </div>
			</div>
			

		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/edit-profile.blade.php ENDPATH**/ ?>