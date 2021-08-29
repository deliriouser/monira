
@switch($unit)
    @case('eselon1')
    <table class="table table-sm table-striped" id="page-all">
        <tr>
            <td colspan="8"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN PER SATKER</b></td>
        </tr>
        <tr>
            <td colspan="8"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
        </tr>
        <tr>
            <td colspan="8"><b>TAHUN ANGGARAN {{$year}}</b></td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>

        <thead class="bg-primary">
            <tr>
                <th class="text-center"><b>NO</b></th>
                <th class="text-start"><b>KODE</b></th>
                <th class="text-start"><b>NAMA SATKER</b></th>
                <th class="text-start"><b>PROPINSI</b></th>
                <th class="text-end"><b>PAGU AWAL</b></th>
                <th class="text-end"><b>PAGU AKHIR</b></th>
                <th class="text-end"><b>REALISASI</b></th>
                <th class="text-center"><b>%</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr @if($item->KodeSatker==Auth:: user()->kdsatker) class="bg-danger" @endif>
                <td class="text-center">{{$loop->iteration}}
                    @if($item->KodeSatker==Auth:: user()->kdsatker) <a name="{{Auth:: user()->kdsatker}}"></a> @endif
                </td>
                <td class="text-center">{{$item->KodeSatker}}</td>
                <td class="text-start">{{$item->NamaSatuanKerja}}</td>
                <td class="text-start">{{$item->WilayahName}}</td>
                <td class="text-end">{{($item->PaguAwal)}}</td>
                <td class="text-end">{{($item->Pagu)}}</td>
                <td class="text-end">{{($item->Realisasi)}}</td>
                <td class="text-center">{{Persen($item->Persen)}}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start"></th>
                <th class="text-start"><b>JUMLAH</b></th>
                <th class="text-end"><b>{{($data->sum('PaguAwal'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Pagu'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Realisasi'))}}</b></th>
                <th class="text-end"><b>{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</b></th>
            </tr>
        </tfoot>
    </table>

        @break
    @case('propinsi')


    <table class="table table-sm table-striped" id="page-all">
        <tr>
            <td colspan="7"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN PER PROPINSI</b></td>
        </tr>
        <tr>
            <td colspan="7"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
        </tr>
        <tr>
            <td colspan="7"><b>TAHUN ANGGARAN {{$year}}</b></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>

        <thead class="bg-primary">
            <tr>
                <th class="text-center"><b>NO</b></th>
                <th class="text-start"><b>KODE</b></th>
                <th class="text-start"><b>PROPINSI</b></th>
                <th class="text-end"><b>PAGU AWAL</b></th>
                <th class="text-end"><b>PAGU AKHIR</b></th>
                <th class="text-end"><b>REALISASI</b></th>
                <th class="text-center"><b>%</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$item->KodeWilayah}}</td>
                <td class="text-start">{{$item->WilayahName}}</td>
                <td class="text-end">{{($item->PaguAwal)}}</td>
                <td class="text-end">{{($item->Pagu)}}</td>
                <td class="text-end">{{($item->Realisasi)}}</td>
                <td class="text-center">{{Persen($item->Persen)}}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-end"><b>JUMLAH</b></th>
                <th class="text-end"><b>{{($data->sum('PaguAwal'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Pagu'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Realisasi'))}}</b></th>
                <th class="text-center"><b>{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</b></th>
            </tr>
        </tfoot>
    </table>
        @break
    @case('satker')
    <table class="table table-sm" id="page-all">

        <tr>
            <td colspan="7"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN PER PROPINSI SATKER</b></td>
        </tr>
        <tr>
            <td colspan="7"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
        </tr>
        <tr>
            <td colspan="7"><b>TAHUN ANGGARAN {{$year}}</b></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>

        <thead class="bg-primary">
            <tr>
                <th class="text-center"><b>NO</b></th>
                <th class="text-start"><b>KODE</b></th>
                <th class="text-start"><b>PROPINSI</b></th>
                <th class="text-end"><b>PAGU AWAL</b></th>
                <th class="text-end"><b>PAGU AKHIR</b></th>
                <th class="text-end"><b>REALISASI</b></th>
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
            <tr class="table-danger">
                <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
                <td class="text-start" colspan="7"><b>{{$item->NamaHeader}}</b></td>
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
                <td class="text-center">{{$noSatker=$noSatker+1}}</td>
                <td class="text-center">{{$satker->Kode}}</td>
                <td class="text-start">{{$satker->Keterangan}}</td>
                <td class="text-end">{{($satker->PaguAwal)}}</td>
                <td class="text-end">{{($satker->PaguAkhir)}}</td>
                <td class="text-end">{{($satker->Realisasi)}}</td>
                <td class="text-center">{{Persen($satker->Persen)}}%</td>
            </tr>
            @endforeach

            <tr class="border-top-primary">
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
            <tr class="table-primary">
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start"><b>JUMLAH RAYA</b></th>
                <th class="text-end"><b>{{($TotalPaguAwal)}}</b></th>
                <th class="text-end"><b>{{($TotalPaguAkhir)}}</b></th>
                <th class="text-end"><b>{{($TotalRealisasi)}}</b></th>
                <th class="text-center"><b>{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</b></th>
            </tr>
    </table>
        @break
@endswitch



