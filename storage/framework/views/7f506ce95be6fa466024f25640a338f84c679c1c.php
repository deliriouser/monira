<?php switch($action):
    case ('insertKegiatanCovid'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="kegiatan">
        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning output text-center"></a>
                <a class="list-group-item list-group-item-primary akun text-center"></a>
                <a class="list-group-item list-group-item-success dana text-center"></a>
                <a class="list-group-item list-group-item-grey pagu text-center"></a>
            </div>

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="id">


            <div class="mb-3">
                <label class="form-label">Pagu Anggaran Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input required style="text-align:right; font-size:14px" type="text" class="form-control number" name="pagu">
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Volume Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="layers"></i></span>
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number" name="volume">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Satuan</label>
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="satuan">
                        <option value="">Pilih</option>
                        <?php $__currentLoopData = $satuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($item->Satuan); ?>"><?php echo e($item->Satuan); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Uraian Kegiatan / Barang</label>
                <div class="input-group">
                    <textarea required style="font-size:14px;" class='form-control' name='kegiatan' rows='2'></textarea>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan / Keterangan</label>
                <div class="input-group">
                    <textarea style="font-size:14px;" class='form-control' name='catatan' rows='3'></textarea>
                </div>
            </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>
    </form>

    <?php break; ?>

    <?php case ('updateKegiatanCovid'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="updatekegiatan">
        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning output text-center"></a>
                <a class="list-group-item list-group-item-primary akun text-center"></a>
                <a class="list-group-item list-group-item-success dana text-center"></a>
                <a class="list-group-item list-group-item-grey pagu text-center"></a>
            </div>

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="guid">
            <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">


            <div class="mb-3">
                <label class="form-label">Pagu Anggaran Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input required style="text-align:right; font-size:14px" type="text" class="form-control number" name="pagu" value="<?php echo e(RP($data->Amount)); ?>">
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Volume Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="layers"></i></span>
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number" name="volume" value="<?php echo e($data->Volume); ?>">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Satuan</label>
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="satuan">
                        <option value="">Pilih</option>
                        <?php $__currentLoopData = $satuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php if($data->Satuan==$item->Satuan): ?> selected <?php endif; ?> value="<?php echo e($item->Satuan); ?>"><?php echo e($item->Satuan); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Uraian Kegiatan / Barang</label>
                <div class="input-group">
                    <textarea required style="font-size:14px;" class='form-control' name='kegiatan' rows='2'><?php echo e($data->Uraian); ?></textarea>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan / Keterangan</label>
                <div class="input-group">
                    <textarea style="font-size:14px;" class='form-control' name='catatan' rows='3'>
                    <?php echo e($data->BudgetType); ?></textarea>
                </div>
            </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>
    </form>

    <?php break; ?>

    <?php case ('insertRealisasiCovid'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="realisasi">

        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning dana text-center"></a>
                <a class="list-group-item list-group-item-primary pagu text-center"></a>
            </div>

            <input type="hidden" id="pagu">
            <input type="hidden" name="guid" id="akun">
            <input type="hidden" id="sisa">
            <input type="hidden" id="id" name="id">
            <div class="row">

                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Rupiah Realisasi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number realisasiCovid" name="realisasi">
                    </div>
                </div>

                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Volume Kegiatan</label>
                    <div class="input-group">
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number" name="volume">
                        <span class="input-group-text output"></span>
                    </div>
                </div>

            </div>
            <div class="row">
            <div class="mb-3 col-sm-6">
                <label class="form-label">Tanggal SP2D</label>
                <input required name="bulan" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control" type="text" data-language="en">

            </div>
            <div class="mb-3 col-sm-6">
                <label class="form-label">Nomor SP2D</label>
                <input maxlength="25" required style="font-size:14px" type="text" class="form-control number" name="nosp2d">

            </div>
            </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>
    </form>

    <?php break; ?>


    <?php case ('dataRealisasiCovid'): ?>
        <input type="hidden" name="type" value="realisasi">

        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">

            <div class="table-responsive">
                <table class="table table-sm" id="page-all">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">TGL / NO SP2D</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-center">VOLUME</th>
                            <th class="text-center">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php if(count($data)>0): ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="text-start"><?php echo e(\Carbon\Carbon::parse($item->Bulan ?? '')->isoFormat('DD/MM/YYYY')); ?><br><?php echo e($item->Nosp2d ?? ''); ?></td>
                            <td class="text-end"><?php echo e(RP($item->Amount)); ?></td>
                            <td class="text-center"><?php echo e(($item->Volume)); ?> <?php echo e(($item->Satuan)); ?></td>
                            <td class="text-center">
                                <a onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/monitoring/status',['status'=>'0', 'id'=> Crypt::encrypt($item->idtable),'what'=>'realisasi'])); ?>" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td class="text-center" colspan="5">Belum Ada Data Realisasi</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if(count($data)>0): ?>
                    <tfoot class="table-primary">
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">Total</th>
                            <th class="text-end"><?php echo e(RP($data->sum('Amount') ?? '')); ?></th>
                            <th class="text-center"><?php echo e($data->sum('Volume') ?? ''); ?> <?php echo e(($item->Satuan ?? '')); ?></th>
                            <th class="text-center">...</th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
         </div>

    <?php break; ?>

    <?php case ('insertPadatKarya'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="padatkarya">
        <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">

        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row">
                <div class="col-8"><h6 class="">KEGIATAN</h6></div>
                <div title="Tambah Akun Kegiatan" class="col-4 text-primary text-end"><a class="addakun" href="#"><i data-feather="plus-circle"></i></a></div>
            </div>
            <hr class="mt-2 mb-3">
            <div class="row">
            <div class="mb-3 col-xl-7 col-sm-12">
                <label class="form-label">Akun Belanja</label>
                <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="akun[]">
                    <option value="">Pilih Akun Belanja</option>
                    <?php $__currentLoopData = $akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($item->Id); ?>.<?php echo e($item->Kewenangan); ?>.<?php echo e($item->Program); ?>"><?php echo e($item->Id); ?> : <?php echo e($item->keterangan->NamaAkun); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3 col-xl-5 col-sm-12">
                <label class="form-label">Jumlah Pagu Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagukegiatan" name="pagukegiatan[]" value="">
                </div>
            </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kegiatan Padat Karya</label>
                <div class="input-group">
                    <input required style="text-align:left; font-size:14px; padding:9px !important;" type="text" class="form-control" name="kegiatan[]">
                </div>
            </div>
            <div class="newData"></div>
            <h6 class="mt-4">RINCIAN KEGIATAN</h6>
            <hr class="mt-2 mb-3">
            <div class="mb-3">
                <label class="form-label">Total Pagu</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control totalpagu number" name="totalpagu">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi Kegiatan</label>
                <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="kdlokasi">
                    <option value="">Pilih Desa/Kecamatan Lokasi</option>
                    <?php $__currentLoopData = $lokasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(strlen($item->kode)==5): ?>
                        <option disabled value="<?php echo e($item->kode); ?>"><?php echo e($item->Nama); ?></option>
                    <?php elseif(strlen($item->kode)==8): ?>
                        <option disabled value="<?php echo e($item->kode); ?>">Kecamatan <?php echo e($item->Nama); ?></option>
                    <?php elseif(strlen($item->kode)>8): ?>
                        <option value="<?php echo e($item->kode); ?>">Kelurahan / Desa <?php echo e($item->Nama); ?></option>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>


            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Rencana Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option value="Swakelola">Swakelola</option>
                            <option value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="150.000">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah">
                        <span class="input-group-text persenupah" style="font-size:14px;"><?php echo e(Persen($item->PersenBiayaUpah)); ?></span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayalain number" id="biayalain" name="biayalain">
                    </div>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    <?php break; ?>



    <?php case ('realisasiPadatKarya'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="realisasipadatkarya">
        <input type="hidden" name="id" id="sisa" value="0000.000.000000.0.0.0000000">
        <input type="hidden" id="id" name="guid">
        <input type="hidden" id="akun" name="akun">
        

        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Jumlah Pagu Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" id="pagu" class="form-control number totalpagu pagukegiatan" name="pagu">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="hidden" disabled class="form-control number totalpagu">
                </div>
                </div>

            

            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option value="Swakelola">Swakelola</option>
                            <option value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="150.000">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah">
                        <span class="input-group-text persenupah" style="font-size:14px;"></span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number totalbiayalain" name="totalbiayalain">
                    </div>
                </div>
            </div>
                <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Jumlah Realisasi Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number jumlahRealisasi" id="jumlahRealisasi" name="jumlahRealisasi">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Sisa Anggaran Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number sisaRealisasi" id="sisaRealisasi" name="sisaRealisasi">
                    </div>
                </div>
                </div>

                <div class="row mt-3">
                    <div class="col-8"><h6 class="">RINCIAN PEMBAYARAN</h6></div>
                    <div title="Tambah No SP2D" class="col-4 text-primary text-end"><a class="addsp2d" href="#"><i data-feather="plus-circle"></i></a></div>
                </div>
                <hr class="mt-2 mb-2">


                <div class="row">
                    <div class="mb-3 col-sm-5">
                        <label class="form-label">Tanggal SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                        <input required name="tanggal[]" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control" type="text" data-language="en">
                        </div>
                    </div>
                    <div class="mb-3 col-sm-7">
                        <label class="form-label">Nomor SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="file-text"></i></span>
                        <input maxlength="25" required style="font-size:14px" type="text" class="form-control number" name="nosp2d[]">
                        </div>
                    </div>
                </div>
                <div class="newDatasp2d"></div>

                <div class="mb-3">
                    <label class="form-label">Keterangan / Kendala Pelaksanaan</label>
                    <div class="input-group">
                        <textarea style="font-size:14px;" class='form-control' name='keterangan' rows='3'></textarea>
                    </div>
                </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    <?php break; ?>

    <?php case ('editRPadatKarya'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="editrealisasipadatkarya">
        <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">
        <input type="hidden" id="id" name="guid">
        <input type="hidden" name="guid_sppd" value="<?php echo e($item->guid_sppd); ?>">

        

        <input style="text-align:right; font-size:14px; padding:9px !important;" type="hidden" class="form-control number pagukegiatan" name="pagukegiatan[]" value="<?php echo e(RP($item->TotalPaguDipa)); ?>">

        



        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Total Pagu Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagu totalpagu" id="pagu" name="pagu">
                </div>
            </div>

            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal" value="<?php echo e(($item->Jadwal)); ?>">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option <?php if($item->Mekanisme=='Swakelola'): ?> selected <?php endif; ?> value="Swakelola">Swakelola</option>
                            <option <?php if($item->Mekanisme=='PL Penyedia Jasa'): ?> selected <?php endif; ?> value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option <?php if($item->Mekanisme=='Lelang'): ?> selected <?php endif; ?> value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" value="<?php echo e(($item->JumlahOrang)); ?>" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari" value="<?php echo e(($item->JumlahHari)); ?>">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" value="<?php echo e(($item->JumlahOrangHari)); ?>"name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="<?php echo e(($item->UpahHarian)); ?>">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah" value="<?php echo e(($item->TotalBiayaUpah)); ?>">
                        <span class="input-group-text persenupah" style="font-size:14px;"><?php echo e(Persen($item->PersenBiayaUpah)); ?>%</span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number totalbiayalain" name="totalbiayalain" value="<?php echo e(($item->TotalBiayaLain)); ?>">
                    </div>
                </div>
            </div>
                <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Jumlah Realisasi Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number jumlahRealisasi output" id="jumlahRealisasi" name="jumlahRealisasi" value="<?php echo e(($item->TotalPagu)); ?>">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Sisa Anggaran Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number sisaRealisasi" id="sisaRealisasi" name="sisaRealisasi">
                    </div>
                </div>
                </div>

                <div class="row mt-3">
                    <div class="col-8"><h6 class="">RINCIAN PEMBAYARAN</h6></div>
                    <div title="Tambah No SP2D" class="col-4 text-primary text-end"><a class="addsp2d" href="#"><i data-feather="plus-circle"></i></a></div>
                </div>
                <hr class="mt-2 mb-2">


                <div class="row">
                    <?php $__currentLoopData = $item->sppd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="idtable[]" value="<?php echo e($sppd->idtable); ?>">
                    <div class="mb-3 col-sm-5">
                        <label class="form-label">Tanggal SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                            <input required name="tanggal[]" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control" value="<?php echo e(\Carbon\Carbon::parse($sppd->tanggal)->isoFormat('DD/MM/YYYY')); ?>" type="text" data-language="en">
                        </div>
                    </div>
                    <div class="mb-3 col-sm-7">
                        <label class="form-label">Nomor SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="file-text"></i></span>
                            <input maxlength="25" required style="font-size:14px" type="text" class="form-control number" value="<?php echo e($sppd->nosppd); ?>" name="nosp2d[]">
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="newDatasp2d"></div>

                <div class="mb-3">
                    <label class="form-label">Keterangan / Kendala Pelaksanaan</label>
                    <div class="input-group">
                        <textarea style="font-size:14px;" class='form-control' name='keterangan' rows='3'><?php echo e($item->Keterangan); ?></textarea>
                    </div>
                </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    <?php break; ?>

    <?php case ('editKegiatanPadatKarya'): ?>
    <form id="myform" action="<?php echo e(route('satker/post/data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="editpadatkarya">
        <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">
        <input type="hidden" name="guid" value="<?php echo e($dataDB->guid); ?>">

        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row">
                <div class="col-8"><h6 class="">KEGIATAN</h6></div>
                <div title="Tambah Akun Kegiatan" class="col-4 text-primary text-end"><a class="addakun" href="#"><i data-feather="plus-circle"></i></a></div>
            </div>
            <hr class="mt-2 mb-3">
            
            <?php $__currentLoopData = $akunDB; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akunkegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input value="<?php echo e($akunkegiatan->idtable); ?>" type="hidden" name="idtable[]">

            
            <div class="row">
                <div class="mb-3 col-xl-7 col-sm-12">
                    <label class="form-label">Akun Belanja</label>
                    <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="akun[]">
                        <option value="">Pilih Akun Belanja</option>
                        <?php $__currentLoopData = $akun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php if($item->Id==$akunkegiatan->Id): ?> selected <?php endif; ?> value="<?php echo e($item->Id); ?>.<?php echo e($item->Kewenangan); ?>.<?php echo e($item->Program); ?>"><?php echo e($item->Id); ?> : <?php echo e($item->keterangan->NamaAkun); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Jumlah Pagu Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input value="<?php echo e(RP($akunkegiatan->Amount)); ?>" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagukegiatan" name="pagukegiatan[]" value="">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kegiatan Padat Karya</label>
                <div class="input-group">
                    <input value="<?php echo e($akunkegiatan->Uraian); ?>" required style="text-align:left; font-size:14px; padding:9px !important;" type="text" class="form-control" name="kegiatan[]">
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="newData"></div>
            <h6 class="mt-4">RINCIAN KEGIATAN</h6>
            <hr class="mt-2 mb-3">
            <div class="mb-3">
                <label class="form-label">Total Pagu</label>
                <div class="input-group">
                    <input value="<?php echo e(RP($dataDB->TotalPagu)); ?>" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control totalpagu number" name="totalpagu">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi Kegiatan</label>
                <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="kdlokasi">
                    <option value="">Pilih Desa/Kecamatan Lokasi</option>
                    <?php $__currentLoopData = $lokasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(strlen($item->kode)==5): ?>
                        <option disabled value="<?php echo e($item->kode); ?>"><?php echo e($item->Nama); ?></option>
                    <?php elseif(strlen($item->kode)==8): ?>
                        <option disabled value="<?php echo e($item->kode); ?>">Kecamatan <?php echo e($item->Nama); ?></option>
                    <?php elseif(strlen($item->kode)>8): ?>
                        <option <?php if($item->kode==$dataDB->KdLokasi): ?> selected <?php endif; ?> value="<?php echo e($item->kode); ?>">Kelurahan / Desa <?php echo e($item->Nama); ?></option>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>


            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Rencana Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input value="<?php echo e($dataDB->Jadwal); ?>" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option <?php if($dataDB->Mekanisme=="Swakelola"): ?> selected <?php endif; ?> value="Swakelola">Swakelola</option>
                            <option <?php if($dataDB->Mekanisme=="PL Penyedia Jasa"): ?> selected <?php endif; ?> value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option <?php if($dataDB->Mekanisme=="Lelang"): ?> selected <?php endif; ?> value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input value="<?php echo e($dataDB->JumlahOrang); ?>" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input value="<?php echo e($dataDB->JumlahHari); ?>" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input value="<?php echo e($dataDB->JumlahOrangHari); ?>" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="150.000">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input  value="<?php echo e(RP($dataDB->TotalBiayaUpah)); ?>" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah">
                        <span class="input-group-text persenupah" style="font-size:14px;"><?php echo e(Persen($item->PersenBiayaUpah)); ?></span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        
                        <input  value="<?php echo e(RP($dataDB->TotalBiayaLain)); ?>" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayalain number" id="biayalain" name="biayalain">
                    </div>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    <?php break; ?>


    <?php case ('showpaguakun'): ?>
    <?php break; ?>

<?php endswitch; ?>


<?php /**PATH /Users/user/dev/app-monira/resources/views/apps/data-modal-monitoring.blade.php ENDPATH**/ ?>