<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3 class="f-w-600" id="greeting"></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">Default</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row second-chart-list third-news-update">


        <?php $__currentLoopData = $DashBelanja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-4 col-lg-12 box-col-12 example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="top" title="Rincian Data" data-offset="-20px -20px" data-bs-content="PAGU : <?php echo e(RP($item->Pagu)); ?>">
			<div class="card">
				<div class="card-body">
					<div class="media align-items-center">
						<div class="media-body right-chart-content">
							<h5>Belanja <?php echo e(belanja($item->Belanja)); ?></h5>
							<small>PAGU</small><bold> <?php echo e(numbering($item->Pagu)); ?></bold>
							<small>REALISASI</small><bold> <?php echo e(numbering($item->Realisasi)); ?></bold>
						</div>
						<div class="knob-block text-center">
							<input readonly disabled class="knob1" data-width="5" data-height="100" data-thickness=".3" data-angleoffset="0" data-linecap="round" data-fgcolor="#<?php echo e(Color($item->Persen)); ?>" data-bgcolor="#eef5fb" value="<?php echo e(Persen($item->Persen)); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title mb-0">Realisasi Jenis Belanja</h5>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
              <div class="card-block row">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $DashBelanja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                                <td class="text-center"><?php echo e($item->Belanja); ?></td>
                                <td class="text-start"><?php echo e(belanja($item->Belanja)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?></span></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $totalPersen = ($DashBelanja->sum('Realisasi')/$DashBelanja->sum('Pagu'))*100;
                            ?>
                        </tbody>
                        <thead class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($DashBelanja->sum('PaguAwal'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashBelanja->sum('Pagu'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashBelanja->sum('Realisasi'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashBelanja->sum('Sisa'))); ?></th>
                              <th class="text-center"><?php echo e(Persen($totalPersen)); ?></th>
                            </tr>
                          </thead>
                      </table>
                </div>
              </div>
            </div>
          </div>


          <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Realisasi Jenis Kegiatan</h5>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
              <div class="card-block row">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $DashKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                                <td class="text-center"><?php echo e($item->KdKegiatan); ?></td>
                                <td class="text-start"><?php echo e($item->NamaKegiatan); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?></span></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $totalPersen = ($DashKegiatan->sum('Realisasi')/$DashKegiatan->sum('Pagu'))*100;
                            ?>
                        </tbody>
                        <thead class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($DashKegiatan->sum('PaguAwal'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashKegiatan->sum('Pagu'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashKegiatan->sum('Realisasi'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashKegiatan->sum('Sisa'))); ?></th>
                              <th class="text-center"><?php echo e(Persen($totalPersen)); ?></th>
                            </tr>
                          </thead>
                      </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Realisasi Sumber Dana</h5>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
              <div class="card-block row">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $DashSDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                                <td class="text-center"><?php echo e($item->KodeSumberDana); ?></td>
                                <td class="text-start"><?php echo e($item->NamaSumberDana); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?></span></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $totalPersen = ($DashSDana->sum('Realisasi')/$DashSDana->sum('Pagu'))*100;
                            ?>
                        </tbody>
                        <thead class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($DashSDana->sum('PaguAwal'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashSDana->sum('Pagu'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashSDana->sum('Realisasi'))); ?></th>
                              <th class="text-end"><?php echo e(RP($DashSDana->sum('Sisa'))); ?></th>
                              <th class="text-center"><?php echo e(Persen($totalPersen)); ?></th>
                            </tr>
                          </thead>
                      </table>
                </div>
              </div>
            </div>
          </div>


	</div>
</div>
<script type="text/javascript">
	var session_layout = '<?php echo e(session()->get('layout')); ?>';
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/knob/knob.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/knob/knob-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead/handlebars.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead/typeahead.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead/typeahead.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead-search/handlebars.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead-search/typeahead-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/dashboard/index.blade.php ENDPATH**/ ?>