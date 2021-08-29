<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MONIRA : MONITORING INFORMASI DAN REALISASI ANGGARAN</title>

  <link rel="shortcut icon" href="<?php echo e(URL::asset('favicon.ico')); ?>">
  <meta name="csrf-token" content="<?= csrf_token() ?>" />
  <meta name="csrf-param" content="_token" />
  <style>
      body {
          text-align: center;
          font-size: 12px;
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

        #table tr:hover {background-color: #ddd;}

        #table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        }
    h3,h4 {
        text-transform: uppercase;
    }
    img {
        width: 100px;
        height: 100px;
        border: 0px solid #fff;
        border-radius: 999px;
        margin-bottom: 10px;
        float: right;
        margin-top: 46px;
    }
    .left {
        text-align: left;
    }

    @page  {
            padding-bottom:33px;
        }

        footer {
            border-top:1px dotted rgb(0, 0, 0);
            padding-top:15px;
                position: fixed;
                bottom: 0.5cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;
                text-align:center;
            }

  </style>
  </head>
  <body>

    <div class="container-default">

    <h3>Daftar Pejabat Perbendaharaan <br>Kantor <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?> <br>DIREKTORAT JENDERAL PERHUBUNGAN LAUT <br>Tahun Anggaran <?php echo e($setyear); ?></h3>


      <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <img src="<?php echo e(asset('storage/'.$data->profile->foto)); ?>" alt="img" class="img">

      <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-title text-c profile-pegawai">
                <h4 class="left"><?php echo e($data->refJabatan->keterangan); ?></h4>
            </div>
            <div class="panel-body">

          <!-- Start Profile Widget -->
          <table id="table">
            <tbody>
              <tr>
                <td width="30%">Nama</td>
                <td><?php echo e($data->profile->nama); ?></td>
            </tr>
            <tr>
                <td>NIP</td>
                <td><?php echo e($data->profile->nip); ?></td>
            </tr>
            <tr>
                <td>Pangkat/Golongan</td>
                <td><?php if(isset($data->profile->pangkat)): ?> <?php echo e($data->profile->pangkat); ?> (<?php echo e($data->profile->golongan); ?>) <?php endif; ?></td>
            </tr>
            <tr>
                <td>Pendidikan</td>
                <td><?php echo e($data->profile->pendidikan_terakhir); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo e($data->profile->email); ?></td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td><?php echo e($data->profile->telepon); ?></td>
            </tr>
            <tr>
                <td>Nomor SK Jabatan</td>
                <td><?php echo e($data->detiljabatan->notmt_jabatan ?? ''); ?></td>
            </tr>
            <tr>
                <td>Tanggal SK Jabatan</td>
                <td><?php if(isset($data->detiljabatan->tmt_jabatan)): ?> <?php echo e(\Carbon\Carbon::parse($data->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y')); ?> <?php endif; ?></td>
            </tr>
            <tr>
                <td>Lama Menjabat</td>
                <td><?php if(isset($data->detiljabatan->tmt_jabatan)): ?><?php echo e(\Carbon\Carbon::parse($data->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')); ?> <?php endif; ?>
                </td>
            </tr>


            <tr>
                <td>Kantor Asal</td>
                <td><?php echo e($data->profile->kantor); ?></td>
            </tr>
            <tr>
                <td>Sertifikat</td>
                <td>
                    <?php if(!empty($data->bnt)): ?>
                    <div class="ribbon ribbon-bookmark ribbon-right ribbon-success"><?php echo e($data->bnt->no_bnt); ?></div>
                    <?php endif; ?>
                    <?php if(!empty($data->barjas)): ?>
                    <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">Barang dan Jasa : <?php echo e($data->barjas->nomor_sertifikat); ?></div>
                    <?php endif; ?>

                </td>
            </tr>
            <tr>
                <td>Keterangan </td>
                <td><?php echo e($data->detiljabatan->keterangan_pejabat ?? ''); ?></td>
            </tr>
            </tbody>
          </table>

          </div>

        </div>
          <!-- End Profile Widget -->
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


<footer>
    MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
    Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e($setyear); ?>

</footer>

</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf-snipper.blade.php ENDPATH**/ ?>