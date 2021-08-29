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
<li class="breadcrumb-item active">Realisasi Belanja Covid</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row second-chart-list third-news-update">

        <div class="col-xl-4 col-lg-12 box-col-12" >
            <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body right-chart-content">
                        <h5>Belanja Pegawai</h5>
                        <small>PAGU</small><bold> 0</bold>
                        <small>REALISASI</small><bold> 0</bold>
                    </div>
                    <div class="knob-block text-center">
                        <input readonly disabled class="knob1" data-width="5" data-height="100" data-thickness=".3" data-angleoffset="0" data-linecap="round" data-fgcolor="#51bb25" data-bgcolor="#eef5fb" value="0">
                    </div>
                </div>
            </div>
        </div>
    </div>

        <?php $__currentLoopData = $DashBelanja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-4 col-lg-12 box-col-12" >
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
                    <div class="row">
                        <div class="col-9">
                            <h5>Realisasi Penanganan Covid-19 per Jenis Belanja</h5>
                        </div>
                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'eselon1','segment'=>'belanja_covid'])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'eselon1','segment'=>'belanja_covid'])); ?>">PDF</a>
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
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $TotalPaguAwal=0;
                            $TotalPaguAkhir=0;
                            $TotalRealisasi=0;
                            $TotalSisa=0;
                            ?>

                            <?php $__currentLoopData = $DashBelanja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                                <td class="text-center"><?php echo e($item->Belanja); ?></td>
                                <td class="text-start"><?php echo e(belanja($item->Belanja)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?>%</span></td>
                            </tr>
                            <?php
                            $TotalPaguAwal  += $item->PaguAwal;
                            $TotalPaguAkhir += $item->Pagu;
                            $TotalRealisasi += $item->Realisasi;
                            $TotalSisa      += $item->Sisa;
                            ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
                              <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                            </tr>
                          </tfoot>
                      </table>
              </div>
            </div>
          </div>


          <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                <div class="row">
                    <div class="col-9">
                        <h5>Realisasi Penanganan Covid-19 per Jenis Kegiatan</h5>
                    </div>
                    <div class="col-3 text-end dropup-basic">
                        <div class="export">
                            <i data-feather="printer" class="exportbtn text-primary"></i>
                            <div class="export-content">
                                <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'eselon1','segment'=>'kegiatan_covid'])); ?>">Excell</a>
                                <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'eselon1','segment'=>'kegiatan_covid'])); ?>">PDF</a>
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
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $TotalPaguAwal=0;
                            $TotalPaguAkhir=0;
                            $TotalRealisasi=0;
                            $TotalSisa=0;
                            ?>

                            <?php $__currentLoopData = $DashKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                                <td class="text-center"><?php echo e($item->KdKegiatan); ?></td>
                                <td class="text-start"><?php echo e(($item->NamaKegiatan)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?>%</span></td>
                            </tr>
                            <?php
                            $TotalPaguAwal  += $item->PaguAwal;
                            $TotalPaguAkhir += $item->Pagu;
                            $TotalRealisasi += $item->Realisasi;
                            $TotalSisa      += $item->Sisa;
                            ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
                              <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                            </tr>
                          </tfoot>
                      </table>
              </div>
            </div>
          </div>

          <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Realisasi Penanganan Covid-19 per Sumber Dana</h5>
                        </div>
                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'eselon1','segment'=>'sumberdana_covid'])); ?>">Excell</a>
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'eselon1','segment'=>'sumberdana_covid'])); ?>">PDF</a>
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
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $TotalPaguAwal=0;
                            $TotalPaguAkhir=0;
                            $TotalRealisasi=0;
                            $TotalSisa=0;
                            ?>

                            <?php $__currentLoopData = $DashSDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                                <td class="text-center"><?php echo e($item->KdSumberDana); ?></td>
                                <td class="text-start"><?php echo e(($item->NamaSumberDana)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?>%</span></td>
                            </tr>
                            <?php
                            $TotalPaguAwal  += $item->PaguAwal;
                            $TotalPaguAkhir += $item->Pagu;
                            $TotalRealisasi += $item->Realisasi;
                            $TotalSisa      += $item->Sisa;
                            ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                              <th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
                              <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                            </tr>
                          </tfoot>
                      </table>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/dashboard/covid.blade.php ENDPATH**/ ?>