<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">

<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/tour.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Monitoring Covid</li>
<li class="breadcrumb-item active">Satker</li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h5>Monitoring Kegiatan Padat Karya</h5>
                        </div>
                        <div class="col-4 text-end dropup-basic">
                            <a data-intro="Tambah Kegiatan" title="Tambah Data" href="#" class="p-20 text-primary open-modal-monitoring" action="insertPadatKarya" id="0"><i data-feather="plus-circle"></i></a>
                            
                                <a target="_blank" href="<?php echo e(route(Auth::user()->ba.'/reporting',['type'=>'pdf','unit'=>'satker','segment'=>Request::route('segment'), 'month'=>'1'])); ?>">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                </a>
                            

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="loadrpd">
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="row">
                        <div class="col-10 mb-3">
                            <h6>KEGIATAN PADAT KARYA</h6>
                            DESA <?php echo e(strtoupper($item->Desa)); ?><br>
                            KECAMATAN <?php echo e(strtoupper($item->Kecamatan)); ?><br>
                            <?php echo e($item->Kabupaten); ?><br>
                            PROPINSI <?php echo e($item->Propinsi); ?>

                        </div>
                        <div class="col-2 border-1 text-end">
                        <a data-intro="Edit Data" title="Edit Data Kegiatan" class="open-modal-monitoring static text-primary" id="<?php echo e($item->guid); ?>" action="editKegiatanPadatKarya" href="#" output="" pagu=""><i class="icofont icofont-info-circle fa-2x"></i></a>
                        <a data-intro="Hapus Kegiatan" title="Hapus Data Kegiatan" class="static text-danger" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/monitoring/status',['status'=>'0', 'id'=> $item->guid,'what'=>'kegiatanPK'])); ?>"><i class="icofont icofont-close-circled fa-2x"></i></a></div>

                    <ul class="list-group mb-5">
                        <li class="list-group-item bg-primary"><i class="icon-location-pin"></i>LOKASI KEGIATAN PADAT KARYA</li>
                        <li class="list-group-item">
                            <?php
                                $lokasi = "Desa ".$item->Desa. ", Kecamatan ".$item->Kecamatan. ", Kabupaten".$item->Kabupaten.", Propinsi".$item->Kecamatan;
                            ?>
                            <iframe
                            width="100%"
                            height="200"
                            frameborder="0" style="border:0"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDqaagiEpfM3wDfZlX7inQbJpGUQVarOZg
                                &q=<?php echo e($lokasi); ?>" allowfullscreen>
                            </iframe>

                        </li>

                        <li class="list-group-item list-group-item-success"><i class="icon-credit-card"></i>TARGET KEGIATAN</li>
                        <li class="list-group-item">
                            <?php if(!empty($item->realisasi) AND count($item->realisasi)>0): ?>
                            <div class="row">
                                <div class="col-xl-6 col-sm-12 text-bold"><h6 class="mt-3 mb-3">TARGET</h6>
                                    <ul class="mt-2 mb-2">
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan<span class="badge badge-primary"><?php echo e($item->Jadwal); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary"><?php echo e($item->Mekanisme); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info"><?php echo e($item->JumlahOrang); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info"><?php echo e($item->JumlahHari); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info"><?php echo e($item->JumlahOrangHari); ?></span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark"><?php echo e(RP($item->UpahHarian)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success"><?php echo e(RP($item->TotalBiayaUpah)); ?></span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary"><?php echo e(Persen($item->PersenBiayaUpah)); ?> %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success"><?php echo e(RP($item->TotalBiayaLain)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan<span class="badge badge-success"><?php echo e(RP($item->TotalPagu)); ?></span></li>

                                    </ul>
                                </div>
                                <div class="col-xl-6 col-sm-12 text-bold"><h6 class="mt-3 mb-3">REALISASI</h6>
                                    <ul class="mt-2 mb-2">
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan<span class="badge badge-primary"><?php echo e($item->sumrealisasi->Jadwal); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary"><?php echo e($item->sumrealisasi->Mekanisme); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info"><?php echo e($item->sumrealisasi->JumlahOrang); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info"><?php echo e($item->sumrealisasi->JumlahHari); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info"><?php echo e($item->sumrealisasi->JumlahOrangHari); ?></span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark"><?php echo e(RP($item->sumrealisasi->UpahHarian)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success"><?php echo e(RP($item->sumrealisasi->TotalBiayaUpah)); ?></span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary"><?php echo e(Persen($item->sumrealisasi->PersenBiayaUpah)); ?> %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success"><?php echo e(RP($item->sumrealisasi->TotalBiayaLain)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan<span class="badge badge-success"><?php echo e(RP($item->sumrealisasi->TotalPagu)); ?></span></li>

                                    </ul>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="row">
                                <div class="col-xl-12 col-sm-12 text-bold"><h6 class="mt-3 mb-3">TARGET</h6>
                                    <ul class="mt-2 mb-2">
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan<span class="badge badge-primary"><?php echo e($item->Jadwal); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary"><?php echo e($item->Mekanisme); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info"><?php echo e($item->JumlahOrang); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info"><?php echo e($item->JumlahHari); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info"><?php echo e($item->JumlahOrangHari); ?></span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark"><?php echo e(RP($item->UpahHarian)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success"><?php echo e(RP($item->TotalBiayaUpah)); ?></span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary"><?php echo e(Persen($item->PersenBiayaUpah)); ?> %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success"><?php echo e(RP($item->TotalBiayaLain)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan<span class="badge badge-success"><?php echo e(RP($item->TotalPagu)); ?></span></li>

                                    </ul>
                                </div>
                            </div>
                            <?php endif; ?>
                        </li>
                        <li class="list-group-item list-group-item-warning"><i class="icon-credit-card"></i>RINCIAN AKUN KEGIATAN</li>
                        <li class="list-group-item">
                            <div class="row p-10 table-responsive">
                                <table class="table table-bordered">
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Akun</th>
                                    <th class="text-start">Keterangan Akun / Kegiatan</th>
                                    <th class="text-end">Pagu</th>
                                    <th class="text-end">Realisasi</th>
                                    <th class="text-end">Sisa</th>
                                    </tr>
                                    <?php
                                        $total_pagu = 0;
                                        $total_dsa  = 0;
                                        $total_sisa = 0;
                                    ?>
                                    <?php $__currentLoopData = $item->akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $pagu      = $kegiatan->Amount ?? '0';
                                        $realisasi = $kegiatan->realisasi->TotalPagu ?? '0';
                                        $sisa      = $pagu - $realisasi;
                                        $total_pagu += $pagu;
                                        $total_dsa  += $realisasi;
                                        $total_sisa += $sisa;

                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><?php echo e($kegiatan->Akun); ?></td>
                                        <td class="text-start"><?php echo e($kegiatan->akun->NamaAkun ?? ''); ?><br><small><i><?php echo e($kegiatan->Uraian); ?></i></small></td>
                                        <td class="text-end"><?php echo e(RP($kegiatan->Amount)); ?></td>
                                        <td class="text-end">
                                            <?php if(!empty($kegiatan->realisasi_akun)): ?>
                                            <?php echo e(RP($kegiatan->realisasi_akun->TotalPagu ?? '0')); ?>

                                            <?php else: ?>
                                            <a akun="<?php echo e($kegiatan->Id); ?>" id="<?php echo e($kegiatan->idtable); ?>.<?php echo e($kegiatan->guid); ?>" data-intro="Input Realisasi" title="Input Realisasi" class="open-modal-monitoring static text-primary" action="realisasiPadatKarya" pagu="<?php echo e($kegiatan->Amount); ?>" sisa="<?php echo e($kegiatan->Id); ?>.<?php echo e($kegiatan->Kewenangan); ?>.<?php echo e($kegiatan->Program); ?>" href="#" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end"><?php echo e(RP($sisa)); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($item->akun)>1): ?>
                                    <tfoot>
                                        <tr class="table-primary">
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                        <th class="text-start">Jumlah</th>
                                        <th class="text-end"><?php echo e(RP($total_pagu)); ?></th>
                                        <th class="text-end"><?php echo e(RP($total_dsa)); ?></th>
                                        <th class="text-end"><?php echo e(RP($total_sisa)); ?></th>
                                        </tr>
                                    </tfoot>
                                    <?php endif; ?>
                                </table>

                            </div>
                        </li>
                        <li class="list-group-item list-group-item-info"><i class="icon-credit-card"></i>RINCIAN REALISASI KEGIATAN</li>
                        <li class="list-group-item">
                            <div class="row">

                                <div class="col-xl-12 col-sm-12 text-bold">

                                    <ul class="mt-0 mb-2">
                                        <br>
                                        <?php $__currentLoopData = $item->realisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemRealisasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row">
                                            <div class="col-8">
                                                <h6 class="mt-3 mb-3">REALISASI KEGIATAN <?php echo e(strtoupper($itemRealisasi->akun->Uraian ?? '')); ?></h6>
                                            </div>
                                            <div class="col-4 pt-3 text-end">
                                                <?php if(count($item->realisasi)>0): ?>
                                                <a data-intro="Edit Data" title="Edit Data Realisasi" class="open-modal-monitoring static text-primary" id="<?php echo e($itemRealisasi->guid_sppd); ?>" action="editRPadatKarya" href="#" output="<?php echo e($itemRealisasi->TotalPagu); ?>" pagu="<?php echo e($itemRealisasi->TotalPaguDipa); ?>"><i class="icofont icofont-info-circle fa-2x"></i></a>
                                                <a data-intro="Hapus Data" title="Hapus Data Realisasi" class="static text-danger" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/monitoring/status',['status'=>'0', 'id'=> $itemRealisasi->guid_sppd,'what'=>'realisasiPK'])); ?>"><i class="icofont icofont-close-circled fa-2x"></i></a></a>
                                                <?php endif; ?>
                                            </div>
                                            </div>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Akun : <?php echo e($itemRealisasi->akun->akun->KdAkun ?? ''); ?> - <?php echo e($itemRealisasi->akun->akun->NamaAkun ?? ''); ?> <span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalPaguDipa)); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Realisasi Pelaksanaan<span class="badge badge-primary"><?php echo e($itemRealisasi->Jadwal); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary"><?php echo e($itemRealisasi->Mekanisme); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info"><?php echo e($itemRealisasi->JumlahOrang); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info"><?php echo e($itemRealisasi->JumlahHari); ?></span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info"><?php echo e($itemRealisasi->JumlahOrangHari); ?></span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark"><?php echo e(RP($itemRealisasi->UpahHarian)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalBiayaUpah)); ?></span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary"><?php echo e(Persen($itemRealisasi->PersenBiayaUpah)); ?> %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalBiayaLain)); ?></span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Realisasi Kegiatan<span class="badge badge-success"><?php echo e(RP($itemRealisasi->TotalPagu)); ?></span></li>
                                        <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Sisa Dana <span class="badge badge-danger"><?php echo e(RP($itemRealisasi->TotalPaguDipa-$itemRealisasi->TotalPagu)); ?></span></li>
                                        <?php $__currentLoopData = $itemRealisasi->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Data SP2D  Tanggal : <?php echo e(\Carbon\Carbon::parse($sppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')); ?> Nomor. <?php echo e($sppd->nosppd); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">Catatan : <?php echo e(($itemRealisasi->Keterangan)); ?></li>
                                        <br>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                    </ul>
                                </div>

                        </li>
                      </ul>
                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
                </div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/tour/intro.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/tour/intro-init.js')); ?>"></script>


<script>
$('body').on('blur','.pagukegiatan,.hari,.upah,.totalbiayalain,.validasi',function(){
    var sum  = 0;
    $('.pagukegiatan').each(function() {
    sum += Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.totalpagu').val(sum);

    $('.orang').each(function() {
    orang = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.hari').each(function() {
    hari = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var oranghari = orang*hari;
    $('.modal-content-monitoring #oranghari').val((oranghari));
    $('.upah').each(function() {
    upah = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayaupah = oranghari*upah;
    // alert(biayaupah);
    $('.modal-content-monitoring #biayaupah').val(biayaupah);

    $('.totalpagu').each(function() {
    jumlahpagu = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayalain = (jumlahpagu-biayaupah);
    // alert((biayalain));
    $('.modal-content-monitoring #biayalain').val(biayalain);

    var persenUpah = (biayaupah/jumlahpagu*100);
    var Persen = persenUpah.toFixed(2);
    // alert(persenUpah);
    $('.modal-content-monitoring .persenupah').html(Persen+'%');

    $('.totalbiayalain').each(function() {
        totalbiayalain = Number($(this).val().replace(/[^0-9]+/g,""));
    });

    var jumlahRealisasi = biayaupah+totalbiayalain;
    // alert(jumlahRealisasi);
    $('.modal-content-monitoring #jumlahRealisasi').val(jumlahRealisasi);

    var sisaRealisasi = jumlahpagu-jumlahRealisasi;
    // alert(sisaRealisasi);
    $('.modal-content-monitoring #sisaRealisasi').val(sisaRealisasi);

});

$('body').on('click','.validasi',function(){
    var sum  = 0;
    $('.pagukegiatan').each(function() {
    sum += Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.totalpagu').val(sum);

    $('.orang').each(function() {
    orang = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.hari').each(function() {
    hari = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var oranghari = orang*hari;
    $('.modal-content-monitoring #oranghari').val((oranghari));
    $('.upah').each(function() {
    upah = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayaupah = oranghari*upah;
    // alert(biayaupah);
    $('.modal-content-monitoring #biayaupah').val(biayaupah);

    $('.totalpagu').each(function() {
    jumlahpagu = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayalain = (jumlahpagu-biayaupah);
    // alert((biayalain));
    $('.modal-content-monitoring #biayalain').val(biayalain);

    var persenUpah = (biayaupah/jumlahpagu*100);
    var Persen = persenUpah.toFixed(2);
    // alert(persenUpah);
    $('.modal-content-monitoring .persenupah').html(Persen+'%');

    $('.totalbiayalain').each(function() {
        totalbiayalain = Number($(this).val().replace(/[^0-9]+/g,""));
    });

    var jumlahRealisasi = biayaupah+totalbiayalain;
    // alert(jumlahRealisasi);
    $('.modal-content-monitoring #jumlahRealisasi').val(jumlahRealisasi);

    var sisaRealisasi = jumlahpagu-jumlahRealisasi;
    // alert(sisaRealisasi);
    $('.modal-content-monitoring #sisaRealisasi').val(sisaRealisasi);

});

$(document).ready(function(){

    $('body').on('click','.addakun',function(e){
        e.preventDefault();
                $(document).ready(function() {
                        feather.replace();
                    });
        var html  = '';
            html += '<div class="box"><div class="row mt-3">';
            html += '<div class="col-8"><h6 class="">KEGIATAN </h6></div>';
            html += '<div title="Tambah Akun Kegiatan" class="col-4 text-danger text-end"><a class="removeakun" href="#"><i data-feather="minus-circle"></i></a></div>';
            html += '<hr class="mt-2 mb-2">';
            html += '<div class="mb-2 col-xl-7 col-sm-xl">';
            html += '<label class="form-label">Akun Belanja</label>';
            html += '<select style="font-size:14px" required class="form-control select col-sm-12" name="akun[]">';
            html += '<option value="">Pilih Akun Belanja</option>';
            html += '<?php $__currentLoopData = $akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($item->Id); ?>.<?php echo e($item->Kewenangan); ?>.<?php echo e($item->Program); ?>"><?php echo e($item->Id); ?> : <?php echo e($item->keterangan->NamaAkun); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>';
            html += '</select>';
            html += '</div>';
            html += '<div class="mb-2 col-xl-5 col-sm-12">';
            html += '<label class="form-label">Jumlah Pagu Kegiatan</label>';
            html += '<div class="input-group">';
            html += '<span class="input-group-text"><i data-feather="credit-card"></i></span>';
            html += '<input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagukegiatan" name="pagukegiatan[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="mb-2">';
            html += '<label class="form-label">Nama Kegiatan Padat Karya</label>';
            html += '<div class="input-group">';
            html += '<input required style="text-align:left; font-size:14px; padding:9px !important;" type="text" class="form-control" name="kegiatan[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';

        $(".newData").append(html);
        $('.select').select2({
                    dropdownParent: $('#open-modal-monitoring .box'),
                    tags: true
                });

    });

    $('body').on('click','.removeakun',function(){
    $(this).closest('.box').remove();
    });


    $('body').on('click','.addsp2d',function(e){
        e.preventDefault();
        $(document).ready(function() {
                        feather.replace();
                    });
        var html  = '';
            html += '<div class="boxsp2d"><div class="row">';
            html += '<div class="col-8"></div>';
            html += '<div title="Tambah Akun Kegiatan" class="col-4 text-danger text-end"><a class="removesp2d" href="#"><i data-feather="minus-circle"></i></a></div>';
            html += '<div class="mb-2 col-sm-5">';
            html += '<label class="form-label">Tanggal SP2D</label>';
            html += '<div class="input-group">';
            html += '<span class="input-group-text"><i data-feather="calendar"></i></span>';
            html += '<input required name="tanggal[]" style="font-size:14px; background-color:#fff;" readonly class="datepicker-append form-control" type="text" data-language="en">';
            html += '</div>';
            html += '</div>';
            html += '<div class="mb-2 col-sm-7">';
            html += '<label class="form-label">Nomor SP2D</label>';
            html += '<div class="input-group">';
            html += '<span class="input-group-text"><i data-feather="file-text"></i></span>';
            html += '<input maxlength="25" required style="font-size:14px" type="text" class="form-control number" name="nosp2d[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

        $(".newDatasp2d").append(html);

        $(".datepicker-append").datepicker();
        feather.replace();


    });

    $('body').on('click','.removesp2d',function(){
    $(this).closest('.boxsp2d').remove();
    });

});

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/monitoring-padat-karya.blade.php ENDPATH**/ ?>