@switch($segment)
    @case('belanja_covid')
    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS BELANJA PENANGANAN COVID-19</b></td>
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
            @php
            $TotalPaguAwal=0;
            $TotalPaguAkhir=0;
            $TotalRealisasi=0;
            $TotalSisa=0;
            @endphp

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
            @php
            $TotalPaguAwal  += $item->PaguAwal;
            $TotalPaguAkhir += $item->Pagu;
            $TotalRealisasi += $item->Realisasi;
            $TotalSisa      += $item->Sisa;
            @endphp

            @endforeach
        </tbody>
        <tfoot class="table-footer text-dark">
            <tr>
              <th class="text-center"></th>
              <th>JUMLAH</th>
              <th class="text-end">{{($TotalPaguAwal)}}</th>
              <th class="text-end">{{($TotalPaguAkhir)}}</th>
              <th class="text-end">{{($TotalRealisasi)}}</th>
              <th class="text-end">{{($TotalSisa)}}</th>
              <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
            </tr>
          </tfoot>
      </table>

        @break
    @case('kegiatan_covid')
    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS KEGIATAN PENANGANAN COVID-19</b></td>
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
            @php
            $TotalPaguAwal=0;
            $TotalPaguAkhir=0;
            $TotalRealisasi=0;
            $TotalSisa=0;
            @endphp

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
            @php
            $TotalPaguAwal  += $item->PaguAwal;
            $TotalPaguAkhir += $item->Pagu;
            $TotalRealisasi += $item->Realisasi;
            $TotalSisa      += $item->Sisa;
            @endphp

            @endforeach
        </tbody>
        <tfoot class="table-footer text-dark">
            <tr>
              <th class="text-center"></th>
              <th>JUMLAH</th>
              <th class="text-end">{{($TotalPaguAwal)}}</th>
              <th class="text-end">{{($TotalPaguAkhir)}}</th>
              <th class="text-end">{{($TotalRealisasi)}}</th>
              <th class="text-end">{{($TotalSisa)}}</th>
              <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
            </tr>
          </tfoot>
      </table>
        @break
    @case('sumberdana_covid')
    <table class="table">
        <tr>
            <td colspan="7"><b>REKAPITULASI REALISASI DAYA SERAP PER JENIS SUMBER DANA PENANGANAN COVID-19</b></td>
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
            @php
            $TotalPaguAwal=0;
            $TotalPaguAkhir=0;
            $TotalRealisasi=0;
            $TotalSisa=0;
            @endphp

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
            @php
            $TotalPaguAwal  += $item->PaguAwal;
            $TotalPaguAkhir += $item->Pagu;
            $TotalRealisasi += $item->Realisasi;
            $TotalSisa      += $item->Sisa;
            @endphp

            @endforeach
        </tbody>
        <tfoot class="table-footer text-dark">
            <tr>
              <th class="text-center"></th>
              <th>JUMLAH</th>
              <th class="text-end">{{($TotalPaguAwal)}}</th>
              <th class="text-end">{{($TotalPaguAkhir)}}</th>
              <th class="text-end">{{($TotalRealisasi)}}</th>
              <th class="text-end">{{($TotalSisa)}}</th>
              <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
            </tr>
          </tfoot>
      </table>
    @break

    @default

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
                <th><b>JUMLAH</b></th>
                <th class="text-end"><b>{{($data->sum('PaguAwal'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Pagu'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Realisasi'))}}</b></th>
                <th class="text-end"><b>{{($data->sum('Sisa'))}}</b></th>
                <th class="text-center"><b>{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</b></th>
            </tr>
            </tfoot>
    </table>

@endswitch
