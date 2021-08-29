<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
   <div class="row">
      <div class="col-xl-5"><img class="bg-img-cover bg-center" src="<?php echo e(asset('assets/images/login/left-bg.png')); ?>" alt="looginpage"></div>
      <div class="col-xl-7 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo text-center" href="index.html"><img class="img-fluid for-light" src="<?php echo e(asset('assets/images/logo/logo-text-md.png')); ?>" alt="looginpage"><img class="img-fluid for-dark" src="<?php echo e(asset('assets/images/logo/logo_dark.png')); ?>" alt="looginpage"></a></div>
               <div class="login-main">
                  <form id="myform" class="theme-form" action="<?php echo e(route('postlogin')); ?>" method="POST">
                    <?php echo e(csrf_field()); ?>

                     <h4>Sign in to account</h4>
                     <p>Enter your username & password to login</p>
                     <div class="form-group">
                        <label class="col-form-label">Username</label>
                        <input name="username" class="form-control" type="text" required="">
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="password" required="">
                     </div>
                     <div class="form-group mb-0">
                        <div class="checkbox p-0">
                           <input id="checkbox1" type="checkbox" name="remember">
                           <label class="text-muted" for="checkbox1">Remember</label>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/authentication/login-two.blade.php ENDPATH**/ ?>