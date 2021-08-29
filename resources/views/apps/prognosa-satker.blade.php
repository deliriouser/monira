@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Prognosa Satker</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Prognosa</li>
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
                            <h5>Prognosa Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <a target="_blank" href="{{route(Auth::user()->ba.'/reportPrognosa',['type'=>'pdf','unit'=>'satker','segment'=>'prognosa'])}}">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                            </a>

                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadrpd" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-center">DANA</th>
									<th class="text-center">PAGU</th>
									<th class="text-center">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-center">SISA</th>
									<th class="text-center">Aksi</th>
								</tr>

							</thead>
							<tbody>

                                @php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    $TotalPrognosa  = 0;
                                    $TotalSisa      = 0;
                                    $PaguAwalSub    = 0;
                                    $PaguAkhirSub   = 0;
                                    $RealisasiSub   = 0;
                                    $PrognosaSub    = 0;
                                    $SisaSub        = 0;

                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-danger">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="8"><b>{{strtoupper($item->NamaHeader)}}</b></td>
                                </tr>

                                @foreach ($item->Data as $key => $value)
                                <tr class="table-warning">
                                        <td class="text-center"{{$loop->iteration}}</td>
                                        <td class="text-center"><b>{{$key}}</b></td>
                                        <td class="text-start" colspan="7"><b>
                                            @if (isset($value->KodeSubHeader))
                                                {{strtoupper($value->NamaSubHeader)}}
                                            @endif
                                        </b></td>
                                </tr>
                                @php
                                    $PaguAwal  = 0;
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Prognosa  = 0;
                                    $Sisa      = 0;

                                @endphp

                                @foreach ($value->SubData as $detil)
                                @php
                                    $PaguAwal  += $detil->PaguAwal ?? '0';
                                    $PaguAkhir += $detil->PaguAkhir ?? '0';
                                    $Realisasi += $detil->Realisasi ?? '0';
                                    $Prognosa  += $detil->Prognosa ?? '0';
                                    $Sisa       = $PaguAkhir-$Prognosa;
                                @endphp

                                <tr valign="middle" class="@if($detil->Persen>100) bg-danger @endif">
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
                                    <td class="text-end">
                                        {{RP($detil->PaguAkhir-$detil->Prognosa)}}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                        @if(isset($detil->Prognosa) AND $detil->Prognosa>0)
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Data" dana="{{$detil->NamaDana}}" akun="{{$detil->Kode}}. {{$detil->Keterangan}}" output="{{$key}}. {{$value->NamaSubHeader}}" kegiatan="{{$item->KodeHeader}}. {{$item->NamaHeader}}" pagu="{{$detil->PaguAkhir}}" sisa="{{($detil->PaguAkhir - $detil->Prognosa)}}" id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" href="#" class="open-modal text-success static" style="font-size:20px;" @if($locking==1) action="locking" @else action="updatePrognosa" @endif><i class="fa fa-check-circle"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data" class="text-warning" onclick="confirmation_disabled(event)" href="{{route('satker/prognosa/status',['status'=>'0', 'id'=>$item->KodeHeader.'.'.$key.'.'.$detil->Kode.'.'.$detil->KodeDana,'what'=> 'reset'])}}" style="font-size:20px;"><i class="fa fa-dot-circle-o"></i></a>
                                        </div>

                                        @else
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Input Prognosa" dana="{{$detil->NamaDana}}" akun="{{$detil->Kode}}. {{$detil->Keterangan}}" output="{{$key}}. {{$value->NamaSubHeader}}" kegiatan="{{$item->KodeHeader}}. {{$item->NamaHeader}}" pagu="{{$detil->PaguAkhir}}"  id="{{$item->KodeHeader}}.{{$key}}.{{$detil->Kode}}.{{$detil->KodeDana}}.{{$detil->KodeKewenangan}}.{{$detil->KodeProgram}}" href="#" class="open-modal text-primary static" @if($locking==1) action="locking" @else action="insertPrognosa" @endif style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                        @endif
                                    </td>
								</tr>


                                @endforeach

                                @php
                                    $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                    $TotalPrognosa  += $Prognosa;
                                    $TotalSisa      += $Sisa;

                                @endphp
                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end"></th>
                                    <th class="text-end">{{RP($PaguAkhir)}}</th>
                                    <th class="text-end">{{RP($Prognosa)}}</th>
                                    <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                                    <th class="text-end">{{RP($Sisa)}}</th>
                                    <th></th>
                                </tr>

                                @php
                                $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                $PrognosaSub  += $Prognosa;
                                $SisaSub      += $Sisa;

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
                                    <th class="text-end">{{RP($SisaSub)}}</th>
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
                                    <th class="text-end">{{RP($TotalSisa)}}</th>
                                    <th></th>
								</tr>
                            </tbody>

						</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
