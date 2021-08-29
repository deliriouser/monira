<div class="email-top">
    <div class="row">
      <div class="col-md-6 xl-100 col-sm-12">
        <div class="media"><img width="50px" class="me-3 rounded-circle" src="<?php echo e(asset('assets/images/user/avatar.png')); ?>" alt="">
        <div class="media-body">
            <h6 class="text-primary">Admin
                <small class="text-success float-end">
                <?php echo e(\Carbon\Carbon::parse($data->created_at)->isoFormat('D MMMM Y h:m:s')); ?>

              </small>
          </h6>
            <h6><?php echo e($data->message->Subject); ?></h6>
        </div>
        </div>
      </div>

    </div>
  </div>
  <div class="email-wrapper">
    <?php echo $data->message->Message; ?>

    <br>
    
    <div class="d-inline-block">
      <p class="text-muted">Files Attachment</p><a class="text-muted text-end right-download" href="#" data-bs-original-title="" title=""></a>
      <div class="clearfix"></div>
    </div>
    <div class="attachment mt-3">
      <ul class="list-inline">
          <?php $__currentLoopData = $data->message->attachment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="list-inline-item text-primary"><a targer="_blank" href="<?php echo e(route('download',['id' => Crypt::encrypt($file->FileName)])); ?>"><i class="icofont icofont-cloud-download fa-2x"></i></a></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  </div>
<?php /**PATH /Users/user/dev/app-monira/resources/views/apps/open-message-satker.blade.php ENDPATH**/ ?>