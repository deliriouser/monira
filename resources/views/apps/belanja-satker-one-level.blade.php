@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Belanja</li>
<li class="breadcrumb-item">Satker</li>
<li class="breadcrumb-item active">{{$segment}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0">Satker Per {{$segment}}
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
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportSpending',['type'=>'excell','unit'=>'satker','segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Excell</a>
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportSpending',['type'=>'pdf','unit'=>'satker','segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>

                      </div>
                    </div>
                </div>
                        <div class="table-responsive">
                            <table class="table table-sm" id="card">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-start">KODE</th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-end">PAGU AWAL</th>
                                        <th class="text-end">PAGU AKHIR</th>
                                        <th class="text-end">REALISASI</th>
                                        <th class="text-center">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td class="text-center">{{$item['Kode']}}</td>
                                        <td class="text-start">{{$item['Keterangan']}}</td>
                                        <td class="text-end">{{RP($item['PaguAwal'])}}</td>
                                        <td class="text-end">{{RP($item['Pagu'])}}</td>
                                        <td class="text-end">{{RP($item['Realisasi'])}}</td>
                                        <td class="text-center">{{Persen($item['Persen'])}}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-start">JUMLAH</th>
                                        <th class="text-end">{{RP($data->sum('PaguAwal'))}}</th>
                                        <th class="text-end">{{RP($data->sum('Pagu'))}}</th>
                                        <th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
                                        <th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
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
