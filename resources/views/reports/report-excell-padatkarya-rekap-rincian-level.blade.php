
<table class="table table-sm" id="card" data-show-columns="true" width="100%;">
    <tr>
        <td colspan="30"><b>REKAPITULASI</b></td>
    </tr>
    <tr>
        <td colspan="30"><b>KEGIATAN PADAT KARYA</b></td>
    </tr>
    <tr>
        <td colspan="30"><b>TAHUN ANGGARAN {{$year}}</b></td>
    </tr>
    <tr>
        <td></td>
    </tr>


        <thead class="bg-primary text-white">
            <tr class="bg-primary text-white">
                <th class="text-center" valign="middle" rowspan="2">NO</th>
                <th class="text-start" valign="middle" rowspan="2">KODE</th>
                <th class="text-center" valign="middle" rowspan="2">KETERANGAN</th>
                <th class="text-center" valign="middle" colspan="3">LOKASI</th>
                <th class="text-center" valign="middle" rowspan="2">AKUN</th>
                <th class="text-center" valign="middle" rowspan="2">KEGIATAN</th>
                <th class="text-center" colspan="9">TARGET</th>
                <th class="text-center" valign="middle" rowspan="2"></th>
                <th class="text-center" colspan="14">REALISASI</th>
            </tr>
            <tr>
                <th class="col-1 text-center">KABUPATEN</th>
                <th class="col-1 text-center">KECAMATAN</th>
                <th class="col-1 text-center">DESA / KELURAHAN</th>
                <th class="col-1 text-center">TOTAL PAGU</th>
                <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                <th class="col-1 text-center">% BIAYA UPAH</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                <th class="col-1 text-center">JUMLAH HARI</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                <th class="col-1 text-center">JADWAL KEGIATAN</th>
                <th class="col-1 text-center">MEKANIME KEGIATAN</th>
                <th class="col-1 text-center">TGL SPPD</th>
                <th class="col-1 text-center">NO SPPD</th>
                <th class="col-1 text-center">TOTAL REALISASI</th>
                <th class="col-1 text-center">REALISASI KEGIATAN PENDUKUNG</th>
                <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                <th class="col-1 text-center">% BIAYA UPAH</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                <th class="col-1 text-center">JUMLAH HARI</th>
                <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                <th class="col-1 text-center">REALISASI KEGIATAN</th>
                <th class="col-1 text-center">MEKANISME KEGIATAN</th>
                <th class="col-1 text-center">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
            $SumPagu_TotalPagu       = 0;
            $SumPagu_TotalBiayaLain  = 0;
            $SumPagu_TotalBiayaUpah  = 0;
            $SumPagu_JumlahOrang     = 0;
            $SumPagu_JumlahHari      = 0;
            $SumPagu_JumlahOrangHari = 0;
            $SumDsa_TotalPagu        = 0;
            $SumDsa_TotalBiayaLain   = 0;
            $SumDsa_TotalBiayaUpah   = 0;
            $SumDsa_JumlahOrang      = 0;
            $SumDsa_JumlahHari       = 0;
            $SumDsa_JumlahOrangHari  = 0;

        @endphp

            @foreach ($data as $propinsi)
            <tr class="table-danger">
                <th>{{$propinsi->KodeWilayah}}</th>
                <th colspan="29">{{$propinsi->WilayahName}}</th>
            </tr>
            @php
                $PropPagu_TotalPagu       = 0;
                $PropPagu_TotalBiayaLain  = 0;
                $PropPagu_TotalBiayaUpah  = 0;
                $PropPagu_JumlahOrang     = 0;
                $PropPagu_JumlahHari      = 0;
                $PropPagu_JumlahOrangHari = 0;
                $PropDsa_TotalPagu        = 0;
                $PropDsa_TotalBiayaLain   = 0;
                $PropDsa_TotalBiayaUpah   = 0;
                $PropDsa_JumlahOrang      = 0;
                $PropDsa_JumlahHari       = 0;
                $PropDsa_JumlahOrangHari  = 0;

            @endphp
            @foreach ($propinsi->satker as $satker)
            <tr class="@if(!empty($satker->realisasipadatkarya)) table-success @else table-warning  @endif">
                <th>{{$loop->iteration}}</th>
                <th>{{$satker->KodeSatker}}</th>
                <th colspan="6">{{$satker->NamaSatuanKerja}}</th>
                <th class="text-end">{{($satker->pagupadatkarya->TotalPagu ?? '0')}}</th>
                <th class="text-end">{{($satker->pagupadatkarya->TotalBiayaLain ?? '0')}}</th>
                <th class="text-end">{{($satker->pagupadatkarya->TotalBiayaUpah ?? '0')}}</th>
                <th class="text-end">{{Persen(divnum($satker->pagupadatkarya->TotalBiayaUpah ?? '0',$satker->pagupadatkarya->TotalPagu ?? '0')*100)}}%</th>
                <th class="text-end">{{($satker->pagupadatkarya->JumlahOrang ?? '0')}}</th>
                <th class="text-end">{{($satker->pagupadatkarya->JumlahHari ?? '0')}}</th>
                <th class="text-end">{{($satker->pagupadatkarya->JumlahOrangHari ?? '0')}}</th>
                <th class="text-end"><span class="nowrap">{{($satker->pagupadatkarya->Jadwal ?? '')}}</span></th>
                <th class="text-end"><span class="nowrap">{{($satker->pagupadatkarya->Mekanisme ?? '')}}</span></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-end">{{($satker->realisasipadatkarya->TotalPagu ?? '0')}}</th>
                <th class="text-end">{{($satker->realisasipadatkarya->TotalBiayaLain ?? '0')}}</th>
                <th class="text-end">{{($satker->realisasipadatkarya->TotalBiayaUpah ?? '0')}}</th>
                <th class="text-end">{{Persen(divnum($satker->realisasipadatkarya->TotalBiayaUpah ?? '0',$satker->realisasipadatkarya->TotalPagu ?? '0')*100)}}%</th>
                <th class="text-end">{{($satker->realisasipadatkarya->JumlahOrang ?? '0')}}</th>
                <th class="text-end">{{($satker->realisasipadatkarya->JumlahHari ?? '0')}}</th>
                <th class="text-end">{{($satker->realisasipadatkarya->JumlahOrangHari ?? '0')}}</th>
                <th class="text-end">{{($satker->realisasipadatkarya->Jadwal ?? '')}}</th>
                <th class="text-end"><span class="nowrap">{{($satker->realisasipadatkarya->Mekanisme ?? '')}}</span></th>
                <th class="text-end"><span class="nowrap">{{($satker->realisasipadatkarya->Keterangan ?? '')}}</span></th>
            </tr>
            @if(count($satker->datapadatkarya)>0)
            @foreach ($satker->datapadatkarya as $item)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="nowrap">{{$item->Kabupaten}}</span></td>
                <td><span class="nowrap">KEC. {{strtoupper($item->Kecamatan)}}</span></td>
                <td><span class="nowrap">DES. {{strtoupper($item->Desa)}}</span></td>
            </tr>
            @foreach ($item->akun as $akun)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$akun->Akun}}</td>
                <td>{{$akun->Uraian}}</td>
                <td>{{$akun->Amount}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    @foreach ($item->akun as $items)
                    @if(isset($items->realisasi_akun->sppd))
                    @foreach ($items->realisasi_akun->sppd as $itemsppd)
                    {{\Carbon\Carbon::parse($itemsppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')}}<br>
                    @endforeach
                    @endif
                    @endforeach
                </td>
                <td>@foreach ($item->akun as $items)
                    @if(isset($items->realisasi_akun->sppd))
                    @foreach ($items->realisasi_akun->sppd as $itemsppd)
                        {{($itemsppd->nosppd ?? '0')}}<br>
                    @endforeach
                    @endif
                    @endforeach
                </td>
                <td>{{($akun->realisasi_akun->TotalPagu ?? '0')}}</td>
                <td>{{($akun->realisasi_akun->TotalBiayaLain ?? '0')}}</td>
                <td>{{($akun->realisasi_akun->TotalBiayaUpah ?? '0')}}</td>
                <td>{{Persen($akun->realisasi_akun->PersenBiayaUpah ?? '0')}}%</td>
                <td>{{($akun->realisasi_akun->JumlahOrang ?? '0')}}</td>
                <td>{{($akun->realisasi_akun->JumlahHari ?? '0')}}</td>
                <td>{{($akun->realisasi_akun->JumlahOrangHari ?? '0')}}</td>
                <td>{{($akun->realisasi_akun->Jadwal ?? '')}}</td>
                <td>{{($akun->realisasi_akun->Mekanisme ?? '')}}</td>
                <td>{{($akun->realisasi_akun->Keterangan ?? '')}}</td>

            </tr>
            @endforeach
            {{-- <tr>
                <td>
                    @foreach ($item->akun as $akun)
                       <br>
                    @endforeach
                </td>
                <td>
                    @foreach ($item->akun as $akun)
                    <span class="nowrap">{{$akun->Uraian}}</span><br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $akun)
                        {{($akun->Amount)}}<br>
                    @endforeach
                </td>
                <td colspan="9">

                </td>
                <td class="text-start">

                </td>
                <td class="text-start">

                </td>

                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{($items->realisasi_akun->TotalPagu ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{($items->realisasi_akun->TotalBiayaLain ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{($items->realisasi_akun->TotalBiayaUpah ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{Persen($items->realisasi_akun->PersenBiayaUpah ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{($items->realisasi_akun->JumlahOrang ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{($items->realisasi_akun->JumlahHari ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                        {{($items->realisasi_akun->JumlahOrangHari ?? '0')}}<br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                    <span class="nowrap">{{($items->realisasi_akun->Jadwal ?? '')}}</span><br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                    <span class="nowrap">{{($items->realisasi_akun->Mekanisme ?? '')}}</span><br>
                    @endforeach
                </td>
                <td class="text-end">
                    @foreach ($item->akun as $items)
                    <span class="nowrap">{{($items->realisasi_akun->Keterangan ?? '')}}</span><br>
                    @endforeach
                </td>



            </tr> --}}
            @endforeach
            @php
            $PropPagu_TotalPagu       += $satker->pagupadatkarya->TotalPagu ?? '0';
            $PropPagu_TotalBiayaLain  += $satker->pagupadatkarya->TotalBiayaLain ?? '0';
            $PropPagu_TotalBiayaUpah  += $satker->pagupadatkarya->TotalBiayaUpah  ?? '0';
            $PropPagu_JumlahOrang     += $satker->pagupadatkarya->JumlahOrang ?? '0';
            $PropPagu_JumlahHari      += $satker->pagupadatkarya->JumlahHari ?? '0';
            $PropPagu_JumlahOrangHari += $satker->pagupadatkarya->JumlahOrangHari ?? '0';
            $PropDsa_TotalPagu        += $satker->realisasipadatkarya->TotalPagu ?? '0';
            $PropDsa_TotalBiayaLain   += $satker->realisasipadatkarya->TotalBiayaLain ?? '0';
            $PropDsa_TotalBiayaUpah   += $satker->realisasipadatkarya->TotalBiayaUpah ?? '0';
            $PropDsa_JumlahOrang      += $satker->realisasipadatkarya->JumlahOrang ?? '0';
            $PropDsa_JumlahHari       += $satker->realisasipadatkarya->JumlahHari ?? '0';
            $PropDsa_JumlahOrangHari  += $satker->realisasipadatkarya->JumlahOrangHari ?? '0';

            @endphp

            @endif



            @endforeach
            <tr>
                <th></th>
                <th></th>
                <th colspan="6">JUMLAH PROPINSI</th>
                <th class="text-end">{{($PropPagu_TotalPagu ?? '0')}}</th>
                <th class="text-end">{{($PropPagu_TotalBiayaLain ?? '0')}}</th>
                <th class="text-end">{{($PropPagu_TotalBiayaUpah ?? '0')}}</th>
                <th class="text-end">{{Persen(divnum($PropPagu_TotalBiayaUpah ?? '0',$PropPagu_TotalPagu ?? '0')*100)}}%</th>
                <th class="text-end">{{($PropPagu_JumlahOrang ?? '0')}}</th>
                <th class="text-end">{{($PropPagu_JumlahHari ?? '0')}}</th>
                <th class="text-end">{{($PropPagu_JumlahOrangHari ?? '0')}}</th>
                <th class="text-end"></th>
                <th class="text-end"></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-end">{{($PropDsa_TotalPagu ?? '0')}}</th>
                <th class="text-end">{{($PropDsa_TotalBiayaLain ?? '0')}}</th>
                <th class="text-end">{{($PropDsa_TotalBiayaUpah ?? '0')}}</th>
                <th class="text-end">{{Persen(divnum($PropDsa_TotalBiayaUpah ?? '0',$PropDsa_TotalPagu ?? '0')*100)}}%</th>
                <th class="text-end">{{($PropDsa_JumlahOrang ?? '0')}}</th>
                <th class="text-end">{{($PropDsa_JumlahHari ?? '0')}}</th>
                <th class="text-end">{{($PropDsa_JumlahOrangHari ?? '0')}}</th>
                <th class="text-end"></th>
                <th class="text-end"></th>
                <th></th>

            </tr>
            @php
            $SumPagu_TotalPagu       += $PropPagu_TotalPagu;
            $SumPagu_TotalBiayaLain  += $PropPagu_TotalBiayaLain;
            $SumPagu_TotalBiayaUpah  += $PropPagu_TotalBiayaUpah ;
            $SumPagu_JumlahOrang     += $PropPagu_JumlahOrang;
            $SumPagu_JumlahHari      += $PropPagu_JumlahHari;
            $SumPagu_JumlahOrangHari += $PropPagu_JumlahOrangHari;
            $SumDsa_TotalPagu        += $PropDsa_TotalPagu;
            $SumDsa_TotalBiayaLain   += $PropDsa_TotalBiayaLain;
            $SumDsa_TotalBiayaUpah   += $PropDsa_TotalBiayaUpah;
            $SumDsa_JumlahOrang      += $PropDsa_JumlahOrang;
            $SumDsa_JumlahHari       += $PropDsa_JumlahHari;
            $SumDsa_JumlahOrangHari  += $PropDsa_JumlahOrangHari;

            @endphp

            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-primary">
                <th></th>
                <th></th>
                <th colspan="6">TOTAL</th>
                <th class="text-end">{{($SumPagu_TotalPagu ?? '0')}}</th>
                <th class="text-end">{{($SumPagu_TotalBiayaLain ?? '0')}}</th>
                <th class="text-end">{{($SumPagu_TotalBiayaUpah ?? '0')}}</th>
                <th class="text-end">{{Persen(divnum($SumPagu_TotalBiayaUpah ?? '0',$SumPagu_TotalPagu ?? '0')*100)}}%</th>
                <th class="text-end">{{($SumPagu_JumlahOrang ?? '0')}}</th>
                <th class="text-end">{{($SumPagu_JumlahHari ?? '0')}}</th>
                <th class="text-end">{{($SumPagu_JumlahOrangHari ?? '0')}}</th>
                <th class="text-end">{{($SumPagu_Jadwal ?? '')}}</th>
                <th class="text-end">{{($SumPagu_Mekanisme ?? '')}}</th>
                <th></th>
                <th></th>
                <th></th>

                <th class="text-end">{{($SumDsa_TotalPagu ?? '0')}}</th>
                <th class="text-end">{{($SumDsa_TotalBiayaLain ?? '0')}}</th>
                <th class="text-end">{{($SumDsa_TotalBiayaUpah ?? '0')}}</th>
                <th class="text-end">{{Persen(divnum($SumDsa_TotalBiayaUpah ?? '0',$SumDsa_TotalPagu ?? '0')*100)}}%</th>
                <th class="text-end">{{($SumDsa_JumlahOrang ?? '0')}}</th>
                <th class="text-end">{{($SumDsa_JumlahHari ?? '0')}}</th>
                <th class="text-end">{{($SumDsa_JumlahOrangHari ?? '0')}}</th>
                <th class="text-end">{{($SumDsa_Jadwal ?? '')}}</th>
                <th class="text-end">{{($SumDsa_Mekanisme ?? '')}}</th>
                <th></th>

            </tr>

        </tfoot>
    </table>
