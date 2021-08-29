@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Belanja Per {{$segment ?? ''}}</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item">{{ucfirst($segment)}}</li>
<li class="breadcrumb-item active">{{$unit ?? ''}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Prognosa {{$segment}} Per {{$unit ?? ''}}</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reportPrognosa',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment')])}}">Excell</a>
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reportPrognosa',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment')])}}">Pdf</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				{{-- <div class="card-body"> --}}
                    <div class="table-responsive">
						<table class="table table-sm" id="basic-1">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-end">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-end">SISA</th>
								</tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    $TotalPrognosa  = 0;
                                    $TotalSisa      = 0;
                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-danger">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="8"><b>{{$item->NamaHeader}}</b></td>
                                </tr>
                                @php
                                    $noSatker  = 0;
                                    $PaguAwal  = 0;
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Prognosa  = 0;
                                    $Sisa      = 0;
                                @endphp
                                @foreach($item->Data as $satker)
                                @php
                                    $PaguAwal  += $satker->PaguAwal;
                                    $PaguAkhir += $satker->PaguAkhir;
                                    $Realisasi += $satker->Realisasi;
                                    $Prognosa  += $satker->Prognosa;
                                    $Sisa      += $satker->PaguAkhir-$satker->Prognosa;
                                @endphp
                                <tr>
									<td class="text-center"></td>
									<td class="text-center">{{$satker->Kode}}</td>
									<td class="text-start">{{$satker->Keterangan}}</td>
									<td class="text-end">{{RP($satker->PaguAkhir)}}</td>
									<td class="text-end">{{RP($satker->Realisasi)}}</td>
									<td class="text-center">{{Persen($satker->Persen)}}%</td>
									<td class="text-end">{{RP($satker->Prognosa)}}</td>
									<td class="text-center">{{Persen($satker->PersenPrognosa)}}%</td>
									<td class="text-end">{{RP($satker->PaguAkhir-$satker->Prognosa)}}</td>
								</tr>
                                @endforeach

                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end">{{RP($PaguAkhir)}}</th>
                                    <th class="text-end">{{RP($Realisasi)}}</th>
                                    <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                                    <th class="text-end">{{RP($Prognosa)}}</th>
                                    <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                                    <th class="text-end">{{RP($Sisa)}}</th>
                                </tr>

                                @php
                                    $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                    $TotalPrognosa  += $Prognosa;
                                    $TotalSisa           += $Sisa;
                                @endphp

                                @endforeach
							</tbody>
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end">{{RP($TotalPaguAkhir)}}</th>
									<th class="text-end">{{RP($TotalRealisasi)}}</th>
									<th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
									<th class="text-end">{{RP($TotalPrognosa)}}</th>
									<th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
									<th class="text-end">{{RP($TotalSisa)}}</th>
								</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
