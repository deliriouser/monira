
<table class="table table-sm" id="card">
    <tr>
        <td colspan="8"><b>REKAPITULASI</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>KEGIATAN PADAT KARYA</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>TAHUN ANGGARAN {{$year}}</b></td>
    </tr>
    <tr>
        <td></td>
    </tr>
        <thead class="bg-primary">
            <tr>
                <th class="text-center">NO</th>
                <th class="text-center">KODE</th>
                <th class="text-start">KETERANGAN</th>
                <th class="text-center"></th>
                <th class="text-end">PAGU</th>
                <th class="text-end">REALISASI</th>
                <th class="text-center">%</th>
                <th class="text-end">SISA</th>

            </tr>
        </thead>
        <tbody>
            @php
            $totalpagu = 0;
            $totaldsa  = 0;
            $totalsisa = 0;


            @endphp
            @foreach ($data as $item)
            <tr class="table-danger">
                <th class="text-center">{{$item->KodeWilayah}}</th>
                <th class="text-start" colspan="7">{{$item->NamaWilayah}}</th>
            </tr>
            @php
                $totalpaguProp = 0;
                $totaldsaProp  = 0;
                $totalsisaProp = 0;
            @endphp
            @foreach ($item->Program as $item)
            <tr class="table-danger">
                <th class="text-end"></th>
                <th class="text-start">{{$item->KodeProgram}}</th>
                <th class="text-start">{{$item->NamaProgram}}</th>
                <th class="text-end" colspan="5"></th>
            </tr>
            @foreach ($item->Kegiatan as $Kegiatan)
            <tr class="table-warning">
                <th class="text-end"></th>
                <th class="text-start">{{$Kegiatan->KodeKegiatan}}</th>
                <th class="text-start">{{$Kegiatan->NamaKegiatan}}</th>
                <th class="text-end" colspan="5"></th>
            </tr>
            @foreach ($Kegiatan->Output as $Output)
            <tr class="table-success">
                <th class="text-end"></th>
                <th class="text-start">{{$Output->KodeOutput}}</th>
                <th class="text-start">{{$Output->NamaOutput}}</th>
                <th class="text-end" colspan="5"></th>
            </tr>
            @foreach ($Output->Akun as $Akun)
            <tr>
                <td class="text-center"></td>
                <td class="text-start">{{$Akun->KodeAkun}}</td>
                <td class="text-start">{{$Akun->NamaAkun}}</td>
                <td class="text-center">{{$Akun->KodeSumberDana}}</td>
                <td class="text-end">{{($Akun->Pagu)}}</td>
                <td class="text-end">{{($Akun->Dsa)}}</td>
                <td class="text-center">{{Persen($Akun->Persen)}}%</td>
                <td class="text-end">{{($Akun->Sisa)}}</td>

            </tr>
            @php
                $totalpagu += $Akun->Pagu;
                $totaldsa  += $Akun->Dsa;
                $totalsisa += $Akun->Sisa;
            @endphp
            @php
                $totalpaguProp += $Akun->Pagu;
                $totaldsaProp  += $Akun->Dsa;
                $totalsisaProp += $Akun->Sisa;
            @endphp
            @endforeach
            @endforeach
            @endforeach

            @endforeach
            <tr>
                <th class="text-center"></th>
                <th class="text-start"></th>
                <th class="text-start">JUMLAH PROPINSI</th>
                <th class="text-center"></th>
                <th class="text-end">{{($totalpaguProp)}}</th>
                <th class="text-end">{{($totaldsaProp)}}</th>
                <th class="text-center">{{Persen(divnum($totaldsaProp,$totalpaguProp)*100)}}%</th>
                <th class="text-end">{{($totalsisaProp)}}</th>
            </tr>

            @endforeach
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th></th>
                <th class="text-end"></th>
                <th class="text-start">JUMLAH</th>
                <th class="text-end"></th>
                <th class="text-end">{{($totalpagu)}}</th>
                <th class="text-end">{{($totaldsa)}}</th>
                <th class="text-center">{{Persen(divnum($totaldsa,$totalpagu)*100)}}%</th>
                <th class="text-end">{{($totalsisa)}}</th>
            </tr>
        </tfoot>
    </table>
