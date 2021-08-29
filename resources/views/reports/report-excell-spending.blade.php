@switch($unit)
        @case('eselon1')
            <table class="table table-sm" id="card">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}</b></td>
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
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}</b></td>
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
            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}</b></td>
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

            @break
        @default

    @endswitch
