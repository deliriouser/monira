

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.ico')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/favicon.ico')); ?>" type="image/x-icon">
    <title>MONIRA : MONITORING INFORMASI DAN REALISASI ANGGARAN</title>
    <style>

@page  {
            padding-bottom:34px;
        }

        .pagenum:before {
        content: counter(page);
    }
        footer {
            padding-top:10px;
                position: fixed;
                bottom: 1cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;
                text-align:center;
            }

        body {
            text-align: center;
            font-size: 9px;
            font-family:Arial, Helvetica, sans-serif;
        }

        table{
            border-spacing: -1px;}

        .table {
            /* border-collapse: collapse; */
            width: 100%;
            page-break-inside: always;
        }

        .table td, .table th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        .bg-primary {
            background-color:#0d6efd;
        }

        .bg-subheader {
            background-color:#ffd6d6;
        }
        .bg-current {
            background-color:#ff0000;
            color:#fff;
            font-weight:bold;
        }
        .table-footer {
            background-color:#cccccc;;
        }

        .bg-sumheader {
            background-color:#f4f4f4;;
        }
        thead {
            text-align:center;
        }
        .text-white {
            color:#ffffff;
        }

        .text-dark {
            color:#000000;
            font-weight:bold;
        }

        .text-center {
            text-align:center;
        }
        .text-start {
            text-align:left;
        }
        .text-end {
            text-align:right;
        }

        h3 {
        font-size: 13px;
        padding:0px;
        margin:0px;
        }

    </style>

</head>
  <body onload="startTime()">

    <?php switch($segment):
        case ('pnbp'): ?>

        <h3>REKAPITULASI REALISASI PNBP<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?><br></h3>
            <br>

        <table class="table">
            <thead class="bg-primary text-white">
              <tr>
                <th class="text-center">KODE</th>
                <th class="text-center">JENIS JASA</th>
                <th class="text-center">TARGET</th>
                <th class="text-center">REALISASI</th>
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
            <thead class="bg-subheader">
                <tr>
                <th colspan="2" class="text-start">PNBP FUNGSIONAL</th>
                <th class="text-end"><?php echo e(RP($target_fungsional)); ?></th>
                <th class="text-end"><?php echo e(RP($realisasi_fungsional)); ?></th>
                <td class="text-center"><span class="badge badge-<?php echo e(ColorTable(divnum($realisasi_fungsional,$target_fungsional)*100)); ?>"><?php echo e(persen(divnum($realisasi_fungsional,$target_fungsional)*100)); ?>%</span></td>
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

            <thead class="bg-subheader">
            <tr>
                <th colspan="2" class="text-start">PNBP NON FUNGSIONAL</th>
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

            <tfoot class="table-footer text-dark">
                <tr>
                  <th></th>
                  <th>JUMLAH</th>
                  <th class="text-end"><?php echo e(RP($data->sum('target'))); ?></th>
                  <th class="text-end"><?php echo e(RP($data->sum('span'))); ?></th>
                  <th class="text-center"><?php echo e(Persen(divnum($data->sum('span'),$data->sum('target'))*100)); ?>%</th>
                </tr>
              </tfoot>
          </table>

        <?php break; ?>

        <?php case ('ranking'): ?>
          <?php switch($unit):
              case ('eselon1'): ?>
              <h3>REKAPITULASI RANGKING DAYA SERAP ANGGARAN<br>
                <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
                TAHUN ANGGARAN <?php echo e($year); ?></h3>
                <br>

                <table class="table table-sm table-striped" id="page-all">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">KODE</th>
                            <th class="text-center">NAMA SATKER</th>
                            <th class="text-center">PROPINSI</th>
                            <th class="text-center">PAGU AWAL</th>
                            <th class="text-center">PAGU AKHIR</th>
                            <th class="text-center">REALISASI</th>
                            <th class="text-center">%</th>
                        </tr>
                    </thead>
                    <body>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr <?php if($item->KodeSatker==Auth:: user()->kdsatker): ?> class="bg-danger bg-current" <?php endif; ?>>
                            <td class="text-center"><?php echo e($loop->iteration); ?>

                                <?php if($item->KodeSatker==Auth:: user()->kdsatker): ?> <a name="<?php echo e(Auth:: user()->kdsatker); ?>"></a> <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo e($item->KodeSatker); ?></td>
                            <td class="text-start"><?php echo e($item->NamaSatuanKerja); ?></td>
                            <td class="text-start"><?php echo e($item->WilayahName); ?></td>
                            <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                            <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                            <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                            <td class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </body>
                    <tfoot class="table-footer text-dark">
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-start"></th>
                            <th class="text-start"></th>
                            <th class="text-start">JUMLAH</th>
                            <th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
                            <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                            <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                            <th class="text-end"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                        </tr>
                    </tfoot>
                </table>
                  <?php break; ?>

                  <?php case ('propinsi'): ?>
                  <h3>REKAPITULASI RANGKING DAYA SERAP ANGGARAN<br>
                    <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
                    TAHUN ANGGARAN <?php echo e($year); ?></h3>
                    <br>


                    <table class="table table-sm table-striped" id="page-all">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">KODE</th>
                                <th class="text-center">PROPINSI</th>
                                <th class="text-encenterd">PAGU AWAL</th>
                                <th class="text-center">PAGU AKHIR</th>
                                <th class="text-center">REALISASI</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                <td class="text-center"><?php echo e($item->KodeWilayah); ?></td>
                                <td class="text-start"><?php echo e($item->WilayahName); ?></td>
                                <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                                <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                                <td class="text-center"><?php echo e(Persen($item->Persen)); ?>%</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-footer text-dark">
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-start"></th>
                                <th class="text-end">JUMLAH</th>
                                <th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
                                <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                                <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                                <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                            </tr>
                        </tfoot>
                    </table>

                  <?php break; ?>
                <?php case ('satker'): ?>

                <h3>REKAPITULASI RANGKING DAYA SERAP ANGGARAN<br>
                    <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
                    TAHUN ANGGARAN <?php echo e($year); ?></h3>
                    <br>

                <table class="table table-sm" id="page-all">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">KODE</th>
                            <th class="text-center">NAMA SATKER</th>
                            <th class="text-center">PAGU AWAL</th>
                            <th class="text-center">PAGU AKHIR</th>
                            <th class="text-center">REALISASI</th>
                            <th class="text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $TotalPaguAwal=0;
                            $TotalPaguAkhir=0;
                            $TotalRealisasi=0;
                        ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-danger bg-subheader">
                            <td class="text-center"><b><?php echo e($item->KodeHeader); ?></b></td>
                            <td class="text-start" colspan="7"><b><?php echo e($item->NamaHeader); ?></b></td>
                        </tr>
                        <?php
                            $noSatker=0;
                            $PaguAwal=0;
                            $PaguAkhir=0;
                            $Realisasi=0;
                        ?>
                        <?php $__currentLoopData = $item->Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $PaguAwal+=$satker->PaguAwal;
                            $PaguAkhir+=$satker->PaguAkhir;
                            $Realisasi+=$satker->Realisasi;
                        ?>
                        <tr>
                            <td class="text-center"><?php echo e($noSatker=$noSatker+1); ?></td>
                            <td class="text-center"><?php echo e($satker->Kode); ?></td>
                            <td class="text-start"><?php echo e($satker->Keterangan); ?></td>
                            <td class="text-end"><?php echo e(RP($satker->PaguAwal)); ?></td>
                            <td class="text-end"><?php echo e(RP($satker->PaguAkhir)); ?></td>
                            <td class="text-end"><?php echo e(RP($satker->Realisasi)); ?></td>
                            <td class="text-center"><?php echo e(Persen($satker->Persen)); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr class="border-top-primary bg-sumheader">
                            <th class="text-center"></th>
                            <th class="text-start"></th>
                            <th class="text-start">JUMLAH</th>
                            <th class="text-end"><?php echo e(RP($PaguAwal)); ?></th>
                            <th class="text-end"><?php echo e(RP($PaguAkhir)); ?></th>
                            <th class="text-end"><?php echo e(RP($Realisasi)); ?></th>
                            <th class="text-center"><?php echo e(Persen(divnum($Realisasi,$PaguAkhir)*100)); ?>%</th>
                        </tr>

                        <?php
                            $TotalPaguAwal  += $PaguAwal;
                            $TotalPaguAkhir += $PaguAkhir;
                            $TotalRealisasi += $Realisasi;
                        ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="table-footer text-dark">
                        <tr class="table-primary">
                            <th class="text-center"></th>
                            <th class="text-start"></th>
                            <th class="text-start">JUMLAH RAYA</th>
                            <th class="text-end"><?php echo e(RP($TotalPaguAwal)); ?></th>
                            <th class="text-end"><?php echo e(RP($TotalPaguAkhir)); ?></th>
                            <th class="text-end"><?php echo e(RP($TotalRealisasi)); ?></th>
                            <th class="text-center"><?php echo e(Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)); ?>%</th>
                        </tr>
                    </tfoot>
                </table>

                <?php break; ?>
              <?php default: ?>

          <?php endswitch; ?>

        <?php break; ?>

        <?php case ('belanja_covid'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS BELANJA<br>
            KEGIATAN PENANGANAN COVID-19<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?></h3>
            <br>

        <table class="table">
            <thead class="bg-primary text-white">
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

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                    <td class="text-center"><?php echo e($item->Kode); ?></td>
                    <td class="text-start"><?php echo e(($item->Keterangan)); ?></td>
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
            <tfoot class="table-footer text-dark">
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
        <?php break; ?>

        <?php case ('kegiatan_covid'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS KEGIATAN<br>
            KEGIATAN PENANGANAN COVID-19<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?></h3>
            <br>

        <table class="table">
            <thead class="bg-primary text-white">
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

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                    <td class="text-center"><?php echo e($item->Kode); ?></td>
                    <td class="text-start"><?php echo e(($item->Keterangan)); ?></td>
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
            <tfoot class="table-footer text-dark">
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
        <?php break; ?>

        <?php case ('sumberdana_covid'): ?>
        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS SUMBER DANA<br>
            KEGIATAN PENANGANAN COVID-19<br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?></h3>
            <br>

        <table class="table">
            <thead class="bg-primary text-white">
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

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                    <td class="text-center"><?php echo e($item->Kode); ?></td>
                    <td class="text-start"><?php echo e(($item->Keterangan)); ?></td>
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
            <tfoot class="table-footer text-dark">
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
        <?php break; ?>

        <?php default: ?>

        <h3>REKAPITULASI REALISASI DAYA SERAP PER JENIS <?php echo e(STRTOUPPER($segment)); ?><br>
            <?php echo e(Auth::user()->satker->NamaSatuanKerja); ?><br>
            TAHUN ANGGARAN <?php echo e($year); ?></h3>
            <br>
        <table class="table">
            <thead class="bg-primary text-white">
                <tr>
                <th class="text-center">KODE</th>
                <th>KETERANGAN</th>
                <th class="text-center">PAGU AWAL</th>
                <th class="text-center">PAGU AKHIR</th>
                <th class="text-center">REALISASI</th>
                <th class="text-center">SISA</th>
                <th class="text-center">%</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-<?php echo e(ColorTable($item->Persen)); ?>">
                    <td class="text-center"><?php echo e($item->Kode); ?></td>
                    <td class="text-start"><?php echo e(($item->Keterangan)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->PaguAwal)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Pagu)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Realisasi)); ?></td>
                    <td class="text-end"><?php echo e(RP($item->Sisa)); ?></td>
                    <td class="text-center"><span class="badge badge-<?php echo e(ColorTable($item->Persen)); ?>"><?php echo e(persen($item->Persen)); ?>%</span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot class="table-footer text-dark">
                <tr>
                    <th class="text-center"></th>
                    <th>JUMLAH</th>
                    <th class="text-end"><?php echo e(RP($data->sum('PaguAwal'))); ?></th>
                    <th class="text-end"><?php echo e(RP($data->sum('Pagu'))); ?></th>
                    <th class="text-end"><?php echo e(RP($data->sum('Realisasi'))); ?></th>
                    <th class="text-end"><?php echo e(RP($data->sum('Sisa'))); ?></th>
                    <th class="text-center"><?php echo e(Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)); ?>%</th>
                </tr>
                </tfoot>
        </table>


    <?php endswitch; ?>


    <footer>
        <img width="" src="<?php echo e(public_path('assets/images/logo/logo-icon.png')); ?>" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran <?php echo e(date('Y')); ?>

    </footer>


</body>
</html>
<?php /**PATH /Users/user/dev/app-monira/resources/views/reports/report-pdf.blade.php ENDPATH**/ ?>