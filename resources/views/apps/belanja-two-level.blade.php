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
<li class="breadcrumb-item">Belanja</li>
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
                      <h5 class="m-0">{{$segment}} Per {{$unit ?? ''}}
                      </h5>

                      <div class="card-header-right-icon">
                        <div class="row">
                            <div class="col-8">
                                <div class="dropdown">
                                <button class="btn dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{nameofmonth(Request::route('month'))}}</button>
                                <div class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownMenuButton" style="">
                                    @for($i=1;$i<=12;$i++)
                                    @if($i<= DATE('n'))
                                    <a class="dropdown-item @if($i==Request::route('month')) bg-danger text-white @endif" href="{{route(Auth::user()->ba.'/belanja',['unit'=>Request::route('unit'),'segment'=>Request::route('segment'), 'month'=>$i])}}">{{nameofmonth($i)}}</a>
                                    @endif
                                    @endfor
                                </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportSpending',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Excell</a>
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportSpending',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>

                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
								</tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAwal=0;
                                    $TotalPaguAkhir=0;
                                    $TotalRealisasi=0;
                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-danger">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="7"><b>{{$item->NamaHeader}}</b></td>
                                </tr>
                                @php
                                    $noSatker=0;
                                    $PaguAwal=0;
                                    $PaguAkhir=0;
                                    $Realisasi=0;
                                @endphp
                                @foreach($item->Data as $satker)
                                @php
                                    $PaguAwal+=$satker->PaguAwal;
                                    $PaguAkhir+=$satker->PaguAkhir;
                                    $Realisasi+=$satker->Realisasi;
                                @endphp
                                <tr>
									<td class="text-center"></td>
									<td class="text-center">{{$satker->Kode}}</td>
									<td class="text-start">{{$satker->Keterangan}}</td>
									<td class="text-end">{{RP($satker->PaguAwal)}}</td>
									<td class="text-end">{{RP($satker->PaguAkhir)}}</td>
									<td class="text-end">{{RP($satker->Realisasi)}}</td>
									<td class="text-center">{{Persen($satker->Persen)}}%</td>
								</tr>
                                @endforeach

                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end">{{RP($PaguAwal)}}</th>
                                    <th class="text-end">{{RP($PaguAkhir)}}</th>
                                    <th class="text-end">{{RP($Realisasi)}}</th>
                                    <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                                </tr>

                                @php
                                    $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                @endphp

                                @endforeach
							</tbody>
                            <tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end">{{RP($TotalPaguAwal)}}</th>
									<th class="text-end">{{RP($TotalPaguAkhir)}}</th>
									<th class="text-end">{{RP($TotalRealisasi)}}</th>
									<th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
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
@endsection
