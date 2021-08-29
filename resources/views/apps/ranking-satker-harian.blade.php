@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Rangking Daya Serap Satker</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Rangking</li>
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
                            <h5>Rangking Daya Serap Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Rangking" class="openModal" what="filter" href="#"><i data-feather="grid"></i></a>
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reportHarian',['type'=>'excell','top'=>$top,'bottom'=>$bottom])}}">Excell</a>
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reportHarian',['type'=>'pdf','top'=>$top,'bottom'=>$bottom])}}">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-center">KODE</th>
									<th class="text-center">NAMA SATKER</th>
									<th class="text-center">PAGU</th>
									<th class="text-center">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-center">SISA</th>
									<th class="text-center">PROGNOSA</th>
								</tr>
							</thead>
							<tbody>
                                @foreach ($data as $item)
								<tr class="@if($item->Persen>$top) table-success @elseif($item->Persen>$bottom AND $item->Persen<$top) table-warning @else table-danger @endif">
									<td class="text-center">{{$loop->iteration}}</td>
									<td class="text-center">{{$item->KodeSatker}}</td>
									<td class="text-start">{{$item->NamaSatuanKerja}}</td>
									<td class="text-end">{{RP($item->Pagu)}}</td>
									<td class="text-end">{{RP($item->Realisasi)}}</td>
									<td class="text-center">{{Persen($item->Persen)}}%</td>
									<td class="text-end">{{RP($item->Sisa)}}</td>
									<td class="text-center">
                                        @if ($item->Persen_prognosa>0 and $item->Persen_prognosa<=100)
                                        {{Persen($item->Persen_prognosa)}}%
                                        @elseif($item->Persen_prognosa>100)
                                        {{Persen($item->Persen_satker)}}%
                                        @else
                                        @endif
                                    </td>
								</tr>
                                @endforeach
							</tbody>
							<tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
									<th class="text-end">{{RP($data->sum('Pagu'))}}</th>
									<th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
									<th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
									<th class="text-end">{{RP($data->sum('Sisa'))}}</th>
									<th class="text-center">{{Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)}}%</th>
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
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
@endsection
