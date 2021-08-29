@switch($unit)
        @case('eselon1')
            <table class="table table-sm" id="card">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19 PER JENIS {{STRTOUPPER($segment)}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>TAHUN ANGGARAN {{$year}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}}</b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center"><b>NO</b></th>
                        <th class="text-center"><b>KODE</b></th>
                        <th class="text-center"><b>KETERANGAN</b></th>
                        <th class="text-center"><b>PAGU AWAL</b></th>
                        <th class="text-center"><b>PAGU AKHIR</b></th>
                        <th class="text-center"><b>REALISASI</b></th>
                        <th class="text-center"><b>%</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$item['Kode']}}</td>
                        <td class="text-start">{{$item['Keterangan']}}</td>
                        <td class="text-end">{{($item['PaguAwal'])}}</td>
                        <td class="text-end">{{($item['Pagu'])}}</td>
                        <td class="text-end">{{($item['Realisasi'])}}</td>
                        <td class="text-center">{{Persen($item['Persen'])}}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-start"><b>JUMLAH</b></th>
                        <th class="text-end"><b>{{($data->sum('PaguAwal'))}}</b></th>
                        <th class="text-end"><b>{{($data->sum('Pagu'))}}</b></th>
                        <th class="text-end"><b>{{($data->sum('Realisasi'))}}</b></th>
                        <th class="text-center"><b>{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</b></th>
                    </tr>
                </tfoot>
            </table>

            @break

        @case('propinsi')
            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19 PER JENIS {{STRTOUPPER($segment)}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>TAHUN ANGGARAN {{$year}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}}</b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>

                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center"><b>NO</b></th>
                        <th class="text-center"><b>KODE</b></th>
                        <th class="text-center"><b>KETERANGAN</b></th>
                        <th class="text-center"><b>PAGU AWAL</b></th>
                        <th class="text-center"><b>PAGU AKHIR</b></th>
                        <th class="text-center"><b>REALISASI</b></th>
                        <th class="text-center"><b>%</b></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="6"><b>{{$item->NamaHeader}}</b></td>
                    </tr>
                    @php
                        $noSatker=0;
                        $PaguAwal=0;
                        $PaguAkhir=0;
                        $Realisasi=0;
                    @endphp
                    @foreach($item->Data as $satker)
                    @php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                    @endphp
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$satker->Kode}}</td>
                        <td class="text-start">{{$satker->Keterangan}}</td>
                        <td class="text-end">{{($satker->PaguAwal)}}</td>
                        <td class="text-end">{{($satker->PaguAkhir)}}</td>
                        <td class="text-end">{{($satker->Realisasi)}}</td>
                        <td class="text-center">{{Persen($satker->Persen)}}%</td>
                    </tr>
                    @endforeach

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH</b></th>
                        <th class="text-end"><b>{{($PaguAwal)}}</b></th>
                        <th class="text-end"><b>{{($PaguAkhir)}}</b></th>
                        <th class="text-end"><b>{{($Realisasi)}}</b></th>
                        <th class="text-center"><b>{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</b></th>
                    </tr>

                    @php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    @endphp

                    @endforeach
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH RAYA</b></th>
                        <th class="text-end"><b>{{($TotalPaguAwal)}}</b></th>
                        <th class="text-end"><b>{{($TotalPaguAkhir)}}</b></th>
                        <th class="text-end"><b>{{($TotalRealisasi)}}</b></th>
                        <th class="text-center"><b>{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</b></th>
                    </tr>
                </tfoot>
            </table>

            @break

            @case('satker')
            @if($segment!='volume')
            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19 PER JENIS {{STRTOUPPER($segment)}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>TAHUN ANGGARAN {{$year}}</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}}</b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>

                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center"><b>NO</b></th>
                        <th class="text-center"><b>KODE</b></th>
                        <th class="text-center"><b>KETERANGAN</b></th>
                        <th class="text-center"><b>PAGU AWAL</b></th>
                        <th class="text-center"><b>PAGU AKHIR</b></th>
                        <th class="text-center"><b>REALISASI</b></th>
                        <th class="text-center"><b>%</b></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $PaguAwalSub  = 0;
                        $PaguAkhirSub = 0;
                        $RealisasiSub = 0;

                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="6"><b>{{$item->NamaHeader}}</b></td>
                    </tr>

                    @foreach ($item->Data as $key => $value)
                    <tr class="table-warning">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center"><b>{{$key}}</b></td>
                            <td class="text-start" colspan="5"><b>
                                @if (isset($value->KodeSubHeader))
                                    {{$value->NamaSubHeader}}
                                @endif
                            </b></td>
                    </tr>
                    @php
                        $PaguAwal     = 0;
                        $PaguAkhir    = 0;
                        $Realisasi    = 0;
                    @endphp

                    @foreach ($value->SubData as $detil)
                    @php
                        $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                    @endphp

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$detil->Kode}}</td>
                        <td class="text-start">{{$detil->Keterangan}}</td>
                        <td class="text-end">{{($detil->PaguAwal)}}</td>
                        <td class="text-end">{{($detil->PaguAkhir)}}</td>
                        <td class="text-end">{{($detil->Realisasi)}}</td>
                        <td class="text-center">{{Persen($detil->Persen)}}%</td>
                    </tr>


                    @endforeach

                    @php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                    @endphp

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH</b></th>
                        <th class="text-end"><b>{{($PaguAwal)}}</b></th>
                        <th class="text-end"><b>{{($PaguAkhir)}}</b></th>
                        <th class="text-end"><b>{{($Realisasi)}}</b></th>
                        <th class="text-center"><b>{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</b></th>
                    </tr>

                    @php
                    $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    @endphp

                    @endforeach

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH PROPINSI</b></th>
                        <th class="text-end"><b>{{($PaguAwalSub)}}</b></th>
                        <th class="text-end"><b>{{($PaguAkhirSub)}}</b></th>
                        <th class="text-end"><b>{{($RealisasiSub)}}</b></th>
                        <th class="text-center"><b>{{Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)}}%</b></th>
                    </tr>

                    @php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    @endphp


                    @endforeach
                    <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start"><b>JUMLAH RAYA</b></th>
                        <th class="text-end"><b>{{($TotalPaguAwal)}}</b></th>
                        <th class="text-end"><b>{{($TotalPaguAkhir)}}</b></th>
                        <th class="text-end"><b>{{($TotalRealisasi)}}</b></th>
                        <th class="text-center"><b>{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</b></th>
                    </tr>
                    </tfoot>

            </table>
            @else

            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="12"><b>REKAPITULASI REALISASI DAYA SERAP PENANGANAN COVID-19</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>DIREKTORAT JENDERAL PERHUBUNGAN LAUT</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>TAHUN ANGGARAN {{$year}}</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>PERIODE S.D BULAN {{strtoupper(nameofmonth($month))}}</b> </td>
                </tr>
                <tr>
                    <td></td>
                </tr>

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
                    @php
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

                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="11"><b>{{$item->NamaHeader}}</b></td>
                    </tr>

                    @php
                        $PaguAkhirSub_kegiatan = 0;
                        $RealisasiSub_kegiatan = 0;
                        $SisaSub_kegiatan      = 0;

                    @endphp
                @foreach ($item->Data as $key => $value)
                    <tr class="table-warning">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center"><b>{{$key}}</b></td>
                            <td class="text-start" colspan="10"><b>
                                @if (isset($value->KodeSubHeader))
                                    {{$value->NamaSubHeader}}
                                @endif
                            </b></td>
                    </tr>
                    @php
                        $PaguAkhir = 0;
                        $Realisasi = 0;
                        $Sisa      = 0;
                        $PaguKegiatan_satker     = 0;
                        $BelanjaKegiatan_satker  = 0;
                        $SisaKegiatan_satker     = 0;

                    @endphp

                        @foreach ($value->SubData as $output => $valoutput)
                        @foreach ($valoutput->SubDataKegiatan as $kegiatan => $valkegiatan)
                        @foreach ($valkegiatan->SubDataDana as $dana => $valdana)
                        @foreach ($valdana->SubDataAkun as $akun => $detil)

                    @php
                        $PaguAkhir              += $detil->PaguAkhir ?? '0';
                        $Realisasi              += $detil->Realisasi ?? '0';
                        $Sisa                   += $detil->Sisa ?? '0';
                    @endphp

                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center">{{$valkegiatan->KodeKegiatan}}.{{$valoutput->KodeOutput}}.{{$detil->Kode}}</th>
                        <th class="text-start">{{$detil->Keterangan}}</th>
                        <th class="text-center">{{$detil->SumberDana}}</th>
                        <th></th>
                        <th class="text-end">{{($detil->PaguAkhir)}}</th>
                        <th></th>
                        <th></th>
                        <th class="text-end">{{($detil->Realisasi)}}</th>
                        <th></th>
                        <th class="text-center">{{Persen($detil->Persen)}}%</th>
                        <th class="text-end">{{($detil->Sisa)}}</th>
                    </tr>
                    @php
                        $PaguKegiatan    = 0;
                        $BelanjaKegiatan = 0;
                        $SisaKegiatan    = 0;

                    @endphp
                    @foreach ($detil->SubDataKegiatan as $kegiatan)
                    @if($kegiatan->Uraian!='0')
                    @php
                    $PaguKegiatan          += $kegiatan->PaguKegiatan;
                    $BelanjaKegiatan       += $kegiatan->BelanjaKegiatan;
                    $SisaKegiatan          += $kegiatan->SisaKegiatan;

                    $PaguAkhirSub_kegiatan += $kegiatan->PaguKegiatan;
                    $RealisasiSub_kegiatan += $kegiatan->BelanjaKegiatan;
                    $SisaSub_kegiatan      += $kegiatan->SisaKegiatan;


                    @endphp

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-end"></td>
                        <td class="text-start">
                            {{$kegiatan->Uraian}}
                                @if(!empty($kegiatan->Catatan))<br>
                                <small><i>{{$kegiatan->Catatan}}</i>
                                </small>
                                @endif</td>
                        <td class="text-center">{{$detil->SumberDana}}</td>
                        <td class="text-end"><span class="nowrap">{{($kegiatan->VolumePagu)}} {{$kegiatan->SatuanPagu}}</span></td>
                        <td class="text-end">{{($kegiatan->PaguKegiatan)}}</td>
                        <td class="text-center"></td>

                        <td class="text-end"><span class="nowrap">@if(!empty($kegiatan->VolumeBelanja)){{($kegiatan->VolumeBelanja)}} {{$kegiatan->SatuanBelanja}} @endif</span></td>
                        <td class="text-end">{{($kegiatan->BelanjaKegiatan)}}</td>
                        <td class="text-start"><small class="nowrap">{!!nl2br($kegiatan->Tglsp2d)!!}</small></td>

                        <td class="text-center">{{Persen($kegiatan->PersenKegiatan)}}%</td>
                        <td class="text-end">{{($kegiatan->SisaKegiatan)}}</td>
                    </tr>
                    @endif
                    @endforeach
                    @php
                        $selisih_pagu      = $PaguAkhir-$PaguKegiatan;
                        $selisih_realisasi = $Realisasi-$BelanjaKegiatan;
                        $total_selisih     = $selisih_pagu+$selisih_realisasi;


                    @endphp
                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th></th>
                        <th class="text-start">SUB JUMLAH</th>
                        <th class="text-center text-danger">@if($total_selisih!=0) <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Terdapat Perbedaan Data UPT dan SPAN" class="icofont icofont-warning-alt"></i> @endif</th>
                        <th></th>
                        <th class="text-end">{{($PaguKegiatan)}}</th>
                        <th></th>
                        <th></th>
                        <th class="text-end">{{($BelanjaKegiatan)}}</th>
                        <th></th>
                        <th class="text-center">{{Persen(divnum($BelanjaKegiatan,$PaguKegiatan)*100)}}%</th>
                        <th class="text-end">{{($SisaKegiatan)}}</th>
                    </tr>

                    @php
                        $PaguKegiatan_satker    += $PaguKegiatan;
                        $BelanjaKegiatan_satker += $BelanjaKegiatan;
                        $SisaKegiatan_satker    += $SisaKegiatan;
                    @endphp

                    @endforeach

                    @endforeach

                    @endforeach


                    @endforeach

                    <tr class="border-top-primary table-info">
                        <th class="text-center"></th>
                        <th></th>
                        <th class="text-start">JUMLAH SATKER</th>
                        <th class="text-center text-danger"></th>
                        <th></th>
                        <th class="text-end">{{($PaguKegiatan_satker)}}</th>
                        <th></th>
                        <th></th>
                        <th class="text-end">{{($BelanjaKegiatan_satker)}}</th>
                        <th></th>
                        <th class="text-center">{{Persen(divnum($BelanjaKegiatan_satker,$PaguKegiatan_satker)*100)}}%</th>
                        <th class="text-end">{{($SisaKegiatan_satker)}}</th>
                    </tr>

                    @php
                        $TotalPaguAkhir          += $PaguAkhir;
                        $TotalRealisasi          += $Realisasi;
                        $TotalSisa               += $Sisa;
                    @endphp

                    {{-- <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{($PaguAkhir)}}</th>
                        <th class="text-end">{{($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                        <th class="text-end">{{($Sisa)}}</th>
                    </tr> --}}

                    @php
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    $SisaSub      += $Sisa;
                    @endphp

                    @endforeach

                    <tr class="table-danger">
                        <th class="text-center"></th>
                        <th class="text-start" colspan="3">JUMLAH PROPINSI {{$item->NamaHeader}}</th>
                        <th class="text-start"></th>
                        <th class="text-end">{{($PaguAkhirSub_kegiatan)}}</th>
                        <th class="text-start"></th>
                        <th></th>
                        <th class="text-end">{{($RealisasiSub_kegiatan)}}</th>
                        <th></th>
                        <th class="text-center">{{Persen(divnum($RealisasiSub_kegiatan,$PaguAkhirSub_kegiatan)*100)}}%</th>
                        <th class="text-end">{{($SisaSub_kegiatan)}}</th>
                    </tr>

                    @php
                    $PaguAkhirSub             = 0;
                    $RealisasiSub             = 0;
                    $SisaSub                  = 0;
                    $TotalPaguAkhir_kegiatan += $PaguAkhirSub_kegiatan;
                    $TotalRealisasi_kegiatan += $RealisasiSub_kegiatan;
                    $TotalSisa_kegiatan      += $SisaSub_kegiatan;

                    @endphp


                    @endforeach
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start" colspan="3">JUMLAH RAYA UPT</th>
                        <th class="text-start"></th>
                        <th class="text-end">{{($TotalPaguAkhir_kegiatan)}}</th>
                        <th class="text-start"></th>
                        <th></th>
                        <th class="text-end">{{($TotalRealisasi_kegiatan)}}</th>
                        <th></th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi_kegiatan,$TotalPaguAkhir_kegiatan)*100)}}%</th>
                        <th class="text-end">{{($TotalSisa_kegiatan)}}</th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start" colspan="3">JUMLAH RAYA SPAN</th>
                        <th class="text-start"></th>
                        <th class="text-end">{{($TotalPaguAkhir)}}</th>
                        <th class="text-start"></th>
                        <th></th>
                        <th class="text-end">{{($TotalRealisasi)}}</th>
                        <th></th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                        <th class="text-end">{{($TotalSisa)}}</th>
                    </tr>

            </table>

            @endif

            @break
        @default

    @endswitch
