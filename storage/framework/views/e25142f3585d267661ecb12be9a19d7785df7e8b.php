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
<li class="breadcrumb-item">Padat Karya</li>
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
                      <h5 class="m-0"><?php echo e($segment); ?> Padat Karya Per <?php echo e($unit ?? ''); ?>

                      </h5>
                      <div class="card-header-right-icon">
                        <div class="row">

                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPadatKarya',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Excell</a>
                                        <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reportPadatKarya',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])); ?>">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                        <div class="table-responsive">
                            <table class="table table-sm" id="card" data-show-columns="true">
                                <thead class="bg-primary">
                                    <tr class="bg-primary">
                                        <th class="text-center" valign="middle" rowspan="2">NO</th>
                                        <th class="text-start" valign="middle" rowspan="2">KODE</th>
                                        <th class="text-center" valign="middle" rowspan="2">KETERANGAN</th>
                                        <th class="text-center" valign="middle" colspan="3">LOKASI</th>
                                        <th class="text-center" valign="middle" rowspan="2">AKUN</th>
                                        <th class="text-center" valign="middle" rowspan="2">KEGIATAN</th>
                                        <th class="text-center" colspan="9">TARGET</th>
                                        <th class="text-center" valign="middle" rowspan="2"></th>
                                        <th class="text-center" colspan="14">REALISASI</th>
                                    </tr>
                                    <tr>
                                        <th class="col-1 text-center">KABUPATEN</th>
                                        <th class="col-1 text-center">KECAMATAN</th>
                                        <th class="col-1 text-center">DESA / KELURAHAN</th>
                                        <th class="col-1 text-center">TOTAL PAGU</th>
                                        <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                                        <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                                        <th class="col-1 text-center">% BIAYA UPAH</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                                        <th class="col-1 text-center">JUMLAH HARI</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                                        <th class="col-1 text-center">JADWAL KEGIATAN</th>
                                        <th class="col-1 text-center">MEKANIME KEGIATAN</th>
                                        <th class="col-1 text-center">NO SPPD</th>
                                        <th class="col-1 text-center">TGL SPPD</th>
                                        <th class="col-1 text-center">TOTAL REALISASI</th>
                                        <th class="col-1 text-center">REALISASI KEGIATAN PENDUKUNG</th>
                                        <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                                        <th class="col-1 text-center">% BIAYA UPAH</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                                        <th class="col-1 text-center">JUMLAH HARI</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                                        <th class="col-1 text-center">REALISASI KEGIATAN</th>
                                        <th class="col-1 text-center">MEKANISME KEGIATAN</th>
                                        <th class="col-1 text-center">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $SumPagu_TotalPagu       = 0;
                                    $SumPagu_TotalBiayaLain  = 0;
                                    $SumPagu_TotalBiayaUpah  = 0;
                                    $SumPagu_JumlahOrang     = 0;
                                    $SumPagu_JumlahHari      = 0;
                                    $SumPagu_JumlahOrangHari = 0;
                                    $SumDsa_TotalPagu        = 0;
                                    $SumDsa_TotalBiayaLain   = 0;
                                    $SumDsa_TotalBiayaUpah   = 0;
                                    $SumDsa_JumlahOrang      = 0;
                                    $SumDsa_JumlahHari       = 0;
                                    $SumDsa_JumlahOrangHari  = 0;

                                ?>

                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propinsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-danger">
                                        <th><?php echo e($propinsi->KodeWilayah); ?></th>
                                        <th colspan="29"><?php echo e($propinsi->WilayahName); ?></th>
                                    </tr>
                                    <?php
                                        $PropPagu_TotalPagu       = 0;
                                        $PropPagu_TotalBiayaLain  = 0;
                                        $PropPagu_TotalBiayaUpah  = 0;
                                        $PropPagu_JumlahOrang     = 0;
                                        $PropPagu_JumlahHari      = 0;
                                        $PropPagu_JumlahOrangHari = 0;
                                        $PropDsa_TotalPagu        = 0;
                                        $PropDsa_TotalBiayaLain   = 0;
                                        $PropDsa_TotalBiayaUpah   = 0;
                                        $PropDsa_JumlahOrang      = 0;
                                        $PropDsa_JumlahHari       = 0;
                                        $PropDsa_JumlahOrangHari  = 0;

                                    ?>
                                    <?php $__currentLoopData = $propinsi->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php if(!empty($satker->realisasipadatkarya)): ?> table-success <?php else: ?> table-warning  <?php endif; ?>">
                                        <th><?php echo e($loop->iteration); ?></th>
                                        <th><?php echo e($satker->KodeSatker); ?></th>
                                        <th colspan="6"><?php echo e($satker->NamaSatuanKerja); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->TotalPagu ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->TotalBiayaLain ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->TotalBiayaUpah ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($satker->pagupadatkarya->TotalBiayaUpah ?? '0',$satker->pagupadatkarya->TotalPagu ?? '0')*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->JumlahOrang ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->JumlahHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->pagupadatkarya->JumlahOrangHari ?? '0')); ?></th>
                                        <th class="text-end"><span class="nowrap"><?php echo e(($satker->pagupadatkarya->Jadwal ?? '')); ?></span></th>
                                        <th class="text-end"><span class="nowrap"><?php echo e(($satker->pagupadatkarya->Mekanisme ?? '')); ?></span></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->TotalPagu ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->TotalBiayaLain ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->TotalBiayaUpah ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($satker->realisasipadatkarya->TotalBiayaUpah ?? '0',$satker->realisasipadatkarya->TotalPagu ?? '0')*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->JumlahOrang ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->JumlahHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($satker->realisasipadatkarya->JumlahOrangHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(($satker->realisasipadatkarya->Jadwal ?? '')); ?></th>
                                        <th class="text-end"><span class="nowrap"><?php echo e(($satker->realisasipadatkarya->Mekanisme ?? '')); ?></span></th>
                                        <th class="text-end"><span class="nowrap"><?php echo e(($satker->realisasipadatkarya->Keterangan ?? '')); ?></span></th>
                                    </tr>
                                    <?php if(count($satker->datapadatkarya)>0): ?>
                                    <?php $__currentLoopData = $satker->datapadatkarya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><span class="nowrap"><?php echo e($item->Kabupaten); ?></span></td>
                                        <td><span class="nowrap">KEC. <?php echo e(strtoupper($item->Kecamatan)); ?></span></td>
                                        <td><span class="nowrap">DES. <?php echo e(strtoupper($item->Desa)); ?></span></td>
                                        <td>
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($akun->Akun); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="nowrap"><?php echo e($akun->Uraian); ?></span><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(RP($akun->Amount)); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td colspan="9">

                                            
                                        </td>
                                        <td class="text-start">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($items->realisasi_akun->sppd)): ?>
                                            <?php $__currentLoopData = $items->realisasi_akun->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemsppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(($itemsppd->nosppd ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-start">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($items->realisasi_akun->sppd)): ?>
                                            <?php $__currentLoopData = $items->realisasi_akun->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemsppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e(\Carbon\Carbon::parse($itemsppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(RP($items->realisasi_akun->TotalPagu ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(RP($items->realisasi_akun->TotalBiayaLain ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(RP($items->realisasi_akun->TotalBiayaUpah ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(Persen($items->realisasi_akun->PersenBiayaUpah ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(($items->realisasi_akun->JumlahOrang ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(($items->realisasi_akun->JumlahHari ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(($items->realisasi_akun->JumlahOrangHari ?? '0')); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="nowrap"><?php echo e(($items->realisasi_akun->Jadwal ?? '')); ?></span><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="nowrap"><?php echo e(($items->realisasi_akun->Mekanisme ?? '')); ?></span><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="nowrap"><?php echo e(($items->realisasi_akun->Keterangan ?? '')); ?></span><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>



                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $PropPagu_TotalPagu       += $satker->pagupadatkarya->TotalPagu ?? '0';
                                    $PropPagu_TotalBiayaLain  += $satker->pagupadatkarya->TotalBiayaLain ?? '0';
                                    $PropPagu_TotalBiayaUpah  += $satker->pagupadatkarya->TotalBiayaUpah  ?? '0';
                                    $PropPagu_JumlahOrang     += $satker->pagupadatkarya->JumlahOrang ?? '0';
                                    $PropPagu_JumlahHari      += $satker->pagupadatkarya->JumlahHari ?? '0';
                                    $PropPagu_JumlahOrangHari += $satker->pagupadatkarya->JumlahOrangHari ?? '0';
                                    $PropDsa_TotalPagu        += $satker->realisasipadatkarya->TotalPagu ?? '0';
                                    $PropDsa_TotalBiayaLain   += $satker->realisasipadatkarya->TotalBiayaLain ?? '0';
                                    $PropDsa_TotalBiayaUpah   += $satker->realisasipadatkarya->TotalBiayaUpah ?? '0';
                                    $PropDsa_JumlahOrang      += $satker->realisasipadatkarya->JumlahOrang ?? '0';
                                    $PropDsa_JumlahHari       += $satker->realisasipadatkarya->JumlahHari ?? '0';
                                    $PropDsa_JumlahOrangHari  += $satker->realisasipadatkarya->JumlahOrangHari ?? '0';

                                    ?>

                                    <?php endif; ?>



                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th colspan="6">JUMLAH PROPINSI</th>
                                        <th class="text-end"><?php echo e(RP($PropPagu_TotalPagu ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropPagu_TotalBiayaLain ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropPagu_TotalBiayaUpah ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($PropPagu_TotalBiayaUpah ?? '0',$PropPagu_TotalPagu ?? '0')*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($PropPagu_JumlahOrang ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropPagu_JumlahHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropPagu_JumlahOrangHari ?? '0')); ?></th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-end"><?php echo e(RP($PropDsa_TotalPagu ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropDsa_TotalBiayaLain ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropDsa_TotalBiayaUpah ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($PropDsa_TotalBiayaUpah ?? '0',$PropDsa_TotalPagu ?? '0')*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($PropDsa_JumlahOrang ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropDsa_JumlahHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($PropDsa_JumlahOrangHari ?? '0')); ?></th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th></th>

                                    </tr>
                                    <?php
                                    $SumPagu_TotalPagu       += $PropPagu_TotalPagu;
                                    $SumPagu_TotalBiayaLain  += $PropPagu_TotalBiayaLain;
                                    $SumPagu_TotalBiayaUpah  += $PropPagu_TotalBiayaUpah ;
                                    $SumPagu_JumlahOrang     += $PropPagu_JumlahOrang;
                                    $SumPagu_JumlahHari      += $PropPagu_JumlahHari;
                                    $SumPagu_JumlahOrangHari += $PropPagu_JumlahOrangHari;
                                    $SumDsa_TotalPagu        += $PropDsa_TotalPagu;
                                    $SumDsa_TotalBiayaLain   += $PropDsa_TotalBiayaLain;
                                    $SumDsa_TotalBiayaUpah   += $PropDsa_TotalBiayaUpah;
                                    $SumDsa_JumlahOrang      += $PropDsa_JumlahOrang;
                                    $SumDsa_JumlahHari       += $PropDsa_JumlahHari;
                                    $SumDsa_JumlahOrangHari  += $PropDsa_JumlahOrangHari;

                                    ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th></th>
                                        <th></th>
                                        <th colspan="6">TOTAL</th>
                                        <th class="text-end"><?php echo e(RP($SumPagu_TotalPagu ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumPagu_TotalBiayaLain ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumPagu_TotalBiayaUpah ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($SumPagu_TotalBiayaUpah ?? '0',$SumPagu_TotalPagu ?? '0')*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($SumPagu_JumlahOrang ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumPagu_JumlahHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumPagu_JumlahOrangHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(($SumPagu_Jadwal ?? '')); ?></th>
                                        <th class="text-end"><?php echo e(($SumPagu_Mekanisme ?? '')); ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th class="text-end"><?php echo e(RP($SumDsa_TotalPagu ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumDsa_TotalBiayaLain ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumDsa_TotalBiayaUpah ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(Persen(divnum($SumDsa_TotalBiayaUpah ?? '0',$SumDsa_TotalPagu ?? '0')*100)); ?>%</th>
                                        <th class="text-end"><?php echo e(RP($SumDsa_JumlahOrang ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumDsa_JumlahHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(RP($SumDsa_JumlahOrangHari ?? '0')); ?></th>
                                        <th class="text-end"><?php echo e(($SumDsa_Jadwal ?? '')); ?></th>
                                        <th class="text-end"><?php echo e(($SumDsa_Mekanisme ?? '')); ?></th>
                                        <th></th>

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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/padatkarya-rekap-rincian-level.blade.php ENDPATH**/ ?>