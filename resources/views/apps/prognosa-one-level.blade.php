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
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item active">{{$segment}}</li>
<li class="breadcrumb-item">{{ucfirst($unit)}}</li>
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

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-start">KODE</th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-end">PAGU</th>
                                        <th class="text-end">REALISASI</th>
                                        <th class="text-center">%</th>
                                        <th class="text-end">PROGNOSA</th>
                                        <th class="text-center">%</th>
                                        <th class="text-end">SISA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td class="text-center">{{$item['Kode']}}</td>
                                        <td class="text-start">{{$item['Keterangan']}}</td>
                                        <td class="text-end">{{RP($item['Pagu'])}}</td>
                                        <td class="text-end">{{RP($item['Realisasi'])}}</td>
                                        <td class="text-center">{{Persen($item['Persen'])}}%</td>
                                        <td class="text-end">{{RP($item['Prognosa'])}}</td>
                                        <td class="text-center">{{Persen($item['PersenPrognosa'])}}%</td>
                                        <td class="text-end">{{RP($item['Pagu']-$item['Prognosa'])}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-start">JUMLAH</th>
                                        <th class="text-end">{{RP($data->sum('Pagu'))}}</th>
                                        <th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
                                        <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
                                        <th class="text-end">{{RP($data->sum('Prognosa'))}}</th>
                                        <th class="text-center">{{Persen(divnum($data->sum('Prognosa'),$data->sum('Pagu'))*100)}}%</th>
                                        <th class="text-end">{{RP($data->sum('Pagu')-$data->sum('Prognosa'))}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        {{-- </div> --}}
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
