@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>{{$segment}} Per {{$unit ?? ''}}</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Covid</li>
<li class="breadcrumb-item">{{ucfirst($unit)}}</li>
<li class="breadcrumb-item active">{{$segment}}</li>
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0">{{$segment}} Penanganan Covid-19 Per {{$unit ?? ''}}
                      </h5>

                      <div class="card-header-right-icon">
                        <div class="row">
                            <div class="col-8">
                                <div class="dropdown">
                                <button class="btn dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{nameofmonth(Request::route('month'))}}</button>
                                <div class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownMenuButton" style="">
                                    @for($i=1;$i<=12;$i++)
                                    @if($i<= DATE('n'))
                                    <a class="dropdown-item @if($i==Request::route('month')) bg-danger text-white @endif" href="{{route(Auth::user()->ba.'/covid',['unit'=>Request::route('unit'),'segment'=>Request::route('segment'), 'month'=>$i])}}">{{nameofmonth($i)}}</a>
                                    @endif
                                    @endfor
                                </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportCovid',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Excell</a>
                                        {{-- <a target="_blank" href="{{route(Auth::user()->ba.'/reportCovid',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Pdf</a> --}}
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>

                    </div>
                </div>
				{{-- <div class="card-body"> --}}
                    <div class="table-responsive">



                        <table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr valign="middle">
									<th class="text-center" rowspan="2">NO</th>
									<th class="text-start" rowspan="2">KODE</th>
									<th class="text-start" rowspan="2">KETERANGAN</th>
									<th class="text-center" rowspan="2">DANA</th>
									<th class="text-center" colspan="2">PAGU</th>
									<th class="text-center" rowspan="2"></th>
									<th class="text-center" colspan="2">REALISASI</th>
									<th class="text-center" rowspan="2">TGL / NO SP2D</th>
									<th class="text-center" rowspan="2">%</th>
									<th class="text-center" rowspan="2">SISA</th>
								</tr>
                                <tr>
									<th class="text-center">VOLUME</th>
									<th class="text-center">RUPIAH</th>
									<th class="text-center">VOLUME</th>
									<th class="text-center">RUPIAH</th>
                                </tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAkhir          = 0;
                                    $TotalRealisasi          = 0;
                                    $TotalSisa               = 0;
                                    $PaguAwalSub             = 0;
                                    $PaguAkhirSub            = 0;
                                    $RealisasiSub            = 0;
                                    $SisaSub                 = 0;
                                    $TotalPaguAkhir_kegiatan = 0;
                                    $TotalRealisasi_kegiatan = 0;
                                    $TotalSisa_kegiatan      = 0;

                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-danger">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="11"><b>{{$item->NamaHeader}}</b></td>
                                </tr>

                                @php
                                    $PaguAkhirSub_kegiatan = 0;
                                    $RealisasiSub_kegiatan = 0;
                                    $SisaSub_kegiatan      = 0;

                                @endphp
                            @foreach ($item->Data as $key => $value)
                                <tr class="table-warning">
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center"><b>{{$key}}</b></td>
                                        <td class="text-start" colspan="10"><b>
                                            @if (isset($value->KodeSubHeader))
                                                {{$value->NamaSubHeader}}
                                            @endif
                                        </b></td>
                                </tr>
                                @php
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Sisa      = 0;
                                    $PaguKegiatan_satker     = 0;
                                    $BelanjaKegiatan_satker  = 0;
                                    $SisaKegiatan_satker     = 0;

                                @endphp

                                    @foreach ($value->SubData as $output => $valoutput)
                                    @foreach ($valoutput->SubDataKegiatan as $kegiatan => $valkegiatan)
                                    @foreach ($valkegiatan->SubDataDana as $dana => $valdana)
                                    @foreach ($valdana->SubDataAkun as $akun => $detil)

                                @php
                                    $PaguAkhir              += $detil->PaguAkhir ?? '0';
                                    $Realisasi              += $detil->Realisasi ?? '0';
                                    $Sisa                   += $detil->Sisa ?? '0';
                                @endphp

                                <tr>
									<th class="text-center"></th>
									<th class="text-center">{{$valkegiatan->KodeKegiatan}}.{{$valoutput->KodeOutput}}.{{$detil->Kode}}</th>
									<th class="text-start">{{$detil->Keterangan}}</th>
									<th class="text-center">{{$detil->SumberDana}}</th>
                                    <th></th>
									<th class="text-end">{{RP($detil->PaguAkhir)}}</th>
                                    <th></th>
                                    <th></th>
									<th class="text-end">{{RP($detil->Realisasi)}}</th>
                                    <th></th>
									<th class="text-center">{{Persen($detil->Persen)}}%</th>
									<th class="text-end">{{RP($detil->Sisa)}}</th>
								</tr>
                                @php
                                    $PaguKegiatan    = 0;
                                    $BelanjaKegiatan = 0;
                                    $SisaKegiatan    = 0;

                                @endphp
                                @foreach ($detil->SubDataKegiatan as $kegiatan)
                                @if($kegiatan->Uraian!='0')
                                @php
                                $PaguKegiatan          += $kegiatan->PaguKegiatan;
                                $BelanjaKegiatan       += $kegiatan->BelanjaKegiatan;
                                $SisaKegiatan          += $kegiatan->SisaKegiatan;

                                $PaguAkhirSub_kegiatan += $kegiatan->PaguKegiatan;
                                $RealisasiSub_kegiatan += $kegiatan->BelanjaKegiatan;
                                $SisaSub_kegiatan      += $kegiatan->SisaKegiatan;


                                @endphp

                                <tr>
									<td class="text-center"></td>
									<td class="text-end"></td>
									<td class="text-start">
                                        {{$kegiatan->Uraian}}
                                            @if(!empty($kegiatan->Catatan))<br>
                                            <small><i>{{$kegiatan->Catatan}}</i>
                                            </small>
                                            @endif</td>
									<td class="text-center">{{$detil->SumberDana}}</td>
									<td class="text-end"><span class="nowrap">{{RP($kegiatan->VolumePagu)}} {{$kegiatan->SatuanPagu}}</span></td>
									<td class="text-end">{{RP($kegiatan->PaguKegiatan)}}</td>
                                    <td class="text-center"></td>

									<td class="text-end"><span class="nowrap">@if(!empty($kegiatan->VolumeBelanja)){{RP($kegiatan->VolumeBelanja)}} {{$kegiatan->SatuanBelanja}} @endif</span></td>
									<td class="text-end">{{RP($kegiatan->BelanjaKegiatan)}}</td>
									<td class="text-start"><small class="nowrap">{!!nl2br($kegiatan->Tglsp2d)!!}</small></td>

									<td class="text-center">{{Persen($kegiatan->PersenKegiatan)}}%</td>
									<td class="text-end">{{RP($kegiatan->SisaKegiatan)}}</td>
								</tr>
                                @endif
                                @endforeach
                                @php
                                    $selisih_pagu      = $PaguAkhir-$PaguKegiatan;
                                    $selisih_realisasi = $Realisasi-$BelanjaKegiatan;
                                    $total_selisih     = $selisih_pagu+$selisih_realisasi;


                                @endphp
                                <tr class="border-top-primary">
                                    <th class="text-center"></th>
                                    <th></th>
                                    <th class="text-start">SUB JUMLAH</th>
                                    <th class="text-center text-danger">@if($total_selisih!=0) <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Terdapat Perbedaan Data UPT dan SPAN" class="icofont icofont-warning-alt"></i> @endif</th>
                                    <th></th>
                                    <th class="text-end">{{RP($PaguKegiatan)}}</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end">{{RP($BelanjaKegiatan)}}</th>
                                    <th></th>
                                    <th class="text-center">{{Persen(divnum($BelanjaKegiatan,$PaguKegiatan)*100)}}%</th>
                                    <th class="text-end">{{RP($SisaKegiatan)}}</th>
                                </tr>

                                @php
                                    $PaguKegiatan_satker    += $PaguKegiatan;
                                    $BelanjaKegiatan_satker += $BelanjaKegiatan;
                                    $SisaKegiatan_satker    += $SisaKegiatan;
                                @endphp

                                @endforeach

                                @endforeach

                                @endforeach


                                @endforeach

                                <tr class="border-top-primary table-info">
                                    <th class="text-center"></th>
                                    <th></th>
                                    <th class="text-start">JUMLAH SATKER</th>
                                    <th class="text-center text-danger"></th>
                                    <th></th>
                                    <th class="text-end">{{RP($PaguKegiatan_satker)}}</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end">{{RP($BelanjaKegiatan_satker)}}</th>
                                    <th></th>
                                    <th class="text-center">{{Persen(divnum($BelanjaKegiatan_satker,$PaguKegiatan_satker)*100)}}%</th>
                                    <th class="text-end">{{RP($SisaKegiatan_satker)}}</th>
                                </tr>

                                @php
                                    $TotalPaguAkhir          += $PaguAkhir;
                                    $TotalRealisasi          += $Realisasi;
                                    $TotalSisa               += $Sisa;
                                @endphp

                                {{-- <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end">{{RP($PaguAkhir)}}</th>
                                    <th class="text-end">{{RP($Realisasi)}}</th>
                                    <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                                    <th class="text-end">{{RP($Sisa)}}</th>
                                </tr> --}}

                                @php
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                $SisaSub      += $Sisa;
                                @endphp

                                @endforeach

                                <tr class="table-danger">
									<th class="text-center"></th>
									<th class="text-start" colspan="3">JUMLAH PROPINSI {{$item->NamaHeader}}</th>
									<th class="text-start"></th>
                                    <th class="text-end">{{RP($PaguAkhirSub_kegiatan)}}</th>
									<th class="text-start"></th>
                                    <th></th>
                                    <th class="text-end">{{RP($RealisasiSub_kegiatan)}}</th>
                                    <th></th>
                                    <th class="text-center">{{Persen(divnum($RealisasiSub_kegiatan,$PaguAkhirSub_kegiatan)*100)}}%</th>
                                    <th class="text-end">{{RP($SisaSub_kegiatan)}}</th>
                                </tr>

                                @php
                                $PaguAkhirSub             = 0;
                                $RealisasiSub             = 0;
                                $SisaSub                  = 0;
                                $TotalPaguAkhir_kegiatan += $PaguAkhirSub_kegiatan;
                                $TotalRealisasi_kegiatan += $RealisasiSub_kegiatan;
                                $TotalSisa_kegiatan      += $SisaSub_kegiatan;

                                @endphp


                                @endforeach
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start" colspan="3">JUMLAH RAYA UPT</th>
									<th class="text-start"></th>
									<th class="text-end">{{RP($TotalPaguAkhir_kegiatan)}}</th>
									<th class="text-start"></th>
                                    <th></th>
									<th class="text-end">{{RP($TotalRealisasi_kegiatan)}}</th>
                                    <th></th>
                                    <th class="text-center">{{Persen(divnum($TotalRealisasi_kegiatan,$TotalPaguAkhir_kegiatan)*100)}}%</th>
									<th class="text-end">{{RP($TotalSisa_kegiatan)}}</th>
								</tr>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start" colspan="3">JUMLAH RAYA SPAN</th>
									<th class="text-start"></th>
									<th class="text-end">{{RP($TotalPaguAkhir)}}</th>
									<th class="text-start"></th>
                                    <th></th>
                                    <th class="text-end">{{RP($TotalRealisasi)}}</th>
                                    <th></th>
                                    <th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
									<th class="text-end">{{RP($TotalSisa)}}</th>
								</tr>

						</table>
					</div>
				{{-- </div> --}}
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
