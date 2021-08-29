

    <table class="table table-sm table-striped" id="page-all">
        <tr>
            <td colspan="8"><b>REKAPITULASI RANKING DAYA SERAP ANGGARAN</b></td>
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


</body>
</html>
