<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Belanja</li>
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
                      <h5 class="m-0"><?php echo e($segment); ?> Per <?php echo e($unit ?? ''); ?>

                      </h5>
                      <div class="card-header-right-icon">
                        <div class="row">
                            <div class="col-8">
                                <div class="dropdown">
                                <button class="btn dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo e(nameofmonth(Request::route('month'))); ?></button>
                                <div class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownMenuButton" style="">
                                    <?php for($i=1;$i<=12;$i++): ?>
                                    <?php if($i<= DATE('n')): ?>
                                    <a class="dropdown-item <?php if($i==Request::route('month')): ?> bg-danger text-white <?php endif; ?>" href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>Request::route('unit'),'segment'=>Request::route('segment'), 'month'=>$i])); ?>"><?php echo e(nameofmonth($i)); ?></a>
                                    <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportSpending',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Excell</a>
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportSpending',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Pdf</a>
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
                                        <th class="text-start">KODE</th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-end">PAGU AWAL</th>
                                        <th class="text-end">PAGU AKHIR</th>
                                        <th class="text-end">REALISASI</th>
                                        <th class="text-center">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($item['Kode']); ?></td>
                                        <td class="text-start"><?php echo e($item['Keterangan']); ?></td>
                                        <td class="text-end"><?php echo e(RP($item['PaguAwal'])); ?></td>
                                        <td class="text-end"><?php echo e(RP($item['Pagu'])); ?></td>
                                        <td class="text-end"><?php echo e(RP($item['Realisasi'])); ?></td>
                                        <td class="text-center"><?php echo e(Persen($item['Persen'])); ?>%</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-start">JUMLAH</th>
                                        <th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
                                        <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                                        <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                                        <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/belanja-one-level.blade.php ENDPATH**/ ?>