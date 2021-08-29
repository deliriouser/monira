<?php switch($what):
    case ('add'): ?>
    <form id="myform" action="<?php echo e(route(Auth::user()->ba.'/snipper/post/pejabat')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
            <h5 class="modal-title">Data Pegawai</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
           <div class="mb-2">
            <label class="form-label">Nomor Induk Pegawai (NIP)</label>
            <div class="input-group">
            <span class="input-group-text"><i data-feather="cpu"></i></span>
                <input required type="text" style="font-size:14px" class="form-control onlynumber nip" name="nip">
                <input type="hidden" class="what" name="what" value="InsertModal">
            <span class="input-group-text bg-success" style="border:1px solid #51bb25;"><i data-feather="search"></i></span>
            </div>
           </div>
           <div class="append_profile">
           </div>

           <div class="append_certificate">
           </div>
           <div class="keterangan" hidden>
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan" rows="2"></textarea>
           </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submitPegawai" type="submit">Simpan</button>
         </div>
    </form>
    <?php break; ?>

    <?php case ('edit'): ?>
    <form id="myform" action="<?php echo e(route(Auth::user()->ba.'/snipper/post/pejabat')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
            <h5 class="modal-title">Data Pegawai</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>

         <div class="modal-body p-20">

            <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nama" value="<?php echo e($data->profile->nama); ?>" readonly>
            </div>
            <div class="row">
                <div class="mb-2 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" style="font-size:14px" class="form-control nip mb-2" name="email" value="<?php echo e($data->profile->email); ?>" readonly>
                </div>
                <div class="mb-2 col-md-6">
                        <label class="form-label">Telepon</label>
                        <input required maxlength="15" type="text" style="font-size:14px" class="form-control onlynumber mb-2" name="telepon" value="<?php echo e(phone($data->profile->telepon)); ?>">
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Tanggal SK Terakhir</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                      <input value=" <?php if(!empty($data->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($data->detiljabatan->tmt_jabatan)->isoFormat('DD/MM/YYYY')); ?> <?php endif; ?>" required name="tgl_sk" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control digits" type="text" data-language="en">
                    </div>
                 </div>
                 <div class="mb-2">
                    <label class="form-label">Nomor SK Terakhir</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="clipboard"></i></span>
                        <input value="<?php echo e($data->detiljabatan->notmt_jabatan ?? ''); ?>" required style="font-size:14px" type="text" class="form-control" name="nosk">
                    </div>
                </div>

             <div class="mb-2">
                <label class="form-label">Menjabat Sejak (TMT Awal)</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input value="<?php if(!empty($data->detiljabatan->tmt_awal)): ?> <?php echo e(\Carbon\Carbon::parse($data->detiljabatan->tmt_awal)->isoFormat('DD/MM/YYYY')); ?> <?php endif; ?>" required name="tmt_awal" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control digits" type="text" data-language="en">
                </div>
            </div>
            <?php if($data->jabatan==2): ?>
            <div class="row">
                <div class="mb-2 col-md-6">
                    <label class="form-label">No Sertifikat Barjas</label>
                    <input type="text" style="font-size:14px" class="form-control mb-2" name="sertifikat_barjas" value="<?php echo e($data->barjas->nomor_sertifikat ?? ''); ?>">
                </div>
                <div class="mb-2 col-md-6">
                    <label class="form-label">PDF Sertifikat Barjas</label>
                    <div class="input-group">
                        <input name="file_barjas" class="form-control col-sm-8 file" type="file" aria-label="file">
                    </div>
                </div>
            </div>
            <?php elseif($data->jabatan==4 OR $data->jabatan==5): ?>
            <div class="row">
                <div class="mb-2 col-md-6">
                    <label class="form-label">No Sertifikat BNT</label>
                    <input type="text" style="font-size:14px" class="form-control mb-2" name="sertifikat_bnt" value="<?php echo e($data->bnt->no_bnt ?? ''); ?>">
                </div>
                <div class="mb-2 col-md-6">
                    <label class="form-label">PDF Sertifikat BNT</label>
                    <div class="input-group">
                        <input name="file_bnt" class="form-control col-sm-8 file" type="file" aria-label="file">
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="keterangan">
                <label class="form-label">Keterangan</label>
                <textarea required class="form-control" name="keterangan" rows="2"><?php echo e($data->detiljabatan->keterangan_pejabat ?? ''); ?></textarea>
            </div>
         </div>

         <input type="hidden" name="id" id="id">
         <input type="hidden" name="status" value="edit">
         <input type="hidden" name="nip" value="<?php echo e($data->nip); ?>">

         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submitPegawai" type="submit">Simpan</button>
         </div>
    </form>
    <?php break; ?>

    <?php case ('getdata'): ?>
    <input type="hidden" name="status" value="exist">
    <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Nama Pegawai</label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="user"></i></span>
            <input required style="font-size:14px" class="form-control" name="nama" value="<?php echo e($data->nama ?? ''); ?>" <?php echo e($status); ?>>
        </div>
     </div>
     <div class="mb-2 col-md-6">
        <label class="form-label">Nomor Telepon</label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="phone"></i></span>
            <input required style="font-size:14px" maxlength="15" type="tel" class="form-control onlynumber" name="telepon" value="<?php echo e($data->telepon ?? ''); ?>">
        </div>
     </div>
    </div>
     <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Tanggal SK Terakhir</label>
            <div class="input-group">
                <span class="input-group-text"><i data-feather="calendar"></i></span>
              <input required name="tgl_sk" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control digits" type="text" data-language="en">
            </div>
         </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Nomor SK Terakhir</label>
            <div class="input-group">
                <span class="input-group-text"><i data-feather="clipboard"></i></span>
                <input required style="font-size:14px" type="text" class="form-control" name="nosk">
            </div>
        </div>

     </div>
     <div class="row">
        <div class="mb-2 col-md-6">
        <label class="form-label">Jenis Jabatan</label>
        <div class="input-group">
            <select style="font-size:14px" required class="select col-sm-12 jabatan" name="jabatan">
                <option value="">Pilih</option>
                <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($item->id_jabatan); ?>"><?php echo e($item->keterangan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

        </div>
     </div>
     <div class="mb-2 col-md-6">
        <label class="form-label">Menjabat Sejak (TMT Awal)</label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="calendar"></i></span>
            <input required name="tmt_awal" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control digits" type="text" data-language="en">
        </div>
    </div>

     </div>

    <?php break; ?>

    <?php case ('getdatalengkap'): ?>
    <input type="hidden" name="status" value="noexist">

    <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Nama Pegawai</label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="user"></i></span>
            <input required style="font-size:14px" class="form-control" name="nama" value="<?php echo e($data->nama ?? ''); ?>" <?php echo e($status); ?>>
        </div>
     </div>
     <div class="mb-2 col-md-6">
        <label class="form-label">Nomor Telepon</label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="phone"></i></span>
            <input required style="font-size:14px" maxlength="15" type="tel" class="form-control onlynumber" name="telepon" value="<?php echo e($data->telepon ?? ''); ?>">
        </div>
     </div>
    </div>
    <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Pangkat Golongan</label>
        <div class="input-group">
            <select style="font-size:14px" required class="select col-sm-12" name="golongan">
                <option value="">Pilih</option>
                <option value="Pembina Utama_(IV/e)">Pembina Utama (IV/e)</option>
                <option value="Pembina UtamaMadya_(IV/d)">Pembina Utama Madya (IV/d)</option>
                <option value="Pembina Utama Muda_(IV/c)">Pembina Utama Muda (IV/c)</option>
                <option value="Pembina Tingkat I_(IV/b)">Pembina Tingkat I (IV/b)</option>
                <option value="Pembina_(IV/a)">Pembina (IV/a)</option>
                <option value="Penata Tingkat I_(III/d)">Penata Tingkat I (III/d)</option>
                <option value="Penata_(III/c)">Penata (III/c)</option>
                <option value="Penata Muda Tingkat I_(III/b)">Penata Muda Tingkat I (III/b)</option>
                <option value="Penata Muda_(III/a)">Penata Muda (III/a)</option>
                <option value="Pengatur Tingkat I_(II/d)">Pengatur Tingkat I (II/d)</option>
                <option value="Pengatur_(II/c)">Pengatur (II/c)</option>
                <option value="Pengatur Muda Tingkat I_(II/b)">Pengatur Muda Tingkat I (II/b)</option>
                <option value="Pengatur Muda_(II/a)">Pengatur Muda (II/a)</option>
                <option value="Juru Tingkat I_(I/d)">Juru Tingkat I (I/d)</option>
                <option value="Juru_(I/c)">Juru (I/c)</option>
                <option value="Juru Muda Tingkat I_(I/b)">Juru Muda Tingkat I (I/b)</option>
                <option value="Juru Muda_(I/a)">Juru Muda (I/a)</option>
            </select>
        </div>
        </div>
     <div class="mb-2 col-md-6">
        <label class="form-label">Pendidikan</label>
        <div class="input-group">
            <select style="font-size:14px" required class="select col-sm-12" name="pendidikan">
                    <option value="">Pilih</option>
                    <option value="10">S-3/Doktor</option>
                    <option value="11">S-2</option>
                    <option value="14">S-1/Sarjana</option>
                    <option value="15">Diploma IV</option>
                    <option value="16">Diploma III/Sarjana Muda</option>
                    <option value="17">Diploma II</option>
                    <option value="18">Diploma I</option>
                    <option value="19">SLTA</option>
                    <option value="22">SLTP</option>
                    <option value="24">Sekolah Dasar</option>

            </select>
        </div>
     </div>
    </div>

     <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Tanggal SK Terakhir</label>
            <div class="input-group">
                <span class="input-group-text"><i data-feather="calendar"></i></span>
              <input required name="tgl_sk" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control digits" type="text" data-language="en">
            </div>
         </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Nomor SK Terakhir</label>
            <div class="input-group">
                <span class="input-group-text"><i data-feather="clipboard"></i></span>
                <input required style="font-size:14px" type="text" class="form-control" name="nosk">
            </div>
        </div>

     </div>
     <div class="row">
        <div class="mb-2 col-md-6">
        <label class="form-label">Jenis Jabatan</label>
        <div class="input-group">
            <select style="font-size:14px" required class="select col-sm-12 jabatan" name="jabatan">
                <option value="">Pilih</option>
                <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($item->id_jabatan); ?>"><?php echo e($item->keterangan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

        </div>
     </div>
     <div class="mb-2 col-md-6">
        <label class="form-label">Menjabat Sejak (TMT Awal)</label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="calendar"></i></span>
            <input required name="tmt_awal" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control digits" type="text" data-language="en">
        </div>
    </div>

     </div>

    <?php break; ?>

    <?php case ('certificate'): ?>
    <div class="mb-2">
        <label class="form-label">Nomor Sertifikasi <?php echo e($jenis); ?></label>
        <div class="input-group">
            <span class="input-group-text"><i data-feather="shield"></i></span>
            <input style="font-size:14px" class="form-control" name="sertifikat"
            <?php if($jabatan==2): ?>
            value="<?php echo e($data->barjas->nomor_sertifikat ?? ''); ?>"
                <?php if(!empty($data->barjas->nomor_sertifikat)): ?>
                disabled
                <?php else: ?>
                enabled
                <?php endif; ?>
            <?php elseif($jabatan==4 OR $jabatan==5): ?>
            value="<?php echo e($data->bnt->no_bnt ?? ''); ?>"
                <?php if(!empty($data->bnt->no_bnt)): ?>
                disabled
                <?php else: ?>
                enabled
                <?php endif; ?>
            <?php endif; ?>
            >
        </div>
     </div>
     <?php if($jabatan==2): ?>
         <?php if(!empty($data->barjas->nomor_sertifikat)): ?>
         <?php else: ?>
         <div class="mb-2">
            <label class="form-label">Upload Berkas Sertifikat</label>
            <div class="input-group">
                <input name="file" class="form-control col-sm-8 file" type="file" aria-label="file">
            </div>
         </div>
         <?php endif; ?>
     <?php elseif($jabatan==4 OR $jabatan==5): ?>
         <?php if(!empty($data->bnt->no_bnt)): ?>
         <?php else: ?>
         <div class="mb-2">
            <label class="form-label">Upload Berkas Sertifikat</label>
            <div class="input-group">
            <input name="file" class="form-control col-sm-8 file" type="file" aria-label="file">
            </div>
         </div>
         <?php endif; ?>
     <?php endif; ?>

     <?php break; ?>

     <?php case ('insertname'): ?>
     <div class="mb-3">
        <label class="form-label">Nama Pegawai</label>
        <input style="text" required style="font-size:14px" class="form-control" name="nama" value="<?php echo e($data->nama); ?>" disabled>
    </div>

     <?php break; ?>

     <?php case ('read'): ?>

        <div class="modal-header">
            <h5 class="modal-title">Data Pegawai</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-20">

            <div class="mb-2">
                    <label class="form-label">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->nip); ?>" readonly>
            </div>
            <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->profile->nama); ?>" readonly>
            </div>
            <div class="row">
            <div class="mb-2 col-md-6">
                    <label class="form-label">Pangkat Golongan</label>
                    <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->profile->pangkat); ?> (<?php echo e($data->profile->golongan); ?>)" readonly>
            </div>
            <div class="mb-2 col-md-6">
                <label class="form-label">Pendidikan Terakhir</label>
                    <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->profile->pendidikan_terakhir); ?>" readonly>
            </div>
            </div>
            <div class="mb-2">
                <label class="form-label">Satker</label>
                <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->profile->kantor); ?>" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label">Unit</label>
                <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->profile->subkantor); ?>" readonly>
            </div>
            <div class="row">
                <div class="mb-2 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->profile->email); ?>" readonly>
                </div>
                <div class="mb-2 col-md-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e(phone($data->profile->telepon)); ?>" readonly>
                </div>
                </div>
            <?php if(!empty($data->barjas)): ?>
            <div class="mb-2">
                <label class="form-label">No Sertifikat Barjas</label>
                <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->barjas->nomor_sertifikat); ?>" readonly>
            </div>
            <?php endif; ?>
            <?php if(!empty($data->bnt)): ?>
            <div class="mb-2">
                <label class="form-label">No Sertifikat BNT</label>
                <input type="text" style="font-size:14px" class="form-control onlynumber nip mb-2" name="nip" value="<?php echo e($data->bnt->no_bnt); ?>" readonly>
            </div>
            <?php endif; ?>
            <div class="mb-2">
                <label class="form-label">Daftar Sertifikasi</label>
                <?php if(count($data->sertifikasi)>0): ?>
                <?php $__currentLoopData = $data->sertifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo e($item->sertifikat->jenis_diklat); ?> No. <?php echo e($item->nomor_sertifikat); ?>

                    <span class="badge badge-gray counter">
                        <a target="_blank" href="<?php echo e(route('download',['id' => Crypt::encrypt($item->path_file)])); ?>" class="text-success"><i class="icofont icofont-cloud-download fa-2x"></i></a>
                        <a href="<?php echo e(route(Auth::user()->ba.'/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->sertifikat_id),'what'=>'sertifikat'])); ?>" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                    </span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">Belum Ada Data</li>
                <?php endif; ?>
            </div>
            <div class="mb-2">
                <label class="form-label">Riwayat Jabatan</label>
                <?php if(count($data->riwayat)>0): ?>
                <?php $__currentLoopData = $data->riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php if($item->status_pejabat==1): ?> list-group-item-success <?php endif; ?>">
                    <?php echo e(strtoupper($item->jabatan->keterangan ?? '')); ?>

                    <br>
                    <?php echo e(ucfirst(strtolower($item->satker->NamaSatuanKerja ?? ''))); ?><br>
                    <?php echo e(\Carbon\Carbon::parse($item->tmt_jabatan)->format('d/M/Y')); ?><br>

                    <?php if($item->status_pejabat==0): ?>
                    <span class="badge badge-gray counter">
                        <a title="aktifkan" onclick="confirmation_disabled(event)" href="<?php echo e(route(Auth::user()->ba.'/snipper/user',['status'=>'1', 'id'=>Crypt::encrypt($item->detil_id),'what'=>'aktivasiriwayat'])); ?>" data-bs-original-title="" class="text-success"><i class="icofont icofont-square-up fa-2x"></i></i></a>
                        <a title="hapus" onclick="confirmation_disabled(event)" href="<?php echo e(route(Auth::user()->ba.'/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->detil_id),'what'=>'riwayat'])); ?>" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                    </span>
                    <?php endif; ?>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">Belum Ada Data</li>
            <?php endif; ?>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
         </div>

     <?php break; ?>

     <?php case ('editSK'): ?>
     <form id="myform" action="<?php echo e(route(Auth::user()->ba.'/snipper/post/pejabat')); ?>" method="POST" enctype="multipart/form-data">
         <?php echo csrf_field(); ?>
         <input type="hidden" name="status" value="editSK">

         <div class="modal-header">
             <h5 class="modal-title">Data SK Pejabat</h5>
             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Tahun Anggaran</label>
                <select style="font-size:14px" required class="select col-sm-12" name="tahun">

                <option value="">Pilih</option>
                <?php
                $tahun_awal=$setyear;
                $tahun_depan=DATE('Y')+1;
                for($i=$tahun_awal; $i<=$tahun_depan; $i++) { ?>
                <option <?php if($data->tahun==$i): ?> selected <?php endif; ?> value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                <?php } ?>
                </select>
             </div>
            <div class="mb-3">
                <label class="form-label">Jenis SK Pejabat</label>
                <select style="font-size:14px" required class="select col-sm-12" name="jenis">
                <option value="">Pilih</option>
                <option <?php if($data->jenis=="SK KPA"): ?> selected <?php endif; ?> value="SK KPA">SK KPA</option>
                <option <?php if($data->jenis=="SK PPK dan P3SPM"): ?> selected <?php endif; ?> value="SK PPK dan P3SPM">SK PPK dan P3SPM</option>
                <option <?php if($data->jenis=="SK BENDAHARA"): ?> selected <?php endif; ?> value="SK BENDAHARA">SK BENDAHARA</option>
                <option <?php if($data->jenis=="SK BMN"): ?> selected <?php endif; ?> value="SK BMN">SK PENGELOLA BMN</option>
                </select>
            </div>

            <input class="datepicker-here" disabled type="hidden">
            <input id="id" type="hidden" name="id">

          </div>
          <div class="modal-footer">
             <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
             <button class="btn btn-primary submitPegawai" type="submit">Simpan</button>
          </div>
     </form>
     <?php break; ?>

     <?php case ('propinsi'): ?>
     <form id="myformx" action="<?php echo e(route(Auth::user()->ba.'/snipper/rekap/pejabat')); ?>" method="get">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="what" value="propinsi">

        <div class="modal-header">
            <h5 class="modal-title">Pilih Wilayah</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">

     <div class="row">
        <div class="mb-2 col-md-12">
        <div class="input-group">
            <select style="font-size:14px" required class="select col-sm-12" name="id">
                <option value="">Pilih Wilayah</option>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propinsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($propinsi->KodeWilayah); ?>"><?php echo e($propinsi->WilayahName); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

        </div>
        </div>
        </div>
         </div>
    <div class="modal-footer">
       <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
       <button class="btn btn-primary submitPegawai" type="submit">Proses</button>
    </div>
    </form>
     <?php break; ?>

<?php endswitch; ?>


<?php /**PATH /Users/user/dev/app-monira/resources/views/apps/data-modal-snipper.blade.php ENDPATH**/ ?>