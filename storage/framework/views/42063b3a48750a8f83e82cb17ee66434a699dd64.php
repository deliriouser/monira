<?php $__env->startSection('title', 'Snipper'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/tour.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Pejabat Perbendaharaan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">Daftar</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="col-md-12 project-list">
        <div class="card">
          <div class="row">
            <div class="col-md-6">
              <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-controls="top-home" aria-selected="true" data-bs-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>Menjabat</a></li>
                <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab" href="#top-profile" role="tab" aria-controls="top-profile" aria-selected="false" data-bs-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>Nonaktif</a></li>
              </ul>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-0 me-0"></div>
                <a data-intro="Tambah Pejabat Baru" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Data Pejabat" class="btn btn-success OpenModalSnipper" id="0" what="add" href="#" data-bs-original-title="" title="">
                    <i data-feather="plus-square"></i>
                </a>
                <div class="form-group mb-0 me-0 text-lg-center"></div>
                <a data-intro="Cetak Daftar" target="_blank" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak PDF" class="btn btn-primary" href="<?php echo e(route('satker/load/pdf',['what'=>'snipper'])); ?>" data-bs-original-title="" title="">
                    <i data-feather="printer"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
<div class="tab-content" id="top-tabContent">
<div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
   <div class="row" id="loadPejabat">
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php if($item->status==1): ?>
    <div class="col-xl-4 box-col-4">
        <div class="card custom-card">
            <?php if(!empty($item->bnt)): ?>
            <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BNT</div>
            <?php endif; ?>
            <?php if(!empty($item->barjas)): ?>
            <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BARJAS</div>
            <?php endif; ?>
            <?php if($item->status==0): ?>
            <div class="ribbon ribbon-bookmark ribbon-right ribbon-danger">Nonaktif</div>
            <?php endif; ?>
            <div class="card-header"><img class="img-fluid" src="<?php echo e(asset('assets/images/user/'.$item->jabatan.'.jpg')); ?>" alt=""></div>
          <div class="card-profile"><img class="rounded-circle" src="<?php echo e(asset('storage/'.$item->profile->foto)); ?>" alt=""></div>
          <ul class="card-social">
          </ul>
          <div class="text-center profile-details">
            <h5><?php echo e($item->refJabatan->keterangan ?? ''); ?></h5>
            <h6 class="mb-0"><?php echo e($item->profile->nama); ?></h6>
            <p class="mb-0" name="<?php echo e($item->profile->nip); ?>"><?php echo e($item->profile->pangkat); ?> (<?php echo e($item->profile->golongan); ?>)</p>
            <p>NIP. <?php echo e($item->profile->nip); ?></p>
          </div>
          <div class="card-footer row">
            <div class="col-6 col-sm-6">
              <h6>Lama Menjabat</h6>
                    <?php if(!empty($item->detiljabatan->tmt_jabatan) AND !empty($item->detiljabatan->tmt_awal)): ?>
                    <?php echo e(\Carbon\Carbon::parse($item->detiljabatan->tmt_awal)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?>

                    <?php elseif(!empty($item->detiljabatan->tmt_jabatan)): ?>
                    <?php echo e(\Carbon\Carbon::parse($item->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?>

                    <?php else: ?>
                    <?php endif; ?>
            </div>
            <div class="col-6 col-sm-6">
              <h6>Telepon</h6>
              <?php echo e(phone($item->profile->telepon)); ?>

            </div>
          </div>

          <ul class="card-social">

            <li><a data-intro="Profil Pejabat" href="#<?php echo e($item->profile->nip); ?>" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-primary OpenModalSnipper" what="read" id="<?php echo e($item->pejabat_id); ?>">Detail</div></a></li>
            <li><a data-intro="Nonaktifkan Pejabat" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=> 'user'])); ?>" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-danger">Nonaktif</div></a></li>
            <li><a data-intro="Edit Data Pejabat" href="#<?php echo e($item->profile->nip); ?>" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-success OpenModalSnipper" what="edit" id="<?php echo e($item->pejabat_id); ?>.<?php echo e($item->detiljabatan->detil_id ?? '0'); ?>.<?php echo e($item->refJabatan->id_jabatan); ?>">Update</div></a></li>
        </ul>



        </div>
      </div>
    <?php endif; ?>
   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   </div>
</div>

<div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="top-profile-tab">
    <div class="row" id="loadPejabatInactive">
     <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
     <?php if($item->status==0): ?>
     <div class="col-xl-4 box-col-4">
         <div class="card custom-card bg-light-danger text-danger">
             <?php if(!empty($item->bnt)): ?>
             <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BNT</div>
             <?php endif; ?>
             <?php if(!empty($item->barjas)): ?>
             <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BARJAS</div>
             <?php endif; ?>
             <?php if($item->status==0): ?>

             <div class="ribbon ribbon-bookmark ribbon-right ribbon-danger">Nonaktif</div>
             <?php endif; ?>
             <div class="card-header"><img class="img-fluid" src="<?php echo e(asset('assets/images/user/'.$item->jabatan.'.jpg')); ?>" alt=""></div>
           <div class="card-profile"><img class="rounded-circle" src="<?php echo e(asset('storage/'.$item->profile->foto)); ?>" alt=""></div>
           <ul class="card-social">
           </ul>
           <div class="text-center profile-details">
             <h5><?php echo e($item->refJabatan->keterangan ?? ''); ?></h5>
             <h6 class="mb-0"><?php echo e($item->profile->nama); ?></h6>
             <p class="mb-0"><?php echo e($item->profile->pangkat); ?> (<?php echo e($item->profile->golongan); ?>)</p>
             <p>NIP. <?php echo e($item->profile->nip); ?></p>
           </div>
           <div class="card-footer row">
             <div class="col-6 col-sm-6">
               <h6>Pendidikan</h6>
               <?php echo e($item->profile->pendidikan_terakhir); ?>

             </div>
             <div class="col-6 col-sm-6">
               <h6>Telepon</h6>
               <?php echo e(phone($item->profile->telepon)); ?>


             </div>
           </div>

           <ul class="card-social">
            <li><a data-intro="Profil Pejabat" href="#<?php echo e($item->profile->nip); ?>" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-primary OpenModalSnipper" what="read" id="<?php echo e($item->pejabat_id); ?>">Detail</div></a></li>
            <li><a data-intro="Aktifkan Pejabat" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/snipper/user',['status'=>'1', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=> 'user'])); ?>" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-dark">Aktifkan</div></a></li>
            <li><a data-intro="Hapus Data" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/snipper/user',['status'=>'3', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=> 'deleteuser'])); ?>" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-danger">Hapus</div></a></li>
           </ul>

         </div>
       </div>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/daftar.blade.php ENDPATH**/ ?>