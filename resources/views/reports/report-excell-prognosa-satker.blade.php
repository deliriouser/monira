
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

    <thead class="bg-primary">
        <tr>
            <th class="text-center">NO</th>
            <th class="text-start">KODE</th>
            <th class="text-start">KETERANGAN</th>
            <th class="text-center">DANA</th>
            <th class="text-center">PAGU</th>
            <th class="text-center">PROGNOSA</th>
            <th class="text-center">%</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $TotalPaguAwal  = 0;
            $TotalPaguAkhir = 0;
            $TotalRealisasi = 0;
            $TotalPrognosa = 0;
            $PaguAwalSub  = 0;
            $PaguAkhirSub = 0;
            $RealisasiSub = 0;
            $PrognosaSub = 0;

        @endphp
        @foreach ($data as $item)
        <tr class="table-danger">
            <td class="text-center"><b>{{$item->KodeHeader}}</b></td>
            <td class="text-start" colspan="9"><b>{{strtoupper($item->NamaHeader)}}</b></td>
        </tr>

        @foreach ($item->Data as $key => $value)
        <tr class="table-warning">
                <td class="text-center"{{$loop->iteration}}</td>
                <td class="text-center"><b>{{$key}}</b></td>
                <td class="text-start" colspan="9"><b>
                    @if (isset($value->KodeSubHeader))
                        {{strtoupper($value->NamaSubHeader)}}
                    @endif
                </b></td>
        </tr>
        @php
            $PaguAwal     = 0;
            $PaguAkhir    = 0;
            $Realisasi    = 0;
            $Prognosa    = 0;
        @endphp

        @foreach ($value->SubData as $detil)
        @php
            $PaguAwal  += $detil->PaguAwal ?? '0';
            $PaguAkhir += $detil->PaguAkhir ?? '0';
            $Realisasi += $detil->Realisasi ?? '0';
            $Prognosa  += $detil->Prognosa ?? '0';
        @endphp

        <tr valign="middle">
            <td class="text-center">{{$loop->iteration}}</td>
            <td class="text-center">{{$detil->Kode}}</td>
            <td class="text-start">{{$detil->Keterangan}}
                @if(isset($detil->Justifikasi))
                <br><small><i>Justifikasi : {{$detil->Justifikasi}}</i></small>
                @endif
            </td>
            <td class="text-center">{{($detil->NamaDana)}}</td>
            <td class="text-end">{{RP($detil->PaguAkhir)}}</td>
            <td class="text-end">{{RP($detil->Prognosa)}}</td>
            <td class="text-center">{{Persen($detil->Persen)}}%</td>
            <td class="text-center">
                @if(isset($detil->Prognosa) AND $detil->Prognosa>0)
                <a  dana="{{$detil->NamaDana}}" akun="{{$detil->Kode}}. {{$detil->Keterangan}}" output="{{$key}}. {{$value->NamaSubHeader}}" kegiatan="{{$item->KodeHeader}}. {{$item->NamaHeader}}" pagu="{{$detil->PaguAkhir}}" sisa="{{($detil->PaguAkhir - $detil->Prognosa)}}" id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" href="#" class="open-modal text-success static" style="font-size:20px;" action="updatePrognosa"><i class="fa fa-check-circle"></i></a>
                @else
                <a  dana="{{$detil->NamaDana}}" akun="{{$detil->Kode}}. {{$detil->Keterangan}}" output="{{$key}}. {{$value->NamaSubHeader}}" kegiatan="{{$item->KodeHeader}}. {{$item->NamaHeader}}" pagu="{{$detil->PaguAkhir}}"  id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" href="#" class="open-modal text-primary static" action="insertPrognosa" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                @endif
            </td>
        </tr>


        @endforeach

        @php
            $TotalPaguAwal  += $PaguAwal;
            $TotalPaguAkhir += $PaguAkhir;
            $TotalRealisasi += $Realisasi;
            $TotalPrognosa  += $Prognosa;
        @endphp

        <tr class="border-top-primary">
            <th class="text-center"></th>
            <th class="text-start"></th>
            <th class="text-start">JUMLAH</th>
            <th class="text-end"></th>
            <th class="text-end">{{RP($PaguAkhir)}}</th>
            <th class="text-end">{{RP($Prognosa)}}</th>
            <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
            <th></th>
        </tr>

        @php
        $PaguAwalSub  += $PaguAwal;
        $PaguAkhirSub += $PaguAkhir;
        $RealisasiSub += $Realisasi;
        $PrognosaSub  += $Prognosa;
        @endphp

        @endforeach

        <tr class="table-info">
            <th class="text-center"></th>
            <th class="text-start"></th>
            <th class="text-start">JUMLAH SUB</th>
            <th class="text-end"></th>
            <th class="text-end">{{RP($PaguAkhirSub)}}</th>
            <th class="text-end">{{RP($PrognosaSub)}}</th>
            <th class="text-center">{{Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)}}%</th>
            <th></th>
        </tr>

        @php
        $PaguAwalSub  = 0;
        $PaguAkhirSub = 0;
        $RealisasiSub = 0;
        $PrognosaSub  = 0;
        @endphp


        @endforeach
        <tr class="table-primary">
            <th class="text-center"></th>
            <th class="text-start"></th>
            <th class="text-start">JUMLAH RAYA</th>
            <th class="text-end"></th>
            <th class="text-end">{{RP($TotalPaguAkhir)}}</th>
            <th class="text-end">{{RP($TotalPrognosa)}}</th>
            <th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
            <th></th>
        </tr>
</table>
