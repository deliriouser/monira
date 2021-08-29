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
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/report',['type'=>'excell','unit'=>'eselon1','segment'=>'ranking'])}}">Excell</a>
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/report',['type'=>'pdf','unit'=>'eselon1','segment'=>'ranking'])}}">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm table-striped">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">NAMA SATKER</th>
									<th class="text-start">PROPINSI</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
								</tr>
							</thead>
							<tbody>
                                @foreach ($data as $item)
								<tr @if($item->KodeSatker==Auth:: user()->kdsatker) class="bg-danger" @endif>
									<td class="text-center">{{$loop->iteration}}
                                        @if($item->KodeSatker==Auth:: user()->kdsatker)
                                            <a name="{{Auth:: user()->kdsatker}}"></a>
                                         @endif
                                    </td>
									<td class="text-center">{{$item->KodeSatker}}</td>
									<td class="text-start">{{$item->NamaSatuanKerja}}</td>
									<td class="text-start">{{$item->WilayahName}}</td>
									<td class="text-end">{{RP($item->PaguAwal)}}</td>
									<td class="text-end">{{RP($item->Pagu)}}</td>
									<td class="text-end">{{RP($item->Realisasi)}}</td>
									<td class="text-center">{{Persen($item->Persen)}}%</td>
								</tr>
                                @endforeach
							</tbody>
							<tfoot class="table-primary">
								<tr>
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
									<th class="text-end">{{RP($data->sum('PaguAwal'))}}</th>
									<th class="text-end">{{RP($data->sum('Pagu'))}}</th>
									<th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
									<th class="text-end">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
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
