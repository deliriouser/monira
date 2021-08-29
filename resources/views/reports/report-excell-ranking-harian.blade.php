
    <table class="table table-sm">
    <tr>
        <td colspan="8"><b>REALISASI BELANJA SATKER BERDASARKAN RANGKING TERBESAR KE TERENDAH</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>DIREKTORAT JENDERAL PERHUBUNGAN LAUT</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>TAHUN ANGGARAN 2021</b></td>
    </tr>
    <tr>
        <td colspan="8"><b>POSISI {{date('d')}} {{strtoupper(nameofmonth(date('n')))}} {{date('Y')}}</b></td>
    </tr>

    <thead class="bg-primary text-white">
        <tr>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center bg-primary"><b>NO</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>KODE</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>NAMA SATKER</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>PAGU</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>REALISASI</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>%</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>SISA</b></th>
            <th style="background-color:#0d6efd;color:#ffffff;" class="text-center"><b>PROGNOSA</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-center">{{$loop->iteration}}</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-center">{{$item->KodeSatker}}</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-start">{{$item->NamaSatuanKerja}}</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-end">{{($item->Pagu)}}</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-end">{{($item->Realisasi)}}</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-center">{{Persen($item->Persen)}}%</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-end">{{($item->Sisa)}}</td>
            <td style="@if($item->Persen>$top) background-color:#d1e7dd; @elseif($item->Persen>$bottom AND $item->Persen<$top) background-color:#fefbec; @else background-color:#f0a6ad; @endif" class="text-center">
                @if ($item->Persen_prognosa>0 and $item->Persen_prognosa<100)
                {{Persen($item->Persen_prognosa)}}%
                @elseif($item->Persen_prognosa>100)
                {{Persen($item->Persen_satker)}}%
                @else
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    <tr>
        <td colspan="8" style="text-align:left;">Keterangan</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Hijau : Realisasi Telah Melampaui {{$top}}%</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Kuning : Realisasi Masih Dibawah {{$top}}%</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Merah : Realisasi Masih Dibawah Target Prognosa Bulan Berjalan {{$bottom}}%</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:left;">Prognosa Kosong : UPT/Satker Belum Kirim SPTJM</td>
    </tr>
</table>

