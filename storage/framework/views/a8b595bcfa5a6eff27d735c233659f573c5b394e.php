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
<li class="breadcrumb-item active">Realisasi PNBP</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row second-chart-list third-news-update">


        <div class="col-xl-6 col-lg-12 box-col-12">
			<div class="card">
				<div class="card-body">
					<div class="media align-items-center">
						<div class="media-body right-chart-content">
							<h5>REALISASI PNBP</h5>
							<small>TARGET PNBP</small><bold> <?php echo e(numbering($data->sum('target'))); ?></bold>
							<small>REALISASI PNBP</small><bold> <?php echo e(numbering($data->sum('span'))); ?></bold>
						</div>
                        <?php
                            $persen = ($data->sum('span')/$data->sum('target'))*100;
                        ?>
						<div class="knob-block text-center">
							<input readonly disabled class="knob1" data-width="5" data-height="100" data-thickness=".3" data-angleoffset="0" data-linecap="round" data-fgcolor="#<?php echo e(Color($persen)); ?>" data-bgcolor="#eef5fb" value="<?php echo e(PersenKnop(divnum($data->sum('span'),$data->sum('target'))*100)); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="col-xl-6 col-lg-12 box-col-12">
			<div class="card">
				<div class="card-body">
					<div class="media align-items-center">
						<div class="media-body right-chart-content">
							<h5>DAYA SERAP PNBP</h5>
							<small>PAGU PNBP</small><bold> <?php echo e(numbering($PaguPNBP->Pagu)); ?></bold>
							<small>MP PNBP</small><bold> <?php echo e(numbering($PaguPNBP->MP)); ?></bold>
							<small>BELANJA PNBP</small><bold> <?php echo e(numbering($PaguPNBP->Realisasi)); ?></bold>
						</div>

						<div class="knob-block text-center">
							<input readonly disabled class="knob1" data-width="5" data-height="100" data-thickness=".3" data-angleoffset="0" data-linecap="round" data-fgcolor="#<?php echo e(Color($PaguPNBP->Persen)); ?>" data-bgcolor="#eef5fb" value="<?php echo e(PersenKnop($PaguPNBP->Persen)); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="col-xl-12 col-lg-12 box-col-12">
			<div class="card">
				<div class="card-body">
					<div class="media align-items-center">
						<div class="media-body start-chart-content">
							<h5>PNBP PERBULAN</h5>
                            <?php echo $chart->container(); ?>

						</div>
					</div>
				</div>
			</div>
		</div>


        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>PNBP Perjenis Jasa</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'eselon1','segment'=>'pnbp'])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'eselon1','segment'=>'pnbp'])); ?>">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>JENIS JASA</th>
                            <th class="text-end">TARGET</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <?php
                        $target_fungsional=0;
                        $realisasi_fungsional=0;
                        ?>

                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->jenis=='F'): ?>
                        <?php
                        $target_fungsional+=$item->target;
                        $realisasi_fungsional+=$item->span;
                        ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // $persen_fungsional = ($realisasi_fungsional/$target_fungsional)*100;
                        ?>
                        <thead class="table-light">
                            <tr>
                            <th></th>
                            <th>PNBP FUNGSIONAL</th>
                            <th class="text-end"><?php echo e(RP($target_fungsional)); ?></th>
                            <th class="text-end"><?php echo e(RP($realisasi_fungsional)); ?></th>
                            <td class="text-center"><span class="badge badge-<?php echo e(ColorTable(divnum($realisasi_fungsional,$target_fungsional)*100)); ?>"><?php echo e(persen(divnum($realisasi_fungsional,$target_fungsional)*100)); ?></span></td>
                        </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->jenis=="F"): ?>
                                <tr class="table-<?php echo e(ColorTable($item->persen_span)); ?>">
                                    <td class="text-center"><?php echo e($item->akun); ?></td>
                                    <td class="text-start"><?php echo e($item->uraian); ?></td>
                                    <td class="text-end"><?php echo e(RP($item->target)); ?></td>
                                    <td class="text-end"><?php echo e(RP($item->span)); ?></td>
                                    <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->persen_span)); ?>"><?php echo e(persen($item->persen_span)); ?>%</span></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <?php
                            $target_nonfungsional=0;
                            $realisasi_nonfungsional=0;
                        ?>

                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($item->jenis=='U'): ?>
                            <?php
                            $target_nonfungsional+=$item->target;
                            $realisasi_nonfungsional+=$item->span;
                            ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        // $persen_nonfungsional = ($realisasi_nonfungsional/$target_nonfungsional)*100;
                        ?>

                        <thead class="table-light">
                            <tr>
                            <th></th>
                            <th>PNBP NON FUNGSIONAL</th>
                            <th class="text-end"><?php echo e(RP($target_nonfungsional)); ?></th>
                            <th class="text-end"><?php echo e(RP($realisasi_nonfungsional)); ?></th>
                            <td class="text-center"><span class="badge badge-<?php echo e(ColorTable(divnum($realisasi_nonfungsional,$target_nonfungsional)*100)); ?>"><?php echo e(persen(divnum($realisasi_nonfungsional,$target_nonfungsional)*100)); ?>%</span></td>
                        </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->jenis=="U"): ?>
                                <tr class="table-<?php echo e(ColorTable($item->persen_span)); ?>">
                                    <td class="text-center"><?php echo e($item->akun); ?></td>
                                    <td class="text-start"><?php echo e($item->uraian); ?></td>
                                    <td class="text-end"><?php echo e(RP($item->target)); ?></td>
                                    <td class="text-end"><?php echo e(RP($item->span)); ?></td>
                                    <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->persen_span)); ?>"><?php echo e(persen($item->persen_span)); ?>%</span></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                        <tfoot class="table-primary">
                            <tr>
                              <th></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($data->sum('target'))); ?></th>
                              <th class="text-end"><?php echo e(RP($data->sum('span'))); ?></th>
                              <th class="text-center"><?php echo e(Persen(divnum($data->sum('span'),$data->sum('target'))*100)); ?>%</th>
                            </tr>
                          </tfoot>
                      </table>
              </div>
            </div>
          </div>

          <script src="<?php echo e(LarapexChart::cdn()); ?>"></script>
          <?php echo e($chart->script()); ?>



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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/dashboard/penerimaan.blade.php ENDPATH**/ ?>