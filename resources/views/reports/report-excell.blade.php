<table class="table">
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
        <td colspan="7"></td>
    </tr>
    <thead class="bg-primary text-white">
        <tr>
        <th class="text-center"><b>KODE</b></th>
        <th><b>KETERANGAN</b></th>
        <th class="text-center"><b>PAGU AWAL</b></th>
        <th class="text-center"><b>PAGU AKHIR</b></th>
        <th class="text-center"><b>REALISASI</b></th>
        <th class="text-center"><b>SISA</b></th>
        <th class="text-center"><b>%</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr class="table-{{ColorTable($item->Persen)}}">
            <td class="text-center">{{$item->Kode}}</td>
            <td class="text-start">{{($item->Keterangan)}}</td>
            <td class="text-end">{{($item->PaguAwal)}}</td>
            <td class="text-end">{{($item->Pagu)}}</td>
            <td class="text-end">{{($item->Realisasi)}}</td>
            <td class="text-end">{{($item->Sisa)}}</td>
            <td class="text-center"><span class="badge badge-{{ColorTable($item->Persen)}}">{{persen($item->Persen)}}%</span></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot class="table-footer text-dark">
        <tr>
            <th class="text-center"></th>
            <th>JUMLAH</th>
            <th class="text-end">{{($data->sum('PaguAwal'))}}</th>
            <th class="text-end">{{($data->sum('Pagu'))}}</th>
            <th class="text-end">{{($data->sum('Realisasi'))}}</th>
            <th class="text-end">{{($data->sum('Sisa'))}}</th>
            <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
        </tr>
        </tfoot>
</table>

</body>
</html>
