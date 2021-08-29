<table class="table table-sm" id="card">
    <tr>
        <td colspan="14"><b>REKAPITULASI</b></td>
    </tr>
    <tr>
        <td colspan="14"><b>KEGIATAN PADAT KARYA</b></td>
    </tr>
    <tr>
        <td colspan="14"><b>TAHUN ANGGARAN {{$year}}</b></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <thead class="bg-primary text-white">
        <tr class="bg-primary">
            <th class="text-center" valign="middle" rowspan="2">KODE</th>
            <th class="text-center" valign="middle" rowspan="2">PROPINSI KAB / KOTA KEC DESA / KEL</th>
            <th class="text-start" valign="middle" rowspan="2">SATKER</th>
            <th class="text-center" colspan="5">TARGET</th>
            <th class="text-start" valign="middle" rowspan="2"></th>
            <th class="text-center" colspan="5">REALISASI</th>
        </tr>
        <tr>
            <th class="col-1 text-center">TOTAL PAGU</th>
            <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
            <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
            <th class="col-1 text-center">TOTAL REALISASI</th>
            <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
            <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
            <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
        </tr>
    </thead>
    <tbody>
        @php
        $SumTotTarget_TotalBiayaLain  = 0;
        $SumTotTarget_TotalBiayaUpah  = 0;
        $SumTotTarget_JumlahOrang     = 0;
        $SumTotTarget_JumlahOrangHari = 0;
        $SumTotTarget_TotalPagu       = 0;
        $SumTotDaser_TotalBiayaLain   = 0;
        $SumTotDaser_TotalBiayaUpah   = 0;
        $SumTotDaser_JumlahOrang      = 0;
        $SumTotDaser_JumlahOrangHari  = 0;
        $SumTotDaser_TotalPagu        = 0;
        @endphp

        @foreach ($data as $wilayah)
        <tr class="table-danger">
            <th class="text-center">{{$wilayah->KodeWilayah}}</th>
            <th class="text-start" colspan="13">PROP. {{$wilayah->NamaWilayah}}</th>
        </tr>

        @php
            $SumPropTarget_TotalBiayaLain  = 0;
            $SumPropTarget_TotalBiayaUpah  = 0;
            $SumPropTarget_JumlahOrang     = 0;
            $SumPropTarget_JumlahOrangHari = 0;
            $SumPropTarget_TotalPagu       = 0;
            $SumPropDaser_TotalBiayaLain   = 0;
            $SumPropDaser_TotalBiayaUpah   = 0;
            $SumPropDaser_JumlahOrang      = 0;
            $SumPropDaser_JumlahOrangHari  = 0;
            $SumPropDaser_TotalPagu        = 0;
        @endphp

        @foreach ($wilayah->Kabupaten as $kabupaten)
        <tr class="table-warning">
            <th class="text-center">.</th>
            <th class="text-start" colspan="13" style="padding-left:20px;">{{$kabupaten->Kabupaten}}</th>
        </tr>
        @php
            $SumKabTarget_TotalBiayaLain  = 0;
            $SumKabTarget_TotalBiayaUpah  = 0;
            $SumKabTarget_JumlahOrang     = 0;
            $SumKabTarget_JumlahOrangHari = 0;
            $SumKabTarget_TotalPagu       = 0;
            $SumKabDaser_TotalBiayaLain   = 0;
            $SumKabDaser_TotalBiayaUpah   = 0;
            $SumKabDaser_JumlahOrang      = 0;
            $SumKabDaser_JumlahOrangHari  = 0;
            $SumKabDaser_TotalPagu        = 0;
        @endphp

        @foreach ($kabupaten->Kecamatan as $kecamatan)
        <tr class="table-success">
            <th class="text-center">..</th>
            <th class="text-start" colspan="13" style="padding-left:40px;">KEC. {{$kecamatan->Kecamatan}}</th>
        </tr>
        @php
            $SumKecTarget_TotalBiayaLain  = 0;
            $SumKecTarget_TotalBiayaUpah  = 0;
            $SumKecTarget_JumlahOrang     = 0;
            $SumKecTarget_JumlahOrangHari = 0;
            $SumKecTarget_TotalPagu       = 0;
            $SumKecDaser_TotalBiayaLain   = 0;
            $SumKecDaser_TotalBiayaUpah   = 0;
            $SumKecDaser_JumlahOrang      = 0;
            $SumKecDaser_JumlahOrangHari  = 0;
            $SumKecDaser_TotalPagu        = 0;
        @endphp

        @foreach ($kecamatan->Desa as $item)
        <tr>
            <td class="text-center">...</td>
            <td class="text-start" style="padding-left:60px;">DES. {{$item->Desa}}</td>
            <td class="text-start">{{$item->NamaSatker}}</td>
            <td class="text-end">{{($item->Target_TotalPagu)}}</td>
            <td class="text-end">{{($item->Target_TotalBiayaLain)}}</td>
            <td class="text-end">{{($item->Target_TotalBiayaUpah)}}</td>
            <td class="text-center">{{($item->Target_JumlahOrang)}}</td>
            <td class="text-center">{{($item->Target_JumlahOrangHari)}}</td>
            <td class="text-end"></td>
            <td class="text-end">{{($item->Daser_TotalPagu)}}</td>
            <td class="text-end">{{($item->Daser_TotalBiayaLain)}}</td>
            <td class="text-end">{{($item->Daser_TotalBiayaUpah)}}</td>
            <td class="text-center">{{($item->Daser_JumlahOrang)}}</td>
            <td class="text-center">{{($item->Daser_JumlahOrangHari)}}</td>
        </tr>
        @php
            $SumKecTarget_TotalPagu       += $item->Target_TotalPagu;
            $SumKecTarget_TotalBiayaLain  += $item->Target_TotalBiayaLain;
            $SumKecTarget_TotalBiayaUpah  += $item->Target_TotalBiayaUpah;
            $SumKecTarget_JumlahOrang     += $item->Target_JumlahOrang;
            $SumKecTarget_JumlahOrangHari += $item->Target_JumlahOrangHari;
            $SumKecDaser_TotalPagu        += $item->Daser_TotalPagu;
            $SumKecDaser_TotalBiayaLain   += $item->Daser_TotalBiayaLain;
            $SumKecDaser_TotalBiayaUpah   += $item->Daser_TotalBiayaUpah;
            $SumKecDaser_JumlahOrang      += $item->Daser_JumlahOrang;
            $SumKecDaser_JumlahOrangHari  += $item->Daser_JumlahOrangHari;
        @endphp
        @endforeach
        <tr class="table-success">
            <th class="text-center">...</th>
            <th colspan="2" class="text-start" style="padding-left:40px;">JUMLAH KEC. {{$kecamatan->Kecamatan}}</th>
            <th class="text-end">{{($SumKecTarget_TotalPagu)}}</th>
            <th class="text-end">{{($SumKecTarget_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumKecTarget_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumKecTarget_JumlahOrang)}}</th>
            <th class="text-center">{{($SumKecTarget_JumlahOrangHari)}}</th>
            <th class="text-end"></th>
            <th class="text-end">{{($SumKecDaser_TotalPagu)}}</th>
            <th class="text-end">{{($SumKecDaser_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumKecDaser_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumKecDaser_JumlahOrang)}}</th>
            <th class="text-center">{{($SumKecDaser_JumlahOrangHari)}}</th>
        </tr>
        @php
        $SumKabTarget_TotalPagu       += $SumKecTarget_TotalPagu;
        $SumKabTarget_TotalBiayaLain  += $SumKecTarget_TotalBiayaLain;
        $SumKabTarget_TotalBiayaUpah  += $SumKecTarget_TotalBiayaUpah;
        $SumKabTarget_JumlahOrang     += $SumKecTarget_JumlahOrang;
        $SumKabTarget_JumlahOrangHari += $SumKecTarget_JumlahOrangHari;
        $SumKabDaser_TotalPagu        += $SumKecDaser_TotalPagu;
        $SumKabDaser_TotalBiayaLain   += $SumKecDaser_TotalBiayaLain;
        $SumKabDaser_TotalBiayaUpah   += $SumKecDaser_TotalBiayaUpah;
        $SumKabDaser_JumlahOrang      += $SumKecDaser_JumlahOrang;
        $SumKabDaser_JumlahOrangHari  += $SumKecDaser_JumlahOrangHari;
        @endphp

        @endforeach
        <tr class="table-warning">
            <th class="text-center">..</th>
            <th colspan="2" class="text-start" style="padding-left:20px;">JUMLAH {{$kabupaten->Kabupaten}}</th>
            <th class="text-end">{{($SumKabTarget_TotalPagu)}}</th>
            <th class="text-end">{{($SumKabTarget_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumKabTarget_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumKabTarget_JumlahOrang)}}</th>
            <th class="text-center">{{($SumKabTarget_JumlahOrangHari)}}</th>
            <th class="text-end"></th>
            <th class="text-end">{{($SumKabDaser_TotalPagu)}}</th>
            <th class="text-end">{{($SumKabDaser_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumKabDaser_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumKabDaser_JumlahOrang)}}</th>
            <th class="text-center">{{($SumKabDaser_JumlahOrangHari)}}</th>
        </tr>
        @php
        $SumPropTarget_TotalPagu       += $SumKabTarget_TotalPagu;
        $SumPropTarget_TotalBiayaLain  += $SumKabTarget_TotalBiayaLain;
        $SumPropTarget_TotalBiayaUpah  += $SumKabTarget_TotalBiayaUpah;
        $SumPropTarget_JumlahOrang     += $SumKabTarget_JumlahOrang;
        $SumPropTarget_JumlahOrangHari += $SumKabTarget_JumlahOrangHari;
        $SumPropDaser_TotalPagu        += $SumKabDaser_TotalPagu;
        $SumPropDaser_TotalBiayaLain   += $SumKabDaser_TotalBiayaLain;
        $SumPropDaser_TotalBiayaUpah   += $SumKabDaser_TotalBiayaUpah;
        $SumPropDaser_JumlahOrang      += $SumKabDaser_JumlahOrang;
        $SumPropDaser_JumlahOrangHari  += $SumKabDaser_JumlahOrangHari;
        @endphp


        @endforeach
        <tr class="table-danger">
            <th class="text-center"></th>
            <th colspan="2" class="text-start">TOTAL {{$wilayah->NamaWilayah}}</th>
            <th class="text-end">{{($SumPropTarget_TotalPagu)}}</th>
            <th class="text-end">{{($SumPropTarget_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumPropTarget_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumPropTarget_JumlahOrang)}}</th>
            <th class="text-center">{{($SumPropTarget_JumlahOrangHari)}}</th>
            <th class="text-end"></th>
            <th class="text-end">{{($SumPropDaser_TotalPagu)}}</th>
            <th class="text-end">{{($SumPropDaser_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumPropDaser_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumPropDaser_JumlahOrang)}}</th>
            <th class="text-center">{{($SumPropDaser_JumlahOrangHari)}}</th>
        </tr>
        @php
        $SumTotTarget_TotalPagu       += $SumPropTarget_TotalPagu;
        $SumTotTarget_TotalBiayaLain  += $SumPropTarget_TotalBiayaLain;
        $SumTotTarget_TotalBiayaUpah  += $SumPropTarget_TotalBiayaUpah;
        $SumTotTarget_JumlahOrang     += $SumPropTarget_JumlahOrang;
        $SumTotTarget_JumlahOrangHari += $SumPropTarget_JumlahOrangHari;
        $SumTotDaser_TotalPagu        += $SumPropDaser_TotalPagu;
        $SumTotDaser_TotalBiayaLain   += $SumPropDaser_TotalBiayaLain;
        $SumTotDaser_TotalBiayaUpah   += $SumPropDaser_TotalBiayaUpah;
        $SumTotDaser_JumlahOrang      += $SumPropDaser_JumlahOrang;
        $SumTotDaser_JumlahOrangHari  += $SumPropDaser_JumlahOrangHari;
        @endphp
        @endforeach
        </tbody>
        <tfoot>
        <tr class="table-primary">
            <th class="text-center"></th>
            <th colspan="2" class="text-start" style="padding-left:0px;">JUMLAH RAYA</th>
            <th class="text-end">{{($SumTotTarget_TotalPagu)}}</th>
            <th class="text-end">{{($SumTotTarget_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumTotTarget_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumTotTarget_JumlahOrang)}}</th>
            <th class="text-center">{{($SumTotTarget_JumlahOrangHari)}}</th>
            <th class="text-end"></th>
            <th class="text-end">{{($SumTotDaser_TotalPagu)}}</th>
            <th class="text-end">{{($SumTotDaser_TotalBiayaLain)}}</th>
            <th class="text-end">{{($SumTotDaser_TotalBiayaUpah)}}</th>
            <th class="text-center">{{($SumTotDaser_JumlahOrang)}}</th>
            <th class="text-center">{{($SumTotDaser_JumlahOrangHari)}}</th>
        </tr>
        </tfoot>
</table>
