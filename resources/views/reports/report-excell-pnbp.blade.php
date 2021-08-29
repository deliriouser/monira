

<table class="table">
    <thead class="bg-primary text-white">
      <tr>
          <td colspan="5"><b>REKAPITULASI REALISASI PNBP</b></td>
      </tr>
      <tr>
        <td colspan="5"><b>{{Auth::user()->satker->NamaSatuanKerja}}</b></td>
    </tr>
    <tr>
        <td colspan="5"><b>TAHUN ANGGARAN {{$year}}</b></td>
    </tr>
    <tr>
        <td colspan="5"></td>
    </tr>

      <tr>
        <th class="text-center"><b>KODE</b></th>
        <th class="text-center"><b>JENIS JASA</b></th>
        <th class="text-center"><b>TARGET</b></th>
        <th class="text-center"><b>REALISASI</b></th>
        <th class="text-center"><b>%</b></th>
      </tr>
    </thead>
    @php
    $target_fungsional=0;
    $realisasi_fungsional=0;
    @endphp

    @foreach ($data as $item)
    @if ($item->jenis=='F')
    @php
    $target_fungsional+=$item->target;
    $realisasi_fungsional+=$item->span;
    @endphp
    @endif
    @endforeach
    @php
        // $persen_fungsional = ($realisasi_fungsional/$target_fungsional)*100;
    @endphp
    <thead class="bg-subheader">
        <tr>
        <th colspan="2" class="text-start"><b>PNBP FUNGSIONAL</b></th>
        <th class="text-end">{{($target_fungsional)}}</th>
        <th class="text-end">{{($realisasi_fungsional)}}</th>
        <td class="text-center"><span class="badge badge-{{ColorTable(divnum($realisasi_fungsional,$target_fungsional)*100)}}">{{persen(divnum($realisasi_fungsional,$target_fungsional)*100)}}%</span></td>
    </tr>
    </thead>

    <tbody>
        @foreach ($data as $item)
            @if($item->jenis=="F")
            <tr class="table-{{ColorTable($item->persen_span)}}">
                <td class="text-center">{{$item->akun}}</td>
                <td class="text-start">{{$item->uraian}}</td>
                <td class="text-end">{{($item->target)}}</td>
                <td class="text-end">{{($item->span)}}</td>
                <td class="text-center"><span class="badge badge-{{ColorTable($item->persen_span)}}">{{persen($item->persen_span)}}%</span></td>
            </tr>
            @endif
        @endforeach
    </tbody>
    @php
        $target_nonfungsional=0;
        $realisasi_nonfungsional=0;
    @endphp

    @foreach ($data as $item)
        @if ($item->jenis=='U')
        @php
        $target_nonfungsional+=$item->target;
        $realisasi_nonfungsional+=$item->span;
        @endphp
        @endif
    @endforeach
    @php
    // $persen_nonfungsional = ($realisasi_nonfungsional/$target_nonfungsional)*100;
    @endphp

    <thead class="bg-subheader">
    <tr>
        <th colspan="2" class="text-start"><b>PNBP NON FUNGSIONAL</b></th>
        <th class="text-end">{{($target_nonfungsional)}}</th>
        <th class="text-end">{{($realisasi_nonfungsional)}}</th>
        <td class="text-center"><span class="badge badge-{{ColorTable(divnum($realisasi_nonfungsional,$target_nonfungsional)*100)}}">{{persen(divnum($realisasi_nonfungsional,$target_nonfungsional)*100)}}%</span></td>
    </tr>
    </thead>

    <tbody>
        @foreach ($data as $item)
            @if($item->jenis=="U")
            <tr class="table-{{ColorTable($item->persen_span)}}">
                <td class="text-center">{{$item->akun}}</td>
                <td class="text-start">{{$item->uraian}}</td>
                <td class="text-end">{{($item->target)}}</td>
                <td class="text-end">{{($item->span)}}</td>
                <td class="text-center"><span class="badge badge-{{ColorTable($item->persen_span)}}">{{persen($item->persen_span)}}%</span></td>
            </tr>
            @endif
        @endforeach

    </tbody>

    <tfoot class="table-footer text-dark">
        <tr>
          <th></th>
          <th><b>JUMLAH</b></th>
          <th class="text-end"><b>{{($data->sum('target'))}}</b></th>
          <th class="text-end"><b>{{($data->sum('span'))}}</b></th>
          <th class="text-center"><b>{{Persen(divnum($data->sum('span'),$data->sum('target'))*100)}}%</b></th>
        </tr>
      </tfoot>
  </table>
