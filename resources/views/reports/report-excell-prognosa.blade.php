@switch($unit)
        @case('eselon1')

            <table class="table table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <td colspan="8">REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}</td>
                    </tr>
                    <tr>
                        <td colspan="8">{{Auth::user()->satker->NamaSatuanKerja}}</td>
                    </tr>
                    <tr>
                        <td colspan="8">TAHUN ANGGARAN {{$year}}</td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                    </tr>
                   <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$item['Kode']}}</td>
                        <td class="text-start">{{$item['Keterangan']}}</td>
                        <td class="text-end">{{($item['Pagu'])}}</td>
                        <td class="text-end">{{($item['Realisasi'])}}</td>
                        <td class="text-center">{{Persen($item['Persen'])}}%</td>
                        <td class="text-end">{{($item['Prognosa'])}}</td>
                        <td class="text-center">{{Persen($item['PersenPrognosa'])}}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{($data->sum('Pagu'))}}</th>
                        <th class="text-end">{{($data->sum('Realisasi'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
                        <th class="text-end">{{($data->sum('Prognosa'))}}</th>
                        <th class="text-center">{{Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)}}%</th>
                    </tr>
                </tfoot>
            </table>

            @break

        @case('propinsi')
            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="8">REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}</td>
                </tr>
                <tr>
                    <td colspan="8">{{Auth::user()->satker->NamaSatuanKerja}}</td>
                </tr>
                <tr>
                    <td colspan="8">TAHUN ANGGARAN {{$year}}</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>

                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal=0;
                        $TotalPaguAkhir=0;
                        $TotalRealisasi=0;
                        $TotalPrognosa=0;
                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger bg-subheader">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="7"><b>{{$item->NamaHeader}}</b></td>
                    </tr>
                    @php
                        $noSatker=0;
                        $PaguAwal=0;
                        $PaguAkhir=0;
                        $Realisasi=0;
                        $Prognosa=0;
                    @endphp
                    @foreach($item->Data as $satker)
                    @php
                        $PaguAwal+=$satker->PaguAwal;
                        $PaguAkhir+=$satker->PaguAkhir;
                        $Realisasi+=$satker->Realisasi;
                        $Prognosa+=$satker->Prognosa;
                    @endphp
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$satker->Kode}}</td>
                        <td class="text-start">{{$satker->Keterangan}}</td>
                        <td class="text-end">{{($satker->PaguAkhir)}}</td>
                        <td class="text-end">{{($satker->Realisasi)}}</td>
                        <td class="text-center">{{Persen($satker->Persen)}}%</td>
                        <td class="text-end">{{($satker->Prognosa)}}</td>
                        <td class="text-center">{{Persen($satker->PersenPrognosa)}}%</td>
                    </tr>
                    @endforeach

                    <tr class="border-top-primary bg-sumheader">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{($PaguAkhir)}}</th>
                        <th class="text-end">{{($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                        <th class="text-end">{{($Prognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                    </tr>

                    @php
                        $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa += $Prognosa;
                    @endphp

                    @endforeach
                </tbody>
                <tfoot class="table-footer text-dark">
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end">{{($TotalPaguAkhir)}}</th>
                        <th class="text-end">{{($TotalRealisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                        <th class="text-end">{{($TotalPrognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
                    </tr>
                </tfoot>
            </table>

        @break

        @case('satker')

            <table class="table table-sm" id="page-all">
                <tr>
                    <td colspan="8">REKAPITULASI REALISASI DAN PROGNOSA DAYA SERAP PER JENIS {{STRTOUPPER($segment)}}</td>
                </tr>
                <tr>
                    <td colspan="8">{{Auth::user()->satker->NamaSatuanKerja}}</td>
                </tr>
                <tr>
                    <td colspan="8">TAHUN ANGGARAN {{$year}}</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
           <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">PAGU AKHIR</th>
                        <th class="text-center">REALISASI</th>
                        <th class="text-center">%</th>
                        <th class="text-center">PROGNOSA</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $TotalPaguAwal  = 0;
                        $TotalPaguAkhir = 0;
                        $TotalRealisasi = 0;
                        $TotalPrognosa  = 0;
                        $PaguAwalSub    = 0;
                        $PaguAkhirSub   = 0;
                        $RealisasiSub   = 0;
                        $PrognosaSub    = 0;

                    @endphp
                    @foreach ($data as $item)
                    <tr class="table-danger">
                        <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                        <td class="text-start" colspan="7"><b>{{$item->NamaHeader}}</b></td>
                    </tr>

                    @foreach ($item->Data as $key => $value)
                    <tr class="table-warning">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center"><b>{{$key}}</b></td>
                            <td class="text-start" colspan="6"><b>
                                @if (isset($value->KodeSubHeader))
                                    {{$value->NamaSubHeader}}
                                @endif
                            </b></td>
                    </tr>
                    @php
                        $PaguAwal  = 0;
                        $PaguAkhir = 0;
                        $Realisasi = 0;
                        $Prognosa  = 0;

                    @endphp

                    @foreach ($value->SubData as $detil)
                    @php
                        // $PaguAwal  += $detil->PaguAwal ?? '0';
                        $PaguAkhir += $detil->PaguAkhir ?? '0';
                        $Realisasi += $detil->Realisasi ?? '0';
                        $Prognosa  += $detil->Prognosa;

                    @endphp

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{$detil->Kode}}</td>
                        <td class="text-start">{{$detil->Keterangan}}</td>
                        <td class="text-end">{{($detil->PaguAkhir)}}</td>
                        <td class="text-end">{{($detil->Realisasi)}}</td>
                        <td class="text-center">{{Persen($detil->Persen)}}%</td>
                        <td class="text-end">{{($detil->Prognosa)}}</td>
                        <td class="text-center">{{Persen($detil->PersenPrognosa)}}%</td>
                    </tr>


                    @endforeach

                    @php
                        // $TotalPaguAwal  += $PaguAwal;
                        $TotalPaguAkhir += $PaguAkhir;
                        $TotalRealisasi += $Realisasi;
                        $TotalPrognosa  += $Prognosa;

                    @endphp

                    <tr class="border-top-primary">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH</th>
                        <th class="text-end">{{($PaguAkhir)}}</th>
                        <th class="text-end">{{($Realisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                        <th class="text-end">{{($Prognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                    </tr>

                    @php
                    // $PaguAwalSub  += $PaguAwal;
                    $PaguAkhirSub += $PaguAkhir;
                    $RealisasiSub += $Realisasi;
                    $PrognosaSub  += $Prognosa;
                    @endphp

                    @endforeach

                    <tr class="table-info">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH PROPINSI</th>
                        <th class="text-end">{{($PaguAkhirSub)}}</th>
                        <th class="text-end">{{($RealisasiSub)}}</th>
                        <th class="text-center">{{Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)}}%</th>
                        <th class="text-end">{{($PrognosaSub)}}</th>
                        <th class="text-center">{{Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)}}%</th>
                    </tr>

                    @php
                    $PaguAwalSub  = 0;
                    $PaguAkhirSub = 0;
                    $RealisasiSub = 0;
                    $PrognosaSub  = 0;
                    @endphp


                    @endforeach
                    <tr class="table-footer text-dark">
                        <th class="text-center"></th>
                        <th class="text-start"></th>
                        <th class="text-start">JUMLAH RAYA</th>
                        <th class="text-end">{{($TotalPaguAkhir)}}</th>
                        <th class="text-end">{{($TotalRealisasi)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                        <th class="text-end">{{($TotalPrognosa)}}</th>
                        <th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
                    </tr>

            </table>

        @break

    @endswitch
