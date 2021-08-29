
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SNIPER - SISTEM INFORMASI PEJABAT PERBENDAHARAAN</title>

  <link rel="shortcut icon" href="<?php echo e(URL::asset('favicon.ico')); ?>">
  <meta name="csrf-token" content="<?= csrf_token() ?>" />
  <meta name="csrf-param" content="_token" />
  <style>
      body {
          text-align: center;
          font-size: 9px;
          font-family: Arial, Helvetica, sans-serif;
      }


      #table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        page-break-inside: always;
        }

        #table td, #table th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        /* #table tr:hover {background-color: #ddd;} */

        #table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        }
    h3 {
        text-transform: uppercase;
    }
    img {
        width: 100px;
        height: 100px;
        border: 0px solid #fff;
        border-radius: 999px;
        margin-bottom: 10px;
        text-align: center;
    }
    .left {
        text-align: left;
    }
  </style>
  </head>
  <body>

    <div class="container-default">

    <h3>DAFTAR PEJABAT PERBENDAHARAAN <br>PROPINSI <?php echo e($propinsi->WilayahName); ?> <br>DIREKTORAT JENDERAL PERHUBUNGAN LAUT <br>Tahun Anggaran <?php echo e($year); ?></h3>

          <table id="table">
                <?php $__currentLoopData = $propinsi->satker; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                <th colspan="6"><?php echo e($loop->iteration); ?>. <?php echo e(strtoupper($data->NamaSatuanKerja)); ?></th>
                </tr>
                <tr>
                    <td>KPA</td>
                    <td>PPK</td>
                    <td>P3SPM</td>
                    <td>BENRUT</td>
                    <td>BENPEN</td>
                    <td>BENMAT</td>
                </tr>
                <tbody>
                    <tr>
                        <td valign="top">
                            <?php $__currentLoopData = $data->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($profil->jabatan==1): ?>
                            <table id="table" class="left">
                                <tbody>
                                    <tr>
                                        <td rowspan="4" halign="center">
                                            <img src="<?php echo e(asset('storage/'.$profil->profile->foto)); ?>" alt="img" class="img">
                                        </td>
                                        <td>
                                    <?php echo e($profil->profile->nama); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->nip); ?></td>
                                </tr>
                                <tr>
                                    <td><?php if(isset($profil->profile->pangkat)): ?> <?php echo e($profil->profile->pangkat); ?> (<?php echo e($profil->profile->golongan); ?>) <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->pendidikan_terakhir); ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo e($profil->profile->email); ?></td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td><?php echo e($profil->profile->telepon); ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor SK Jabatan</td>
                                    <td><?php echo e($profil->detiljabatan->notmt_jabatan ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal SK Jabatan</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Lama Menjabat</td>
                                    <td>
                                        <?php if(!empty($item->detiljabatan->tmt_jabatan) AND !empty($item->detiljabatan->tmt_awal)): ?>
                                        <?php echo e(\Carbon\Carbon::parse($item->detiljabatan->tmt_awal)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?>

                                        <?php elseif(!empty($item->detiljabatan->tmt_jabatan)): ?>
                                        <?php echo e(\Carbon\Carbon::parse($item->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?>

                                        <?php else: ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kantor Asal</td>
                                    <td><?php echo e($profil->profile->kantor); ?></td>
                                </tr>
                                <tr>
                                    <td>Sertifikat</td>
                                    <td><?php echo e($profil->sertifikat->nomor_sertifikat ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Keterangan </td>
                                    <td><?php echo e($profil->detiljabatan->keterangan_pejabat ?? ''); ?></td>
                                </tr>
                             </tbody>
                              </table>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td valign="top">
                            <?php $__currentLoopData = $data->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($profil->jabatan==2): ?>
                            <table id="table" class="left">
                                <tbody>
                                    <tr>
                                        <td rowspan="4" halign="center">
                                            <img src="<?php echo e(asset('storage/'.$profil->profile->foto)); ?>" alt="img" class="img">
                                        </td>
                                        <td>
                                    <?php echo e($profil->profile->nama); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->nip); ?></td>
                                </tr>
                                <tr>
                                    <td><?php if(isset($profil->profile->pangkat)): ?> <?php echo e($profil->profile->pangkat); ?> (<?php echo e($profil->profile->golongan); ?>) <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->pendidikan_terakhir); ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo e($profil->profile->email); ?></td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td><?php echo e($profil->profile->telepon); ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor SK Jabatan</td>
                                    <td><?php echo e($profil->detiljabatan->notmt_jabatan ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal SK Jabatan</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Lama Menjabat</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?><?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?> <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kantor Asal</td>
                                    <td><?php echo e($profil->profile->kantor); ?></td>
                                </tr>
                                <tr>
                                    <td>Sertifikat</td>
                                    <td>
                                        <?php if(!empty($profil->barjas)): ?>
                                        <?php echo e($profil->barjas->nomor_sertifikat); ?>

                                        <?php endif; ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan </td>
                                    <td><?php echo e($profil->detiljabatan->keterangan_pejabat ?? ''); ?></td>
                                </tr>
                             </tbody>
                              </table>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td valign="top">
                            <?php $__currentLoopData = $data->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($profil->jabatan==3): ?>
                            <table id="table" class="left">
                                <tbody>
                                    <tr>
                                        <td rowspan="4" halign="center">
                                            <img src="<?php echo e(asset('storage/'.$profil->profile->foto)); ?>" alt="img" class="img">
                                        </td>
                                        <td>
                                    <?php echo e($profil->profile->nama); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->nip); ?></td>
                                </tr>
                                <tr>
                                    <td><?php if(isset($profil->profile->pangkat)): ?> <?php echo e($profil->profile->pangkat); ?> (<?php echo e($profil->profile->golongan); ?>) <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->pendidikan_terakhir); ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo e($profil->profile->email); ?></td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td><?php echo e($profil->profile->telepon); ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor SK Jabatan</td>
                                    <td><?php echo e($profil->detiljabatan->notmt_jabatan ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal SK Jabatan</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Lama Menjabat</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?><?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?> <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kantor Asal</td>
                                    <td><?php echo e($profil->profile->kantor); ?></td>
                                </tr>
                                <tr>
                                    <td>Sertifikat</td>
                                    <td><?php echo e($profil->sertifikat->nomor_sertifikat ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Keterangan </td>
                                    <td><?php echo e($profil->detiljabatan->keterangan_pejabat ?? ''); ?></td>
                                </tr>
                             </tbody>
                              </table>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>

                        <td valign="top">
                            <?php $__currentLoopData = $data->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($profil->jabatan==4): ?>
                            <table id="table" class="left">
                                <tbody>
                                    <tr>
                                        <td rowspan="4" halign="center">
                                            <img src="<?php echo e(asset('storage/'.$profil->profile->foto)); ?>" alt="img" class="img">
                                        </td>
                                        <td>
                                    <?php echo e($profil->profile->nama); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->nip); ?></td>
                                </tr>
                                <tr>
                                    <td><?php if(isset($profil->profile->pangkat)): ?> <?php echo e($profil->profile->pangkat); ?> (<?php echo e($profil->profile->golongan); ?>) <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->pendidikan_terakhir); ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo e($profil->profile->email); ?></td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td><?php echo e($profil->profile->telepon); ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor SK Jabatan</td>
                                    <td><?php echo e($profil->detiljabatan->notmt_jabatan ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal SK Jabatan</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Lama Menjabat</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?><?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?> <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kantor Asal</td>
                                    <td><?php echo e($profil->profile->kantor); ?></td>
                                </tr>
                                <tr>
                                    <td>Sertifikat</td>
                                    <td>
                                        <?php if(!empty($profil->bnt)): ?>
                                        <?php echo e($profil->bnt->no_bnt); ?>

                                        <?php endif; ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan </td>
                                    <td><?php echo e($profil->detiljabatan->keterangan_pejabat ?? ''); ?></td>
                                </tr>
                             </tbody>
                              </table>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>

                        <td valign="top">
                            <?php $__currentLoopData = $data->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($profil->jabatan==5): ?>
                            <table id="table" class="left">
                                <tbody>
                                    <tr>
                                        <td rowspan="4" halign="center">
                                            <img src="<?php echo e(asset('storage/'.$profil->profile->foto)); ?>" alt="img" class="img">
                                        </td>
                                        <td>
                                    <?php echo e($profil->profile->nama); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->nip); ?></td>
                                </tr>
                                <tr>
                                    <td><?php if(isset($profil->profile->pangkat)): ?> <?php echo e($profil->profile->pangkat); ?> (<?php echo e($profil->profile->golongan); ?>) <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->pendidikan_terakhir); ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo e($profil->profile->email); ?></td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td><?php echo e($profil->profile->telepon); ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor SK Jabatan</td>
                                    <td><?php echo e($profil->detiljabatan->notmt_jabatan ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal SK Jabatan</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Lama Menjabat</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?><?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?> <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kantor Asal</td>
                                    <td><?php echo e($profil->profile->kantor); ?></td>
                                </tr>
                                <tr>
                                    <td>Sertifikat</td>
                                    <td>
                                        <?php if(!empty($profil->bnt)): ?>
                                        <?php echo e($profil->bnt->no_bnt); ?>

                                        <?php endif; ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan </td>
                                    <td><?php echo e($profil->detiljabatan->keterangan_pejabat ?? ''); ?></td>
                                </tr>
                             </tbody>
                              </table>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td valign="top">
                            <?php $__currentLoopData = $data->pejabat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($profil->jabatan==6): ?>
                            <table id="table" class="left">
                                <tbody>
                                    <tr>
                                        <td rowspan="4" halign="center">
                                            <img src="<?php echo e(asset('storage/'.$profil->profile->foto)); ?>" alt="img" class="img">
                                        </td>
                                        <td>
                                    <?php echo e($profil->profile->nama); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->nip); ?></td>
                                </tr>
                                <tr>
                                    <td><?php if(isset($profil->profile->pangkat)): ?> <?php echo e($profil->profile->pangkat); ?> (<?php echo e($profil->profile->golongan); ?>) <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo e($profil->profile->pendidikan_terakhir); ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo e($profil->profile->email); ?></td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td><?php echo e($profil->profile->telepon); ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor SK Jabatan</td>
                                    <td><?php echo e($profil->detiljabatan->notmt_jabatan ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal SK Jabatan</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Lama Menjabat</td>
                                    <td><?php if(isset($profil->detiljabatan->tmt_jabatan)): ?><?php echo e(\Carbon\Carbon::parse($profil->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?> <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kantor Asal</td>
                                    <td><?php echo e($profil->profile->kantor); ?></td>
                                </tr>
                                <tr>
                                    <td>Sertifikat</td>
                                    <td><?php echo e($profil->sertifikat->nomor_sertifikat ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <td>Keterangan </td>
                                    <td><?php echo e($profil->detiljabatan->keterangan_pejabat ?? ''); ?></td>
                                </tr>
                             </tbody>
                              </table>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>

                </tbody>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </table>


    </div>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-snipper-propinsi.blade.php ENDPATH**/ ?>