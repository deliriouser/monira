



<table class="table table-sm" id="card">
            <tr>
                <td colspan="7"><b>REKAPITULASI</b></td>
            </tr>
            <tr>
                <td colspan="7"><b>KEGIATAN PADAT KARYA</b></td>
            </tr>
            <tr>
                <td colspan="7"><b>TAHUN ANGGARAN {{$year}}</b></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <thead class="bg-primary text-white">
            <tr>
                <th class="text-center">KODE</th>
                <th class="text-center"></th>
                <th class="text-start">KETERANGAN</th>
                <th class="text-end">PAGU</th>
                <th class="text-end">REALISASI</th>
                <th class="text-center">%</th>
                <th class="text-end">SISA</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalpagu =0;
            $totaldsa =0;
            $totalsisa =0;
            @endphp
            @foreach ($data as $item)
            <tr class="table-danger">
                <th class="text-center">{{$item->KodeProgram}}</th>
                <th class="text-end"></th>
                <th class="text-start">{{$item->NamaProgram}}</th>
                <th class="text-end" colspan="4"></th>
            </tr>
            @foreach ($item->Kegiatan as $Kegiatan)
            <tr class="table-warning">
                <th class="text-center">{{$Kegiatan->KodeKegiatan}}</th>
                <th class="text-end"></th>
                <th class="text-start">{{$Kegiatan->NamaKegiatan}}</th>
                <th class="text-end" colspan="4"></th>
            </tr>
            @foreach ($Kegiatan->Output as $Output)
            <tr class="table-success">
                <th class="text-center">{{$Output->KodeOutput}}</th>
                <th class="text-end"></th>
                <th class="text-start">{{$Output->NamaOutput}}</th>
                <th class="text-end" colspan="4"></th>
            </tr>
            @foreach ($Output->Akun as $Akun)
            <tr>
                <td class="text-center">{{$Akun->KodeAkun}}</td>
                <td class="text-center">{{$Akun->KodeSumberDana}}</td>
                <td class="text-start">{{$Akun->NamaAkun}}</td>
                <td class="text-end">{{RP($Akun->Pagu)}}</td>
                <td class="text-end">{{RP($Akun->Dsa)}}</td>
                <td class="text-center">{{Persen($Akun->Persen)}}%</td>
                <td class="text-end">{{RP($Akun->Sisa)}}</td>
            </tr>
            @php
                $totalpagu += $Akun->Pagu;
                $totaldsa  += $Akun->Dsa;
                $totalsisa += $Akun->Sisa;
            @endphp
            @endforeach
            @endforeach
            @endforeach
            @endforeach
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th></th>
                <th class="text-end"></th>
                <th class="text-start">JUMLAH</th>
                <th class="text-end">{{RP($totalpagu)}}</th>
                <th class="text-end">{{RP($totaldsa)}}</th>
                <th class="text-center">{{Persen(divnum($totaldsa,$totalpagu)*100)}}%</th>
                <th class="text-end">{{RP($totalsisa)}}</th>
            </tr>
        </tfoot>
    </table>

