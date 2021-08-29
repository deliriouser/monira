<?php $__env->startSection('title', 'upload'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/dropzone.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Data Pegawai Bersertifikat</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">Daftar</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">
                    <form id="myform" action="<?php echo e(route('satker/snipper/post/pejabat')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="status" value="calon">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Jenis Diklat</label>
                                <select style="font-size:14px" required class="select col-sm-12" name="jenis">
                                <option value="">Pilih</option>
                                <?php $__currentLoopData = $refdata_jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refdata_jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($refdata_jabatan->id_jabatan); ?>"><?php echo e($refdata_jabatan->jenis_diklat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                             </div>
                            <div class="mb-3">
                                <label class="form-label">NIP Pegawai</label>
                                <input required type="text" style="font-size:14px" class="form-control onlynumber nip" name="nip">
                                <input class="what" type="hidden" name="what" value="InsertTalent">
                            </div>
                            <div class="append_profile">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Sertifikat</label>
                                <input required type="text" style="font-size:14px" class="form-control" name="sertifikat">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Pilih File Sertifikat</label>
                                <input name="file" class="form-control mb-4 col-sm-8 file" type="file" aria-label="file" required="">
                            </div>
                        </div>
                        <div class="btn-showcase text-start">
                            <button class="btn btn-primary submitPegawai" type="submit">Upload</button>
                            <input class="btn btn-light" type="reset" value="Reset">
                        </div>
                    </form>
				</div>
			</div>
		</div>
        <div class="col-sm-8">
			<div class="card">
				<div class="card-header">
					<h5>Daftar Pegawai Bersertifikat</h5>
				</div>
                    <div class="table-responsive">
                        <table class="table table-sm loadSK">
                          <thead class="bg-primary">
                            <tr>
                              <th class="text-center">#</th>
                              <th class="text-start">JENIS</th>
                              <th class="text-start">NAMA</th>
                              <th class="text-center">...</th>
                            </tr>
                          </thead>
                          <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-center">
                            <?php echo e($loop->iteration); ?>

                        </td>
                        <td class="text-start"><?php echo e($item->refJabatan->jenis_diklat); ?></td>
                        <td class="text-start">
                            <div class="d-inline-block align-middle">
                                <img style="width:40px !important; height:40px !important;" class="img-40 m-r-15 rounded-circle align-top" src="<?php echo e(asset('storage/'.$item->profile->foto)); ?>" alt="">
                                <div class="d-inline-block"><span><?php echo e($item->profile->nama ?? ''); ?></span>
                                </div>
                              </div>
                            </td>
                            <td class="text-center">
                            <a target="_blank" href="<?php echo e(route('download',['id' => Crypt::encrypt($item->certificate->path_file)])); ?>" class="text-success"><i class="icofont icofont-cloud-download fa-2x"></i></a>
                       <a onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=>'calon'])); ?>" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                        </table>
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


<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/daftar-calon-pejabat.blade.php ENDPATH**/ ?>