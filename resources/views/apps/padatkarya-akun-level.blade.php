@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Padat Karya</li>
<li class="breadcrumb-item">{{ucfirst($unit ?? '')}}</li>
<li class="breadcrumb-item active">{{$segment}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0">{{$segment}} Padat Karya Per {{$unit ?? ''}}
                      </h5>
                      <div class="card-header-right-icon">
                        <div class="row">

                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportPadatKarya',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Excell</a>
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportPadatKarya',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Pdf</a>
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
                                        <th class="text-center">KODE</th>
                                        <th class="text-center"></th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-end">PAGU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalpagu =0;
                                    @endphp
                                    @foreach ($data as $item)
                                    <tr class="table-danger">
                                        <th class="text-center">{{$item->KodeProgram}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-start">{{$item->NamaProgram}}</th>
                                        <th class="text-end"></th>
                                    </tr>
                                    @foreach ($item->Kegiatan as $Kegiatan)
                                    <tr class="table-warning">
                                        <th class="text-center">{{$Kegiatan->KodeKegiatan}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-start">{{$Kegiatan->NamaKegiatan}}</th>
                                        <th class="text-end"></th>
                                    </tr>
                                    @foreach ($Kegiatan->Output as $Output)
                                    <tr class="table-success">
                                        <th class="text-center">{{$Output->KodeOutput}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-start">{{$Output->NamaOutput}}</th>
                                        <th class="text-end"></th>
                                    </tr>
                                    @foreach ($Output->Akun as $Akun)
                                    <tr>
                                        <td class="text-center">{{$Akun->KodeAkun}}</td>
                                        <th class="text-center">{{$Akun->KodeSumberDana}}</th>
                                        <td class="text-start">{{$Akun->NamaAkun}}</td>
                                        <th class="text-end">{{RP($Akun->Pagu)}}</th>
                                    </tr>
                                    @php
                                        $totalpagu +=$Akun->Pagu;
                                    @endphp
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-end"></th>
                                        <th class="text-start">JUMLAH</th>
                                        <th class="text-end">{{RP($totalpagu)}}</th>
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
