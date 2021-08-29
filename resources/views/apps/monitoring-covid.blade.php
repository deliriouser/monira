@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/tour.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Prognosa Satker</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Monitoring Covid</li>
<li class="breadcrumb-item active">Satker</li>
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Monitoring Penanganan Covid-19</h5>
                        </div>
                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    @foreach ($bulan as $item)
                                    @if($item->id<= DATE('n'))
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reporting',['type'=>'pdf','unit'=>'satker','segment'=>Request::route('segment'), 'month'=>$item->id])}}">{{$item->BulanName}}</a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadSK" id="page-all">
							<thead class="bg-primary">
								<tr valign="middle">
									<th class="text-center" rowspan="2">NO</th>
									<th class="text-start" rowspan="2">KODE</th>
									<th class="text-start" rowspan="2">KETERANGAN</th>
									<th class="text-center" rowspan="2">DANA</th>
									<th class="text-center" colspan="2">PAGU</th>
									<th class="text-center" colspan="2">REALISASI</th>
									<th class="text-center" rowspan="2">%</th>
									<th class="text-center" rowspan="2">Aksi</th>
								</tr>
                                <tr>
									<th class="text-center">VOL</th>
									<th class="text-center">RUPIAH</th>
									<th class="text-center">VOL</th>
									<th class="text-center">RUPIAH</th>
                                </tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    // $TotalPrognosa = 0;
                                    $PaguAwalSub  = 0;
                                    $PaguAkhirSub = 0;
                                    $RealisasiSub = 0;
                                    // $PrognosaSub = 0;
                                    $TotalPaguAkhirCovid = 0;
                                    $TotalRealisasiCovid = 0;

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
                                        <td class="text-start" colspan="8"><b>
                                            @if (isset($value->KodeSubHeader))
                                                {{strtoupper($value->NamaSubHeader)}}
                                            @endif
                                        </b></td>
                                </tr>
                                @php
                                    $PaguAwal     = 0;
                                    $PaguAkhir    = 0;
                                    $Realisasi    = 0;
                                    // $Prognosa    = 0;
                                @endphp
                                @foreach ($value->SubData as $detilsub)
                                @foreach ($detilsub->SubDataDana as $detil)
                                @php
                                    $PaguAwal  += $detil->PaguAwal ?? '0';
                                    $PaguAkhir += $detil->PaguAkhir ?? '0';
                                    $Realisasi += $detil->Realisasi ?? '0';
                                    // $Prognosa  += $detil->Prognosa ?? '0';
                                @endphp
                                <tr valign="middle" class="table-success">
									<th class="text-center">{{$loop->iteration}}</th>
									<th class="text-center">{{$detil->Kode}}</th>
									<th class="text-start">{{($detil->Keterangan)}}</th>
									<th class="text-center">{{($detil->NamaDana)}}</th>
                                    <th></th>
									<th class="text-end">{{RP($detil->PaguAkhir)}}</th>
                                    <th></th>
									<th class="text-end">{{RP($detil->Realisasi)}}</th>
									<th class="text-center">{{Persen($detil->Persen)}}%</th>
                                    <th class="text-center">
                                        <a data-intro="Tambah Uraian Kegiatan" title="Tambah Uraian Kegiatan" dana="{{$detil->NamaDana}}" akun="{{$detil->Kode}}. {{$detil->Keterangan}}" output="{{$key}}. {{$value->NamaSubHeader}}" kegiatan="{{$item->KodeHeader}}. {{$item->NamaHeader}}" pagu="{{$detil->PaguAkhir}}"  id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" href="#" class="open-modal-monitoring text-primary static" action="insertKegiatanCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                    </th>
								</tr>
                                @php
                                    $totalPaguKegiatan    = 0;
                                    $totalBelanjaKegiatan = 0;
                                    $number               = 0;
                                @endphp
                                @foreach ($detil->SubDataKegiatan as $uraian)
                                @if(!empty($uraian->PaguKegiatan))
                                @php
                                    $sisa = $uraian->PaguKegiatan-$uraian->BelanjaKegiatan;
                                @endphp
                                <tr valign="middle">
                                    <td></td>
									<td class="text-center">{{$loop->iteration}}</td>
									<td class="text-start">{{$uraian->Uraian}} @if(!empty($uraian->Catatan))<br><small><i>{{$uraian->Catatan}}</i></small>@endif</td>
                                    <td></td>
									<td class="text-center"><span class="nowrap">{{$uraian->VolumePagu}} {{$uraian->SatuanPagu}}</span></td>
									<td class="text-end">{{RP($uraian->PaguKegiatan)}}</td>
									<td class="text-center"><span class="nowrap">{{$uraian->VolumeBelanja}} {{$uraian->SatuanBelanja}}</span></td>
									<td class="text-end">{{RP($uraian->BelanjaKegiatan)}}</td>
                                    <td class="text-center">{{Persen(divnum($uraian->BelanjaKegiatan,$uraian->PaguKegiatan)*100)}}%</td>
									<td class="text-center">
                                        <div class="btn-group">
                                            <a data-intro="Edit Data Uraian Kegiatan" title="Edit Data" dana="{{$uraian->VolumePagu}} {{$uraian->SatuanPagu}}" id="{{$uraian->Guid}}" output="{{$uraian->SatuanPagu}}" kegiatan="{{$uraian->Uraian}}" pagu="{{$uraian->PaguKegiatan}}"  akun="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" sisa="{{$sisa}}" href="#" class="mr-xl-5 open-modal-monitoring text-primary static" action="updateKegiatanCovid" style="font-size:20px;"><i class="icofont icofont-earth"></i></a>
                                            <a data-intro="Tambah Realisasi Kegiatan" title="Input Realisasi" dana="{{$uraian->VolumePagu}} {{$uraian->SatuanPagu}}" akun="{{$uraian->Guid}}" output="{{$uraian->SatuanPagu}}" kegiatan="{{$uraian->Uraian}}" pagu="{{$uraian->PaguKegiatan}}"  id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" sisa="{{$sisa}}" href="#" class="open-modal-monitoring text-success static" action="insertRealisasiCovid" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                            <a data-intro="Lihat Rincian Realisasi" title="Lihat Rincian Realisasi" href="#" id="{{$uraian->Guid}}"class="mr-xl-5 open-modal-monitoring text-primary static" action="dataRealisasiCovid" style="font-size:20px;"><i class="fa fa-info-circle"></i></a>

                                            <a data-intro="Hapus Kegiatan" title="Hapus Kegiatan" onclick="confirmation_disabled(event)" href="{{route('satker/monitoring/status',['status'=>'0', 'id'=> $uraian->Guid,'what'=>'kegiatan'])}}" class="text-danger static" style="font-size:20px;"><i class="fa fa-times-circle"></i></a>
                                          </div>
                                    </td>
                                </tr>
                                @php
                                    $totalPaguKegiatan += $uraian->PaguKegiatan;
                                    $totalBelanjaKegiatan  += $uraian->BelanjaKegiatan;
                                @endphp
                                @endif
                                @endforeach
                                @php
                                    $TotalPaguAwal       += $PaguAwal;
                                    $TotalPaguAkhirCovid += $totalPaguKegiatan;
                                    $TotalRealisasiCovid += $totalBelanjaKegiatan;
                                    // $TotalPrognosa  += $Prognosa;
                                    $selisih_pagu = $detil->PaguAkhir-$totalPaguKegiatan;
                                    $selisih_realisasi = $detil->Realisasi-$totalBelanjaKegiatan;
                                    $number+=$number+1;
                                @endphp
                                <tr valign="middle" class="border-top-primary @if($selisih_pagu>0 OR $selisih_realisasi>0) bg-danger @else text-white bg-success @endif">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start text-white">JUMLAH</th>
                                    <th class="text-end"></th>
                                    <th class="text-end"></th>
                                    <th class="text-end text-white">{{RP($totalPaguKegiatan)}}</th>
                                    <th class="text-end"></th>
                                    <th class="text-end text-white">{{RP($totalBelanjaKegiatan)}}</th>
                                    <th class="text-center text-white">{{Persen(divnum($totalBelanjaKegiatan,$totalPaguKegiatan)*100)}}%</th>
                                    <th class="text-center">@if($selisih_pagu>0 OR $selisih_realisasi>0) <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  @else
                                        <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                                        @endif</th>
                                </tr>
                                @endforeach
                                @endforeach

                                @php
                                    $TotalPaguAkhir      += $PaguAkhir;
                                    $TotalRealisasi      += $Realisasi;

                                $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                // $PrognosaSub  += $Prognosa;
                                @endphp
                                @endforeach

                                @php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                // $PrognosaSub  = 0;


                                @endphp
                                @endforeach

                                @php
                                    $selisih_pagu_akhir      = $TotalPaguAkhir-$TotalPaguAkhirCovid;
                                    $selisih_realisasi_akhir = $TotalRealisasi-$TotalRealisasiCovid;
                                @endphp
                                 <tfoot>
                                    <tr valign="middle" class="table-warning">
                                        <th class="text-center"></th>
                                        <th class="text-start"></th>
                                        <th class="text-start">SELISIH DATA SPAN DAN UPT</th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($selisih_pagu_akhir)}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($selisih_realisasi_akhir)}}</th>
                                        <th class="text-center text-white"></th>
                                        <th class="text-center">
                                        </th>
                                    </tr>
                                </tfoot>
                                <tfoot class="@if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0) bg-danger @else text-white bg-success @endif">
								<tr valign="middle">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start text-white">JUMLAH RAYA</th>
									<th class="text-end"></th>
									<th class="text-end"></th>
									<th class="text-end text-white">{{RP($TotalPaguAkhirCovid)}}</th>
									<th class="text-end"></th>
									<th class="text-end text-white">{{RP($TotalRealisasiCovid)}}</th>
									<th class="text-center text-white">{{Persen(divnum($TotalRealisasiCovid,$TotalPaguAkhirCovid)*100)}}%</th>
                                    <th class="text-center">
                                        @if($selisih_pagu_akhir>0 OR $selisih_realisasi_akhir>0) <a href="#" class="text-white" style="font-size:20px;"><i class="fa fa-exclamation-circle example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="left" title="Peringatan" data-offset="-20px -20px" data-bs-content="Nilai Total Rupiah Kegiatan Tidak Sama Dengan Alokasi Pagu Pada Akun / Nilai Total Realisasi Kegiatan Tidak Sama Dengan Nilai Realisasi Akun yang sudah tercapai"></i></a>  @else
                                        <a class="text-white" style="font-size:20px;"><i class="fa fa-check-circle"></i></a>
                                        @endif

                                    </th>
								</tr>

                                </tfoot>

						</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')

<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>

<script src="{{asset('assets/js/tour/intro.js')}}"></script>
<script src="{{asset('assets/js/tour/intro-init.js')}}"></script>
@endsection
