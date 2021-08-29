<?php

function numbering( $n, $precision = 1 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'Jt';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'M';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
}

function numchart( $n, $precision = 1 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'Ribuan';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'Jutaan';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'Milyaran';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'Trilyunan';
    }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return array('result'=>$n_format,'suffix'=>$suffix);
}


function divnum($numerator, $denominator)
{
            return $denominator == 0 ? 0 : ($numerator / $denominator);
}

function Color($n) {
    if($n>60) {
        $color = "51bb25";
    } elseif($n<60 && $n>40){
        $color = "f8d62b";
    } else {
        $color = "dc3545";
    }
    return $color;
}

function ColorTable($n) {
    $n=number_format($n,0);

    if($n>60) {
        $color = "success";
    } elseif($n<=60 && $n>=40){
        $color = "warning";
    } else {
        $color = "danger";
    }
    return $color;
}

function isRead($n) {
    if($n=='0') {
        $status = "selected";
    } else {
        $status = "";
    }
    return $status;
}

function phone($no) {
    if(!preg_match('/[^+0-9]/',trim($no))){
        // cek apakah no hp karakter 1-3 adalah +62
        if(substr(trim($no), 0, 2)=='62'){
            $hp = '0'.trim($no);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif(substr(trim($no), 0, 1)=='8'){
            $hp = '0'.trim($no);
        }else{
            $hp = $no;
        }
    }
    return $hp;
}
function NestCollection($data,$depth) {
    switch($depth){
        case "user":
            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaWilayah])) {
                    $item[$row->NamaWilayah] = [
                        'KodeWilayah' => $row->KodeWilayah,
                        'NamaWilayah' => $row->NamaWilayah,
                        'Satker'        => []
                    ];

                }

                if (!isset($item[$row->NamaWilayah]['Satker'][$row->KodeSatker])) {
                    $item[$row->NamaWilayah]['Satker'][$row->KodeSatker] = [
                        'KodeSatker' => $row->KodeSatker,
                        'NamaSatker' => $row->NamaSatker,
                        'User' => []
                    ];
                }

                $item[$row->NamaWilayah]['Satker'][$row->KodeSatker]['User'][] = [
                    'Id'         => $row->Id,
                    'KodeSatker' => $row->KodeSatker,
                    'NamaUser'   => $row->name,
                    'UserLogin'  => $row->email,
                    'LastSeen'   => $row->last_seen
                ];
            }

        break;
        case "1":
            $item = [];
            foreach ($data as $row) {
                $item['Data'][] = [
                    'Kode'           => $row->Kode,
                    'Keterangan'     => $row->Keterangan,
                    'PaguAwal'       => $row->PaguAwal ?? '0',
                    'PaguAkhir'      => $row->Pagu,
                    'Realisasi'      => $row->Realisasi,
                    'Sisa'           => $row->Sisa ?? '0',
                    'Persen'         => $row->Persen,
                    'Prognosa'       => $row->Prognosa ?? '0',
                    'PersenPrognosa' => $row->PersenPrognosa ?? '0'
                ];
            }

        break;
        case "2":
            $item = [];
            foreach ($data as $row) {
                $header = $row->NamaHeader;
                if (!isset($item[$header])) {
                    $item[$header] = [
                        'KodeHeader' => $row->KodeHeader,
                        'NamaHeader' => $row->NamaHeader,
                        'Data' => []
                    ];
                }
                $item[$header]['Data'][] = [
                    'Kode'           => $row->Kode,
                    'Keterangan'     => $row->Keterangan,
                    'PaguAwal'       => $row->PaguAwal ?? '0',
                    'PaguAkhir'      => $row->Pagu,
                    'Realisasi'      => $row->Realisasi,
                    'Sisa'           => $row->Sisa ?? '0',
                    'Persen'         => $row->Persen,
                    'Prognosa'       => $row->Prognosa ?? '0',
                    'PersenPrognosa' => $row->PersenPrognosa ?? '0'
                ];
            }
        break;

        case "3":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                    $item[$row->NamaHeader] = [
                        'KodeHeader' => $row->KodeHeader,
                        'NamaHeader' => $row->NamaHeader,
                        'Data' => []
                    ];

                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                    $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                        'KodeSubHeader' => $row->KodeSubHeader,
                        'NamaSubHeader' => $row->NamaSubHeader,
                        'SubData' => []
                    ];
                }

                $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][] = [
                    'Kode'           => $row->Kode,
                    'Keterangan'     => $row->Keterangan,
                    'PaguAwal'       => $row->PaguAwal ?? '',
                    'PaguAkhir'      => $row->Pagu,
                    'Realisasi'      => $row->Realisasi,
                    'Sisa'           => $row->Sisa ?? '0',
                    'Persen'         => $row->Persen,
                    'Prognosa'       => $row->Prognosa ?? '0',
                    'PersenPrognosa' => $row->PersenPrognosa ?? '0'
                ];
            }
        break;

        case "locking":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                    $item[$row->NamaHeader] = [
                        'KodeHeader' => $row->KodeHeader,
                        'NamaHeader' => $row->NamaHeader,
                        'Data' => []
                    ];

                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                    $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                        'KodeSubHeader'  => $row->KodeSubHeader,
                        'NamaSubHeader'  => $row->NamaSubHeader,
                        'PaguAwal'       => $row->PaguAwal ?? '0',
                        'PaguAkhir'      => $row->Pagu,
                        'Realisasi'      => $row->Realisasi,
                        'Sisa'           => $row->Sisa ?? '0',
                        'Persen'         => $row->Persen,
                        'Prognosa'       => $row->Prognosa ?? '0',
                        'PersenPrognosa' => $row->PersenPrognosa ?? '0',
                        'IsLockPrognosa' => $row->IsLockPrognosa
                ];
                }
            }
        break;

        case "4":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                    $item[$row->NamaHeader] = [
                        'KodeHeader' => $row->KodeHeader,
                        'NamaHeader' => $row->NamaHeader,
                        'Data' => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                    $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                        'KodeSubHeader' => $row->KodeSubHeader,
                        'NamaSubHeader' => $row->NamaSubHeader,
                        'SubDataSecond' => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubDataSecond'][$row->KodeSubHeaderSub])) {
                    $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubDataSecond'][$row->KodeSubHeaderSub] = [
                        'KodeSubHeaderSub' => $row->KodeSubHeaderSub,
                        'NamaSubHeaderSub' => $row->NamaSubHeaderSub,
                        'SubData' => []
                    ];
                }


                $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubDataSecond'][$row->KodeSubHeaderSub]['SubData'][] = [
                    'Kode'       => $row->Kode,
                    'Keterangan' => $row->Keterangan,
                    'PaguAwal'   => $row->PaguAwal,
                    'PaguAkhir'  => $row->Pagu,
                    'Realisasi'  => $row->Realisasi,
                    'Sisa'       => $row->Sisa ?? '0',
                    'Persen'     => $row->Persen
                ];
            }
        break;

        case "prognosa":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                    $item[$row->NamaHeader] = [
                        'KodeHeader' => $row->KodeHeader,
                        'NamaHeader' => $row->NamaHeader,
                        'Data' => []
                    ];

                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                    $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                        'KodeSubHeader' => $row->KodeSubHeader,
                        'NamaSubHeader' => $row->NamaSubHeader,
                        'SubData' => []
                    ];
                }

                $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][] = [
                    'KodeDana'       => $row->KodeSumberDana,
                    'NamaDana'       => $row->ShortKode,
                    'Kode'           => $row->Kode,
                    'Keterangan'     => $row->Keterangan,
                    'KodeKewenangan' => $row->KodeKewenangan,
                    'NamaKewenangan' => $row->NamaKewenangan,
                    'KodeProgram'    => $row->KodeProgram,
                    'NamaProgram'    => $row->NamaProgram,
                    'PaguAkhir'      => $row->Pagu,
                    // 'Realisasi'   => $row->Realisasi,
                    // 'Sisa'        => $row->Sisa ?? '0',
                    'Persen'      => $row->Persen,
                    'Prognosa'    => $row->Prognosa,
                    'Justifikasi' => $row->Justifikasi
                ];
            }
        break;

        case "prognosaWeekly":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                    $item[$row->NamaHeader] = [
                        'KodeHeader' => $row->KodeHeader,
                        'NamaHeader' => $row->NamaHeader,
                        'Data' => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                    $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                        'KodeSubHeader' => $row->KodeSubHeader,
                        'NamaSubHeader' => $row->NamaSubHeader,
                        'SubData' => []
                    ];
                }

                $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][] = [
                    'KodeDana'    => $row->KodeSumberDana,
                    'NamaDana'    => $row->ShortKode,
                    'Kode'        => $row->Kode,
                    'Keterangan'  => $row->Keterangan,
                    'PaguAwal'    => $row->PaguAwal,
                    'PaguAkhir'   => $row->Pagu,
                    'Realisasi'   => $row->Realisasi,
                    'Sisa'        => $row->Sisa ?? '0',
                    'Persen'      => $row->Persen,
                    'Prognosa'    => $row->Prognosa,
                    'Justifikasi' => $row->Justifikasi
                ];
            }
        break;

        case "covid":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                           $item[$row->NamaHeader] = [
                            'KodeHeader' => $row->KodeHeader,
                            'NamaHeader' => $row->NamaHeader,
                            'Data'       => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                            'KodeSubHeader' => $row->KodeSubHeader,
                            'NamaSubHeader' => $row->NamaSubHeader,
                            'SubData'       => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->Kode])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->Kode] = [
                            'KodeDana'       => $row->KodeSumberDana,
                            'NamaDana'       => $row->ShortKode,
                            'SubDataDana'    => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->Kode]['SubDataDana'][$row->KodeSumberDana])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->Kode]['SubDataDana'][$row->KodeSumberDana] = [
                            'KodeSubHeader'  => $row->KodeSubHeader,
                            'NamaSubHeader'  => $row->NamaSubHeader,
                            'KodeDana'       => $row->KodeSumberDana,
                            'NamaDana'       => $row->ShortKode,
                            'Kode'           => $row->Kode,
                            'Keterangan'     => $row->Keterangan,
                            'KodeKewenangan' => $row->KodeKewenangan,
                            'NamaKewenangan' => $row->NamaKewenangan,
                            'KodeProgram'    => $row->KodeProgram,
                            'NamaProgram'    => $row->NamaProgram,
                            'PaguAkhir'      => $row->Pagu,
                            'Realisasi'      => $row->Realisasi,
                            'Persen'         => $row->Persen,
                            'SubDataKegiatan'    => []
                    ];
                }

                // if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->Kode]['SubDataDana'][$row->KodeSumberDana]['SubDataKegiatan'])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->Kode]['SubDataDana'][$row->KodeSumberDana]['SubDataKegiatan'][] = [
                            'Guid'            => $row->guid,
                            'PaguKegiatan'    => $row->PaguKegiatan,
                            'BelanjaKegiatan' => $row->BelanjaKegiatan,
                            'Uraian'          => $row->Uraian,
                            'Catatan'         => $row->Catatan ?? '',
                            'VolumePagu'      => $row->VolumePagu,
                            'VolumeBelanja'   => $row->VolumeBelanja,
                            'SatuanPagu'      => $row->SatuanPagu,
                            'SatuanBelanja'   => $row->SatuanBelanja,
                     ];
            //   }
            }
        break;

        case "RincianCovid":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaHeader])) {
                           $item[$row->NamaHeader] = [
                            'KodeHeader' => $row->KodeHeader,
                            'NamaHeader' => $row->NamaHeader,
                            'Data'       => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader] = [
                            'KodeSubHeader' => $row->KodeSubHeader,
                            'NamaSubHeader' => $row->NamaSubHeader,
                            'SubData'       => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput] = [
                            'KodeOutput'       => $row->KodeOutput,
                            'NamaOutput'       => $row->NamaOutput,
                            'SubDataKegiatan'    => []
                    ];
                }
                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan] = [
                            'KodeKegiatan'       => $row->KodeKegiatan,
                            'NamaKegiatan'       => $row->NamaKegiatan,
                            'SubDataDana'    => []
                    ];
                }

                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan]['SubDataDana'][$row->SumberDana])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan]['SubDataDana'][$row->SumberDana] = [
                            'SumberDana'      => $row->SumberDana,
                            'SubDataAkun'    => []
                    ];
                }
                if (!isset($item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan]['SubDataDana'][$row->SumberDana]['SubDataAkun'][$row->Kode])) {
                           $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan]['SubDataDana'][$row->SumberDana]['SubDataAkun'][$row->Kode] = [
                            'KodeSubHeader'   => $row->KodeSubHeader,
                            'NamaSubHeader'   => $row->NamaSubHeader,
                            'KodeOutput'      => $row->KodeOutput,
                            'NamaOutput'      => $row->NamaOutput,
                            'KodeKegiatan'    => $row->KodeKegiatan,
                            'NamaKegiatan'    => $row->NamaKegiatan,
                            'Kode'            => $row->Kode,
                            'Keterangan'      => $row->Keterangan,
                            'SumberDana'      => $row->SumberDana,
                            'PaguAkhir'       => $row->Pagu,
                            'Realisasi'       => $row->Realisasi,
                            'Sisa'            => $row->Sisa,
                            'Persen'          => $row->Persen,
                            'SubDataKegiatan' => []
                    ];
                }
                            $item[$row->NamaHeader]['Data'][$row->KodeSubHeader]['SubData'][$row->KodeOutput]['SubDataKegiatan'][$row->KodeKegiatan]['SubDataDana'][$row->SumberDana]['SubDataAkun'][$row->Kode]['SubDataKegiatan'][]= [
                            // 'Guid'            => $row->guid,
                            'Uraian'          => $row->Uraian,
                            'Catatan'         => $row->Catatan ?? '',
                            'PaguKegiatan'    => $row->RpTarget,
                            'BelanjaKegiatan' => $row->RpRealisasi,
                            'VolumePagu'      => $row->VolumeTarget,
                            'VolumeBelanja'   => $row->VolumeRealisasi,
                            'SatuanPagu'      => $row->SatuanTarget,
                            'SatuanBelanja'   => $row->SatuanTarget,
                            'PersenKegiatan'  => $row->PersenKegiatan,
                            'SisaKegiatan'    => $row->SisaKegiatan,
                            'Tglsp2d'         => $row->Tglsp2d,
                            'Nosp2d'          => $row->Nosp2d,
                     ];

            }
        break;


        case "PadKaryaOne":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaProgram])) {
                           $item[$row->NamaProgram] = [
                            'KodeProgram' => $row->KodeProgram,
                            'NamaProgram' => $row->NamaProgram,
                            'Kegiatan'       => []
                    ];
                }

                if (!isset($item[$row->NamaProgram]['Kegiatan'][$row->KodeKegiatan])) {
                           $item[$row->NamaProgram]['Kegiatan'][$row->KodeKegiatan] = [
                            'KodeKegiatan' => $row->KodeKegiatan,
                            'NamaKegiatan' => $row->NamaKegiatan,
                            'Output'       => []
                    ];
                }

                if (!isset($item[$row->NamaProgram]['Kegiatan'][$row->KodeKegiatan]['Output'][$row->KodeOutput])) {
                    $item[$row->NamaProgram]['Kegiatan'][$row->KodeKegiatan]['Output'][$row->KodeOutput] = [
                     'KodeOutput'   => $row->KodeOutput,
                     'NamaOutput'   => $row->NamaOutput,
                     'Akun' => []
                    ];
                }

                    $item[$row->NamaProgram]['Kegiatan'][$row->KodeKegiatan]['Output'][$row->KodeOutput]['Akun'][] = [
                    'KodeSumberDana' => $row->KodeSumberDana,
                    'NamaSumberDana' => $row->NamaSumberDana,
                    'KodeAkun'       => $row->KodeAkun,
                    'NamaAkun'       => $row->NamaAkun,
                    'Pagu'           => $row->Pagu,
                    'Dsa'            => $row->Dsa,
                    'Persen'         => $row->Persen,
                    'Sisa'           => $row->Sisa,
                    ];
            }
        break;




        case "PadKaryaTwo":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaWilayah])) {
                           $item[$row->NamaWilayah] = [
                            'KodeWilayah' => $row->KodeWilayah,
                            'NamaWilayah' => $row->NamaWilayah,
                            'Program'       => []
                    ];
                }
                if (!isset($item[$row->NamaWilayah]['Program'][$row->KodeProgram])) {
                           $item[$row->NamaWilayah]['Program'][$row->KodeProgram] = [
                            'KodeProgram' => $row->KodeProgram,
                            'NamaProgram' => $row->NamaProgram,
                            'Kegiatan'       => []
                    ];
                }

                if (!isset($item[$row->NamaWilayah]['Program'][$row->KodeProgram]['Kegiatan'][$row->KodeKegiatan])) {
                           $item[$row->NamaWilayah]['Program'][$row->KodeProgram]['Kegiatan'][$row->KodeKegiatan] = [
                            'KodeKegiatan' => $row->KodeKegiatan,
                            'NamaKegiatan' => $row->NamaKegiatan,
                            'Output'       => []
                    ];
                }

                if (!isset($item[$row->NamaWilayah]['Program'][$row->KodeProgram]['Kegiatan'][$row->KodeKegiatan]['Output'][$row->KodeOutput])) {
                           $item[$row->NamaWilayah]['Program'][$row->KodeProgram]['Kegiatan'][$row->KodeKegiatan]['Output'][$row->KodeOutput] = [
                     'KodeOutput'   => $row->KodeOutput,
                     'NamaOutput'   => $row->NamaOutput,
                     'Akun' => []
                    ];
                }

                          $item[$row->NamaWilayah]['Program'][$row->KodeProgram]['Kegiatan'][$row->KodeKegiatan]['Output'][$row->KodeOutput]['Akun'][] = [
                    'KodeSumberDana' => $row->KodeSumberDana,
                    'NamaSumberDana' => $row->NamaSumberDana,
                    'KodeAkun'       => $row->KodeAkun,
                    'NamaAkun'       => $row->NamaAkun,
                    'Pagu'           => $row->Pagu,
                    'Dsa'            => $row->Dsa,
                    'Persen'         => $row->Persen,
                    'Sisa'           => $row->Sisa,

                    ];
            }
        break;

        case "PadKaryaThree":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaWilayah])) {
                           $item[$row->NamaWilayah] = [
                            'KodeWilayah' => $row->KodeWilayah,
                            'NamaWilayah' => $row->NamaWilayah,
                            'Satker'       => []
                    ];
                }
                if (!isset($item[$row->NamaWilayah]['Satker'][$row->KodeSatker])) {
                           $item[$row->NamaWilayah]['Satker'][$row->KodeSatker] = [
                            'KodeSatker' => $row->KodeSatker,
                            'NamaSatker' => $row->NamaSatker,
                            'Akun'       => []
                    ];
                }


                $item[$row->NamaWilayah]['Satker'][$row->KodeSatker]['Akun'][] = [
                    'KodeSumberDana' => $row->KodeSumberDana,
                    'NamaSumberDana' => $row->NamaSumberDana,
                    'KodeAkun'       => $row->KodeAkun,
                    'NamaAkun'       => $row->NamaAkun,
                    'Pagu'           => $row->Pagu,
                    'Dsa'            => $row->Dsa,
                    'Persen'         => $row->Persen,
                    'Sisa'           => $row->Sisa,

                    ];
            }
        break;

        case "PadKaryaTwoRekap":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaWilayah])) {
                           $item[$row->NamaWilayah] = [
                            'KodeWilayah' => $row->KodeWilayah,
                            'NamaWilayah' => $row->NamaWilayah,
                            'Kabupaten'   => []
                    ];
                }
                if (!isset($item[$row->NamaWilayah]['Kabupaten'][$row->Kabupaten])) {
                           $item[$row->NamaWilayah]['Kabupaten'][$row->Kabupaten] = [
                            'Kabupaten' => $row->Kabupaten,
                            'Kecamatan' => []
                    ];
                }

                if (!isset($item[$row->NamaWilayah]['Kabupaten'][$row->Kabupaten]['Kecamatan'][$row->Kecamatan])) {
                           $item[$row->NamaWilayah]['Kabupaten'][$row->Kabupaten]['Kecamatan'][$row->Kecamatan] = [
                            'Kecamatan' => $row->Kecamatan,
                            'Desa' => []
                    ];
                }
                if (!isset($item[$row->NamaWilayah]['Kabupaten'][$row->Kabupaten]['Kecamatan'][$row->Kecamatan]['Desa'][$row->Desa])) {
                           $item[$row->NamaWilayah]['Kabupaten'][$row->Kabupaten]['Kecamatan'][$row->Kecamatan]['Desa'][$row->Desa] = [
                            'Desa'                   => $row->Desa,
                            'KodeSatker'             => $row->KodeSatker ?? '',
                            'NamaSatker'             => $row->NamaSatker ?? '',
                            'Target_JumlahOrang'     => $row->Target_JumlahOrang ?? '',
                            'Target_JumlahHari'      => $row->Target_JumlahHari ?? '',
                            'Target_JumlahOrangHari' => $row->Target_JumlahOrangHari ?? '',
                            'Target_UpahHarian'      => $row->Target_UpahHarian ?? '',
                            'Target_TotalBiayaUpah'  => $row->Target_TotalBiayaUpah ?? '',
                            'Target_TotalBiayaLain'  => $row->Target_TotalBiayaLain ?? '',
                            'Target_PersenBiayaUpah' => $row->Target_PersenBiayaUpah ?? '',
                            'Target_TotalPagu'       => $row->Target_TotalPagu ?? '',
                            'Daser_JumlahOrang'      => $row->Daser_JumlahOrang ?? '',
                            'Daser_JumlahHari'       => $row->Daser_JumlahHari ?? '',
                            'Daser_JumlahOrangHari'  => $row->Daser_JumlahOrangHari ?? '',
                            'Daser_UpahHarian'       => $row->Daser_UpahHarian ?? '',
                            'Daser_TotalBiayaUpah'   => $row->Daser_TotalBiayaUpah ?? '',
                            'Daser_TotalBiayaLain'   => $row->Daser_TotalBiayaLain ?? '',
                            'Daser_PersenBiayaUpah'  => $row->Daser_PersenBiayaUpah ?? '',
                            'Daser_TotalPagu'        => $row->Daser_TotalPagu ?? '',

                    ];
                }


            }
        break;

        case "PadKaryaThreeRekap":

            $item = [];
            foreach ($data as $row) {
                if (!isset($item[$row->NamaWilayah])) {
                           $item[$row->NamaWilayah] = [
                            'KodeWilayah' => $row->KodeWilayah,
                            'NamaWilayah' => $row->NamaWilayah,
                            'Satker'   => []
                    ];
                }

                if (!isset($item[$row->NamaWilayah]['Satker'][$row->NamaSatker])) {
                           $item[$row->NamaWilayah]['Satker'][$row->NamaSatker] = [
                            'KodeSatker' => $row->KodeSatker ?? '',
                            'NamaSatker' => $row->NamaSatker ?? '',
                            'Kabupaten'              => $row->Kabupaten ?? '',
                            'Kecamatan'              => $row->Kecamatan ?? '',
                            'Desa'                   => $row->Desa ?? '',
                            'Target_JumlahOrang'     => $row->Target_JumlahOrang ?? '',
                            'Target_JumlahHari'      => $row->Target_JumlahHari ?? '',
                            'Target_JumlahOrangHari' => $row->Target_JumlahOrangHari ?? '',
                            'Target_UpahHarian'      => $row->Target_UpahHarian ?? '',
                            'Target_TotalBiayaUpah'  => $row->Target_TotalBiayaUpah ?? '',
                            'Target_TotalBiayaLain'  => $row->Target_TotalBiayaLain ?? '',
                            'Target_PersenBiayaUpah' => $row->Target_PersenBiayaUpah ?? '',
                            'Target_TotalPagu'       => $row->Target_TotalPagu ?? '',
                            'Daser_JumlahOrang'      => $row->Daser_JumlahOrang ?? '',
                            'Daser_JumlahHari'       => $row->Daser_JumlahHari ?? '',
                            'Daser_JumlahOrangHari'  => $row->Daser_JumlahOrangHari ?? '',
                            'Daser_UpahHarian'       => $row->Daser_UpahHarian ?? '',
                            'Daser_TotalBiayaUpah'   => $row->Daser_TotalBiayaUpah ?? '',
                            'Daser_TotalBiayaLain'   => $row->Daser_TotalBiayaLain ?? '',
                            'Daser_PersenBiayaUpah'  => $row->Daser_PersenBiayaUpah ?? '',
                            'Daser_TotalPagu'        => $row->Daser_TotalPagu ?? '',
                    ];
                }

            }
        break;

    }

    return json_decode(json_encode($item));

}



function PrepareChart($pagu,$dsa,$year) {

    $previusYear = $year-1;
    $data=array();

        foreach($pagu as $key)
         {
            $data['TA_'.$key->TA] = $key->DIPA;
         }

        $CurDSA = array();
        $PrevDSA = array();
        $total_cur = 0;
        $total_prev = 0;
        foreach($dsa as $item) {
            if($item->TA==$year) {
                $total_cur += $item->DSA;
                // $CurDSA[] = (($item->DSA/$data['TA_'.$year])*100);
                if(isset($data['TA_'.$year])) {
                $CurDSA[] = number_format(divnum($total_cur,$data['TA_'.$year])*100,2);
                }
            } else {
                $total_prev += $item->DSA;
                // $CurDSA[] = (($item->DSA/$data['TA_'.$year])*100);
                if(isset($data['TA_'.$previusYear])) {
                $PrevDSA[] = number_format(divnum($total_prev,$data['TA_'.$previusYear])*100,2);
                }
            }
        }
    return [ 'Current' => $CurDSA , 'Previus' => $PrevDSA];

}

function SqlGroup ($unit) {
    switch($unit) {
        case "eselon1" :
            $selectInnerPagu      = "";
            $selectInnerPaguCovid = "";
            $selectInnerBelanja   = "";
            $group                = "";
            $join                 = "";
            $selectList           = "";
            $groupFinal           = "";
            $orderBy              = "ORDER BY Kode ASC";
            break;

        case "propinsi" :
            $selectInnerPagu      = "SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,";
            $selectInnerPaguCovid = "monira_data_dipa_covid.Lokasi as KodeWilayah,";
            $selectInnerBelanja   = "SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,";
            $group                = "KodeWilayah,";
            $groupFinal           = "monira_ref_wilayah.KodeWilayah,";
            $selectList           = "monira_ref_wilayah.KodeWilayah as KodeHeader, monira_ref_wilayah.WilayahName as NamaHeader,";
            $join                 = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.KodeWilayah";
            $orderBy              = "ORDER BY KodeHeader,Kode ASC";

            break;

        case "satker" :
            $selectInnerPagu      = "KdSatker,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,";
            $selectInnerPaguCovid = "KdSatker,monira_data_dipa_covid.Lokasi as KodeWilayah,";
            $selectInnerBelanja   = "KdSatker,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,";
            $group                = "KodeWilayah,KdSatker,";
            $groupFinal           = "monira_ref_wilayah.KodeWilayah,monira_ref_satker.KodeSatker,";
            $selectList           = "monira_ref_wilayah.KodeWilayah as KodeHeader, monira_ref_wilayah.WilayahName as NamaHeader,monira_ref_satker.KodeSatker as KodeSubHeader,monira_ref_satker.NamaSatuanKerja as NamaSubHeader,";
            $join                 = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.KodeWilayah LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=UnionData.KdSatker";
            $orderBy              = "ORDER BY KodeHeader,KodeSubHeader,Kode ASC";

            break;
    }

    return [
        'selectList'           => $selectList,
        'selectInnerPagu'      => $selectInnerPagu,
        'selectInnerPaguCovid' => $selectInnerPaguCovid,
        'selectInnerBelanja'   => $selectInnerBelanja,
        'groupBy'              => $group,
        'join'                 => $join,
        'groupFinal'           => $groupFinal,
        'orderBy'              => $orderBy
    ];

}

function SqlPadatKarya ($unit) {
    switch($unit) {
        case "eselon1" :
            $selectList             = "";
            $group                  = "";
            $join                   = "";
            $joinRekap              = "";
            $selectListRekap        = "";
            $groupByRekap           = "";
            $selectListInnerPagu    = "";
            $selectListInnerBelanja = "";
            $groupByInnerList       = "";
            $selectListInner        = "";
            $onjoin                 = "monira_data_pakar_belanja.Program=monira_data_pakar_akun.Program AND
            monira_data_pakar_belanja.Kegiatan=monira_data_pakar_akun.Kegiatan AND
            monira_data_pakar_belanja.Output=monira_data_pakar_akun.Output AND
            monira_data_pakar_belanja.SumberDana=monira_data_pakar_akun.SumberDana AND
            monira_data_pakar_belanja.Akun=monira_data_pakar_akun.Akun";
            break;
        case "propinsi" :
            $selectList             = "Lokasi as KodeWilayah,monira_ref_wilayah.WilayahName,monira_data_pakar_akun.KdSatker,";
            $group      = "KodeWilayah,";
            $join       = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = Lokasi";
            $joinRekap  = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.Lokasi
            LEFT JOIN (SELECT nama as Kabupaten,kode FROM monira_ref_desa) as monira_ref_desa_kab ON monira_ref_desa_kab.kode = KodeKabupaten
            LEFT JOIN (SELECT nama as Kecamatan,kode FROM monira_ref_desa) as monira_ref_desa_kec ON monira_ref_desa_kec.kode = KodeKecamatan
            LEFT JOIN (SELECT nama as Desa,kode FROM monira_ref_desa) as monira_ref_desa ON monira_ref_desa.kode              = KodeDesa
            LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=KdSatker";
            $selectListRekap        = "Lokasi as KodeWilayah, monira_ref_wilayah.WilayahName,Kabupaten,Kecamatan,Desa,KdSatker,NamaSatuanKerja,";
            $groupByRekap           = "KodeWilayah,Kabupaten,KdSatker,Kecamatan,Desa,";
            $selectListInnerPagu    = "Lokasi,KdLokasi,substr(KdLokasi,1,5) as KodeKabupaten,KdSatker,substr(KdLokasi,1,8) as KodeKecamatan,substr(KdLokasi,1,13) as KodeDesa,";
            $selectListInnerBelanja = "monira_data_pakar_belanja.Lokasi,monira_data_pakar_belanja.KdLokasi,substr(KdLokasi,1,5) as KodeKabupaten,KdSatker,substr(KdLokasi,1,8) as KodeKecamatan,substr(KdLokasi,1,13) as KodeDesa,";
            $groupByInnerList       = ",Lokasi,KdLokasi,KdSatker";
            $selectListInner        = "Lokasi as KodeWilayah,";
            $onjoin                 = "monira_data_pakar_belanja.KodeWilayah=monira_data_pakar_akun.Lokasi AND monira_data_pakar_belanja.Program=monira_data_pakar_akun.Program AND
            monira_data_pakar_belanja.Kegiatan=monira_data_pakar_akun.Kegiatan AND
            monira_data_pakar_belanja.Output=monira_data_pakar_akun.Output AND
            monira_data_pakar_belanja.SumberDana=monira_data_pakar_akun.SumberDana AND
            monira_data_pakar_belanja.Akun=monira_data_pakar_akun.Akun";


            break;
        case "satker" :
            $selectList             = "Lokasi as KodeWilayah,monira_ref_wilayah.WilayahName,monira_data_pakar_akun.KdSatker,monira_ref_satker.NamaSatuanKerja,";
            $group                  = "KodeWilayah,KdSatker,";
            $join                   = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = monira_data_pakar_akun.Lokasi LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=monira_data_pakar_akun.KdSatker";
            $joinRekap  = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.Lokasi
            LEFT JOIN (SELECT nama as Kabupaten,kode FROM monira_ref_desa) as monira_ref_desa_kab ON monira_ref_desa_kab.kode=KodeKabupaten
            LEFT JOIN (SELECT nama as Kecamatan,kode FROM monira_ref_desa) as monira_ref_desa_kec ON monira_ref_desa_kec.kode=KodeKecamatan
            LEFT JOIN (SELECT nama as Desa,kode FROM monira_ref_desa) as monira_ref_desa ON monira_ref_desa.kode=KodeDesa
            LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=KdSatker";
            $selectListRekap        = "Lokasi as KodeWilayah, monira_ref_wilayah.WilayahName,Kabupaten,Kecamatan,Desa,KdSatker,NamaSatuanKerja,";
            $groupByRekap           = "KodeWilayah,KdSatker,";
            $selectListInnerPagu    = "Lokasi,KdLokasi,substr(KdLokasi,1,5) as KodeKabupaten,KdSatker,substr(KdLokasi,1,8) as KodeKecamatan,substr(KdLokasi,1,13) as KodeDesa,";
            $selectListInnerBelanja = "monira_data_pakar_belanja.Lokasi,monira_data_pakar_belanja.KdLokasi,substr(KdLokasi,1,5) as KodeKabupaten,KdSatker,substr(KdLokasi,1,8) as KodeKecamatan,substr(KdLokasi,1,13) as KodeDesa,";
            $groupByInnerList       = ",Lokasi,KdSatker";
            $selectListInner        = "Lokasi as KodeWilayah,KdSatker,";
            $onjoin                 = "monira_data_pakar_belanja.KdSatker=monira_data_pakar_akun.KdSatker AND
            monira_data_pakar_belanja.KodeWilayah=monira_data_pakar_akun.Lokasi AND
            monira_data_pakar_belanja.Program=monira_data_pakar_akun.Program AND
            monira_data_pakar_belanja.Kegiatan=monira_data_pakar_akun.Kegiatan AND
            monira_data_pakar_belanja.Output=monira_data_pakar_akun.Output AND
            monira_data_pakar_belanja.SumberDana=monira_data_pakar_akun.SumberDana AND
            monira_data_pakar_belanja.Akun=monira_data_pakar_akun.Akun";

            break;
    }
    return [
        'selectList'             => $selectList,
        'groupBy'                => $group,
        'join'                   => $join,
        'joinRekap'              => $joinRekap,
        'selectListRekap'        => $selectListRekap,
        'groupByRekap'           => $groupByRekap,
        'selectListInnerPagu'    => $selectListInnerPagu,
        'groupByInnerList'       => $groupByInnerList,
        'selectListInnerBelanja' => $selectListInnerBelanja,
        'selectListInner'        => $selectListInner,
        'onjoin'                 => $onjoin,

    ];
}

function SqlGroupPrognosa ($unit) {
    switch($unit) {
        case "eselon1" :
            $selectInnerPagu         = "";
            $selectInnerPaguPrognosa = "";
            $selectInnerBelanja      = "";
            $group                   = "";
            $join                    = "";
            $selectList              = "";
            $groupFinal              = "";
            $orderBy                 = "ORDER BY Kode ASC";
            break;

        case "propinsi" :
            $selectInnerPagu         = "SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,";
            $selectInnerPaguPrognosa = "monira_data_prognosa.Lokasi as KodeWilayah,";
            $selectInnerBelanja      = "SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,";
            $group                   = "KodeWilayah,";
            $groupFinal              = "monira_ref_wilayah.KodeWilayah,";
            $selectList              = "monira_ref_wilayah.KodeWilayah as KodeHeader, monira_ref_wilayah.WilayahName as NamaHeader,";
            $join                    = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.KodeWilayah";
            $orderBy                 = "ORDER BY KodeHeader,Kode ASC";

            break;

        case "satker" :
            $selectInnerPagu         = "KdSatker,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,";
            $selectInnerPaguPrognosa = "KdSatker,monira_data_prognosa.Lokasi as KodeWilayah,";
            $selectInnerBelanja      = "KdSatker,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,";
            $group                   = "KodeWilayah,KdSatker,";
            $groupFinal              = "monira_ref_wilayah.KodeWilayah,monira_ref_satker.KodeSatker,";
            $selectList              = "monira_ref_wilayah.KodeWilayah as KodeHeader, monira_ref_wilayah.WilayahName as NamaHeader,monira_ref_satker.KodeSatker as KodeSubHeader,monira_ref_satker.NamaSatuanKerja as NamaSubHeader,";
            $join                    = "LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.KodeWilayah LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=UnionData.KdSatker";
            $orderBy                 = "ORDER BY KodeHeader,KodeSubHeader,Kode ASC";

            break;
    }

    return [
        'selectList'              => $selectList,
        'selectInnerPagu'         => $selectInnerPagu,
        'selectInnerPaguPrognosa' => $selectInnerPaguPrognosa,
        'selectInnerBelanja'      => $selectInnerBelanja,
        'groupBy'                 => $group,
        'join'                    => $join,
        'groupFinal'              => $groupFinal,
        'orderBy'                 => $orderBy
    ];

}

function PrepareSingleChart($total,$elemen) {
    $data = array();
    $totalrow = 0;
    foreach ($elemen as $item) {
        $totalrow += $item->Amount;
        $data[] = number_format(divnum($totalrow,$total)*100,2);
    }
    return $data;
}

function TotalSum($data) {
    $total = 0;
    $total+=$data;
    return $total;
}

function Persen($n) {
    $number=number_format($n,2,',','.');
    return $number;
}



function PersenKnop($n) {
    $number=number_format($n,0,',','');
    if($number>100) {
        $number = 100;
    }
    return $number;
}

function RP($n) {
    $number=number_format($n,0,',','.');
    return $number;
}

function level($n) {
    $level=0;
    switch($n) {
        case '3':
            $level = 'Satker';
        break;
        case '2':
        case '4':
        case '5':
            $level = 'Admin';
        break;
        default:
        break;
    }
    return $level;
}


function belanja($akun) {
    switch($akun) {
        case '51':
            $belanja = 'Pegawai';
        break;
        case '52':
            $belanja = 'Barang';
        break;
        case '53':
            $belanja = 'Modal';
        break;
    }
    return $belanja;
}


function onlynumber($n)
{
    return preg_replace("/[^0-9]/", "",$n);
}

function decimal($n)
{
    return preg_replace(",", ".",$n);
}


function nameofmonth($n) {
    $bulan=0;
    switch($n) {
        case '1':
            $bulan = 'Januari';
        break;
            case '2':
                $bulan = 'Februari';
            break;
                case '3':
                    $bulan = 'Maret';
                break;
                    case '4':
                        $bulan = 'April';
                        break;
                        case '5':
                            $bulan = 'Mei';
                            break;
                            case '6':
                                $bulan = 'Juni';
                                break;
                                case '7':
                                    $bulan = 'Juli';
                                    break;
                                    case '8':
                                        $bulan = 'Agustus';
                                        break;
                                        case '9':
                                            $bulan = 'September';
                                            break;
                                            case '10':
                                                $bulan = 'Oktober';
                                                break;
                                                case '11':
                                                    $bulan = 'November';
                                                    break;
                                                    case '12':
                                                        $bulan = 'Desember';
                                                    break;

        default:
        break;
    }
    return $bulan;
}

?>
