<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Monitoring</li>
<li class="breadcrumb-item active">RPD MP</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Monitoring RPD MP</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'satker','segment'=>'rpd'])); ?>">Excell</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
				
                    <div class="table-responsive">
						<table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">NAMA SATKER</th>
									<th class="text-end">PAGU PNBP</th>
									<th class="text-end">MP PNBP</th>
									<th class="text-center">% MP</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
                                    <th class="text-center">JAN</th>
									<th class="text-center">FEB</th>
									<th class="text-center">MAR</th>
									<th class="text-center">APR</th>
									<th class="text-center">MEI</th>
									<th class="text-center">JUN</th>
									<th class="text-center">JUL</th>
									<th class="text-center">AGS</th>
									<th class="text-center">SEP</th>
									<th class="text-center">OKT</th>
									<th class="text-center">NOV</th>
									<th class="text-center">DES</th>
									<th class="text-center">JUMLAH</th>
									<th class="text-center">SELISIH</th>

								</tr>
							</thead>
							<tbody>
                                <?php
                                    $sumPagu      = 0;
                                    $sumMP        = 0;
                                    $sumRealisasi = 0;
                                    $sumRpdJan    = 0;
                                    $sumRpdFeb    = 0;
                                    $sumRpdMar    = 0;
                                    $sumRpdApr    = 0;
                                    $sumRpdMei    = 0;
                                    $sumRpdJun    = 0;
                                    $sumRpdJul    = 0;
                                    $sumRpdAgs    = 0;
                                    $sumRpdSep    = 0;
                                    $sumRpdOkt    = 0;
                                    $sumRpdNov    = 0;
                                    $sumRpdDes    = 0;
                                    $sumRpd       = 0;
                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-danger">
									<td class="text-center"><b><?php echo e($item->KodeWilayah); ?></b></td>
									<td class="text-start" colspan="21"><b><?php echo e($item->WilayahName); ?></b></td>
                                </tr>
                                <?php
                                    $noSatker          = 0;
                                    $Prop_sumPagu      = 0;
                                    $Prop_sumMP        = 0;
                                    $Prop_sumRealisasi = 0;
                                    $Prop_RpdJan       = 0;
                                    $Prop_RpdFeb       = 0;
                                    $Prop_RpdMar       = 0;
                                    $Prop_RpdApr       = 0;
                                    $Prop_RpdMei       = 0;
                                    $Prop_RpdJun       = 0;
                                    $Prop_RpdJul       = 0;
                                    $Prop_RpdAgs       = 0;
                                    $Prop_RpdSep       = 0;
                                    $Prop_RpdOkt       = 0;
                                    $Prop_RpdNov       = 0;
                                    $Prop_RpdDes       = 0;
                                    $Prop_SumRpd       = 0;

                                ?>
                                <?php $__currentLoopData = $item->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $pagu               = $satker->pagupnbp->Jumlah ?? '0';
                                $mp                 = $satker->mp->Jumlah ?? '0';
                                $realisasi          = $satker->belanjapnbp->Jumlah ?? '0';
                                $rpd                = $satker->rpdpnbp->TOTAL ?? '0';
                                $Prop_sumPagu      += $pagu;
                                $Prop_sumMP        += $mp;
                                $Prop_sumRealisasi += $realisasi;
                                $Prop_RpdJan       += $satker->rpdpnbp->JAN ?? '0';
                                $Prop_RpdFeb       += $satker->rpdpnbp->FEB ?? '0';
                                $Prop_RpdMar       += $satker->rpdpnbp->MAR ?? '0';
                                $Prop_RpdApr       += $satker->rpdpnbp->APR ?? '0';
                                $Prop_RpdMei       += $satker->rpdpnbp->MEI ?? '0';
                                $Prop_RpdJun       += $satker->rpdpnbp->JUN ?? '0';
                                $Prop_RpdJul       += $satker->rpdpnbp->JUL ?? '0';
                                $Prop_RpdAgs       += $satker->rpdpnbp->AGS ?? '0';
                                $Prop_RpdSep       += $satker->rpdpnbp->SEP ?? '0';
                                $Prop_RpdOkt       += $satker->rpdpnbp->OKT ?? '0';
                                $Prop_RpdNov       += $satker->rpdpnbp->NOV ?? '0';
                                $Prop_RpdDes       += $satker->rpdpnbp->DES ?? '0';
                                $Prop_SumRpd       += $satker->rpdpnbp->TOTAL ?? '0';
                                ?>

                                <tr>
									<td class="text-center"><?php echo e($noSatker=$noSatker+1); ?></td>
									<td class="text-center"><?php echo e($satker->KodeSatker); ?></td>
									<td class="text-start"><?php echo e($satker->NamaSatuanKerja); ?></td>
									<td class="text-end"><?php echo e(RP($satker->pagupnbp->Jumlah ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->mp->Jumlah ?? '0')); ?></td>
                                    <td class="text-end"><?php echo e(Persen(divnum($satker->mp->Jumlah ?? '0',$satker->pagupnbp->Jumlah ?? '0')*100)); ?>%</td>
									<td class="text-end"><?php echo e(RP($satker->belanjapnbp->Jumlah ?? '0')); ?></td>
                                    <td class="text-end"><?php echo e(Persen(divnum($satker->belanjapnbp->Jumlah ?? '0',$satker->mp->Jumlah ?? '0')*100)); ?>%</td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->JAN ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->FEB ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->MAR ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->APR ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->MEI ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->JUN ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->JUL ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->AGS ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->SEP ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->OKT ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->NOV ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->DES ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($satker->rpdpnbp->TOTAL ?? '0')); ?></td>
									<td class="text-end"><?php echo e(RP($pagu-$rpd)); ?></td>
								</tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $sumPagu      += $Prop_sumPagu;
                                            $sumMP        += $Prop_sumMP;
                                            $sumRealisasi += $Prop_sumRealisasi;
                                            $sumRpdJan    += $Prop_RpdJan;
                                            $sumRpdFeb    += $Prop_RpdFeb;
                                            $sumRpdMar    += $Prop_RpdMar;
                                            $sumRpdApr    += $Prop_RpdApr;
                                            $sumRpdMei    += $Prop_RpdMei;
                                            $sumRpdJun    += $Prop_RpdJun;
                                            $sumRpdJul    += $Prop_RpdJul;
                                            $sumRpdAgs    += $Prop_RpdAgs;
                                            $sumRpdSep    += $Prop_RpdSep;
                                            $sumRpdOkt    += $Prop_RpdOkt;
                                            $sumRpdNov    += $Prop_RpdNov;
                                            $sumRpdDes    += $Prop_RpdDes;
                                            $sumRpd       += $Prop_SumRpd;
                                        ?>

                                    <tr class="table-primary">
                                        <th class="text-center"></th>
                                        <th class="text-start"></th>
                                        <th class="text-start">JUMLAH PROP</th>
                                        <th class="text-end"><?php echo e(RP($Prop_sumPagu)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_sumMP)); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($Prop_sumMP,$Prop_sumPagu)*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($Prop_sumRealisasi)); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($Prop_sumRealisasi,$Prop_sumPagu)*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdJan)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdFeb)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdMar)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdApr)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdMei)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdJun)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdJul)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdAgs)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdSep)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdOkt)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdNov)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_RpdDes)); ?></th>
                                        <th class="text-end"><?php echo e(RP($Prop_SumRpd)); ?></th>
                                        <th class="text-center"><?php echo e(RP($Prop_sumPagu-$Prop_SumRpd)); ?></th>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>

                            <tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
									<th class="text-end"><?php echo e(RP($sumPagu)); ?></th>
									<th class="text-end"><?php echo e(RP($sumMP)); ?></th>
                                    <th class="text-end"><?php echo e(Persen(divnum($sumMP,$sumPagu)*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($sumRealisasi)); ?></th>
                                    <th class="text-end"><?php echo e(Persen(divnum($sumRealisasi,$sumPagu)*100)); ?>%</th>
									<th class="text-end"><?php echo e(RP($sumRpdJan)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdFeb)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdMar)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdApr)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdMei)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdJun)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdJul)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdAgs)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdSep)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdOkt)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdNov)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpdDes)); ?></th>
									<th class="text-end"><?php echo e(RP($sumRpd)); ?></th>
									<th class="text-end"><?php echo e(RP($sumPagu-$sumRpd)); ?></th>

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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/monitoring-rpd-mp.blade.php ENDPATH**/ ?>