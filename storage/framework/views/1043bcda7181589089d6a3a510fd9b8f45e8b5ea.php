<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Covid</li>
<li class="breadcrumb-item"><?php echo e(ucfirst($unit)); ?></li>
<li class="breadcrumb-item active"><?php echo e($segment); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0"><?php echo e($segment); ?> Penanganan Covid-19 Per <?php echo e($unit ?? ''); ?>

                      </h5>

                      <div class="card-header-right-icon">
                        <div class="row">
                            <div class="col-8">
                                <div class="dropdown">
                                <button class="btn dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo e(nameofmonth(Request::route('month'))); ?></button>
                                <div class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownMenuButton" style="">
                                    <?php for($i=1;$i<=12;$i++): ?>
                                    <?php if($i<= DATE('n')): ?>
                                    <a class="dropdown-item <?php if($i==Request::route('month')): ?> bg-danger text-white <?php endif; ?>" href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>Request::route('unit'),'segment'=>Request::route('segment'), 'month'=>$i])); ?>"><?php echo e(nameofmonth($i)); ?></a>
                                    <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportCovid',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Excell</a>
                                        
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>

                    </div>
                </div>
				
                    <div class="table-responsive">



                        <table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr valign="middle">
									<th class="text-center" rowspan="2">NO</th>
									<th class="text-start" rowspan="2">KODE</th>
									<th class="text-start" rowspan="2">KETERANGAN</th>
									<th class="text-center" rowspan="2">DANA</th>
									<th class="text-center" colspan="2">PAGU</th>
									<th class="text-center" rowspan="2"></th>
									<th class="text-center" colspan="2">REALISASI</th>
									<th class="text-center" rowspan="2">TGL / NO SP2D</th>
									<th class="text-center" rowspan="2">%</th>
									<th class="text-center" rowspan="2">SISA</th>
								</tr>
                                <tr>
									<th class="text-center">VOLUME</th>
									<th class="text-center">RUPIAH</th>
									<th class="text-center">VOLUME</th>
									<th class="text-center">RUPIAH</th>
                                </tr>
							</thead>
							<tbody>
                                <?php
                                    $TotalPaguAkhir          = 0;
                                    $TotalRealisasi          = 0;
                                    $TotalSisa               = 0;
                                    $PaguAwalSub             = 0;
                                    $PaguAkhirSub            = 0;
                                    $RealisasiSub            = 0;
                                    $SisaSub                 = 0;
                                    $TotalPaguAkhir_kegiatan = 0;
                                    $TotalRealisasi_kegiatan = 0;
                                    $TotalSisa_kegiatan      = 0;

                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
									<td class="text-start" colspan="11"><b><?php echo e($item->NamaHeader); ?></b></td>
                                </tr>

                                <?php
                                    $PaguAkhirSub_kegiatan = 0;
                                    $RealisasiSub_kegiatan = 0;
                                    $SisaSub_kegiatan      = 0;

                                ?>
                            <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-warning">
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><b><?php echo e($key); ?></b></td>
                                        <td class="text-start" colspan="10"><b>
                                            <?php if(isset($value->KodeSubHeader)): ?>
                                                <?php echo e($value->NamaSubHeader); ?>

                                            <?php endif; ?>
                                        </b></td>
                                </tr>
                                <?php
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Sisa      = 0;
                                    $PaguKegiatan_satker     = 0;
                                    $BelanjaKegiatan_satker  = 0;
                                    $SisaKegiatan_satker     = 0;

                                ?>

                                    <?php $__currentLoopData = $value->SubData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $output => $valoutput): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $valoutput->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan => $valkegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $valkegiatan->SubDataDana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dana => $valdana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $valdana->SubDataAkun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun => $detil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $PaguAkhir              += $detil->PaguAkhir ?? '0';
                                    $Realisasi              += $detil->Realisasi ?? '0';
                                    $Sisa                   += $detil->Sisa ?? '0';
                                ?>

                                <tr>
									<th class="text-center"></th>
									<th class="text-center"><?php echo e($valkegiatan->KodeKegiatan); ?>.<?php echo e($valoutput->KodeOutput); ?>.<?php echo e($detil->Kode); ?></th>
									<th class="text-start"><?php echo e($detil->Keterangan); ?></th>
									<th class="text-center"><?php echo e($detil->SumberDana); ?></th>
                                    <th></th>
									<th class="text-end"><?php echo e(RP($detil->PaguAkhir)); ?></th>
                                    <th></th>
                                    <th></th>
									<th class="text-end"><?php echo e(RP($detil->Realisasi)); ?></th>
                                    <th></th>
									<th class="text-center"><?php echo e(Persen($detil->Persen)); ?>%</th>
									<th class="text-end"><?php echo e(RP($detil->Sisa)); ?></th>
								</tr>
                                <?php
                                    $PaguKegiatan    = 0;
                                    $BelanjaKegiatan = 0;
                                    $SisaKegiatan    = 0;

                                ?>
                                <?php $__currentLoopData = $detil->SubDataKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($kegiatan->Uraian!='0'): ?>
                                <?php
                                $PaguKegiatan          += $kegiatan->PaguKegiatan;
                                $BelanjaKegiatan       += $kegiatan->BelanjaKegiatan;
                                $SisaKegiatan          += $kegiatan->SisaKegiatan;

                                $PaguAkhirSub_kegiatan += $kegiatan->PaguKegiatan;
                                $RealisasiSub_kegiatan += $kegiatan->BelanjaKegiatan;
                                $SisaSub_kegiatan      += $kegiatan->SisaKegiatan;


                                ?>

                                <tr>
									<td class="text-center"></td>
									<td class="text-end"></td>
									<td class="text-start">
                                        <?php echo e($kegiatan->Uraian); ?>

                                            <?php if(!empty($kegiatan->Catatan)): ?><br>
                                            <small><i><?php echo e($kegiatan->Catatan); ?></i>
                                            </small>
                                            <?php endif; ?></td>
									<td class="text-center"><?php echo e($detil->SumberDana); ?></td>
									<td class="text-end"><span class="nowrap"><?php echo e(RP($kegiatan->VolumePagu)); ?> <?php echo e($kegiatan->SatuanPagu); ?></span></td>
									<td class="text-end"><?php echo e(RP($kegiatan->PaguKegiatan)); ?></td>
                                    <td class="text-center"></td>

									<td class="text-end"><span class="nowrap"><?php if(!empty($kegiatan->VolumeBelanja)): ?><?php echo e(RP($kegiatan->VolumeBelanja)); ?> <?php echo e($kegiatan->SatuanBelanja); ?> <?php endif; ?></span></td>
									<td class="text-end"><?php echo e(RP($kegiatan->BelanjaKegiatan)); ?></td>
									<td class="text-start"><small class="nowrap"><?php echo nl2br($kegiatan->Tglsp2d); ?></small></td>

									<td class="text-center"><?php echo e(Persen($kegiatan->PersenKegiatan)); ?>%</td>
									<td class="text-end"><?php echo e(RP($kegiatan->SisaKegiatan)); ?></td>
								</tr>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $selisih_pagu      = $PaguAkhir-$PaguKegiatan;
                                    $selisih_realisasi = $Realisasi-$BelanjaKegiatan;
                                    $total_selisih     = $selisih_pagu+$selisih_realisasi;


                                ?>
                                <tr class="border-top-primary">
                                    <th class="text-center"></th>
                                    <th></th>
                                    <th class="text-start">SUB JUMLAH</th>
                                    <th class="text-center text-danger"><?php if($total_selisih!=0): ?> <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Terdapat Perbedaan Data UPT dan SPAN" class="icofont icofont-warning-alt"></i> <?php endif; ?></th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($PaguKegiatan)); ?></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($BelanjaKegiatan)); ?></th>
                                    <th></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($BelanjaKegiatan,$PaguKegiatan)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($SisaKegiatan)); ?></th>
                                </tr>

                                <?php
                                    $PaguKegiatan_satker    += $PaguKegiatan;
                                    $BelanjaKegiatan_satker += $BelanjaKegiatan;
                                    $SisaKegiatan_satker    += $SisaKegiatan;
                                ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <tr class="border-top-primary table-info">
                                    <th class="text-center"></th>
                                    <th></th>
                                    <th class="text-start">JUMLAH SATKER</th>
                                    <th class="text-center text-danger"></th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($PaguKegiatan_satker)); ?></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($BelanjaKegiatan_satker)); ?></th>
                                    <th></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($BelanjaKegiatan_satker,$PaguKegiatan_satker)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($SisaKegiatan_satker)); ?></th>
                                </tr>

                                <?php
                                    $TotalPaguAkhir          += $PaguAkhir;
                                    $TotalRealisasi          += $Realisasi;
                                    $TotalSisa               += $Sisa;
                                ?>

                                

                                <?php
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                $SisaSub      += $Sisa;
                                ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <tr class="table-danger">
									<th class="text-center"></th>
									<th class="text-start" colspan="3">JUMLAH PROPINSI <?php echo e($item->NamaHeader); ?></th>
									<th class="text-start"></th>
                                    <th class="text-end"><?php echo e(RP($PaguAkhirSub_kegiatan)); ?></th>
									<th class="text-start"></th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($RealisasiSub_kegiatan)); ?></th>
                                    <th></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($RealisasiSub_kegiatan,$PaguAkhirSub_kegiatan)*100)); ?>%</th>
                                    <th class="text-end"><?php echo e(RP($SisaSub_kegiatan)); ?></th>
                                </tr>

                                <?php
                                $PaguAkhirSub             = 0;
                                $RealisasiSub             = 0;
                                $SisaSub                  = 0;
                                $TotalPaguAkhir_kegiatan += $PaguAkhirSub_kegiatan;
                                $TotalRealisasi_kegiatan += $RealisasiSub_kegiatan;
                                $TotalSisa_kegiatan      += $SisaSub_kegiatan;

                                ?>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start" colspan="3">JUMLAH RAYA UPT</th>
									<th class="text-start"></th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir_kegiatan)); ?></th>
									<th class="text-start"></th>
                                    <th></th>
									<th class="text-end"><?php echo e(RP($TotalRealisasi_kegiatan)); ?></th>
                                    <th></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi_kegiatan,$TotalPaguAkhir_kegiatan)*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($TotalSisa_kegiatan)); ?></th>
								</tr>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start" colspan="3">JUMLAH RAYA SPAN</th>
									<th class="text-start"></th>
									<th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
									<th class="text-start"></th>
                                    <th></th>
                                    <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                                    <th></th>
                                    <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($TotalSisa)); ?></th>
								</tr>

						</table>
					</div>
				
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/covid-multi-level.blade.php ENDPATH**/ ?>