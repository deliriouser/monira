@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Rangking Daya Serap Propinsi Satker</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Rangking</li>
<li class="breadcrumb-item active">Propinsi Satker</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Rangking Daya Serap Propinsi Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'satker','segment'=>'ranking'])}}">Excell</a>
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'satker','segment'=>'ranking'])}}">PDF</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
				{{-- <div class="card-body"> --}}
                    <div class="table-responsive">
						<table class="table table-sm">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">NAMA SATKER</th>
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
									<td class="text-center">{{$noSatker=$noSatker+1}}</td>
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
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end">{{RP($TotalPaguAwal)}}</th>
									<th class="text-end">{{RP($TotalPaguAkhir)}}</th>
									<th class="text-end">{{RP($TotalRealisasi)}}</th>
									<th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
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
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
@endsection
