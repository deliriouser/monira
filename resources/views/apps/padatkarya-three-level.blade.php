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
                                        <th class="text-center">NO</th>
                                        <th class="text-start">KODE</th>
                                        <th class="text-start">KETERANGAN</th>
                                        <th class="text-center"></th>
                                        <th class="text-end">PAGU</th>
                                        <th class="text-end">REALISASI</th>
                                        <th class="text-center">%</th>
                                        <th class="text-end">SISA</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalpagu = 0;
                                    $totaldsa  = 0;
                                    $totalsisa = 0;

                                    @endphp
                                    @foreach ($data as $item)
                                    <tr class="table-danger">
                                        <th class="text-center">{{$item->KodeWilayah}}</th>
                                        <th class="text-start" colspan="7">{{$item->NamaWilayah}}</th>
                                    </tr>
                                    @php
                                    $totalpaguSatker = 0;
                                    $totaldsaSatker  = 0;
                                    $totalsisaSatker = 0;

                                    @endphp

                                    @foreach ($item->Satker as $satker)
                                    <tr class="table-warning">
                                        <th class="text-end"></th>
                                        <th class="text-start">{{$satker->KodeSatker}}</th>
                                        <th class="text-start">{{$satker->NamaSatker}}</th>
                                        <th class="text-end" colspan="5"></th>
                                    </tr>
                                    @foreach ($satker->Akun as $Akun)
                                    <tr>
                                        <td class="text-center">@</td>
                                        <td class="text-start">{{$Akun->KodeAkun}}</td>
                                        <td class="text-start">{{$Akun->NamaAkun}}</td>
                                        <td class="text-center">{{$Akun->KodeSumberDana}}</td>
                                        <td class="text-end">{{RP($Akun->Pagu)}}</td>
                                        <td class="text-end">{{RP($Akun->Dsa)}}</td>
                                        <td class="text-center">{{Persen($Akun->Persen)}}%</td>
                                        <td class="text-end">{{RP($Akun->Sisa)}}</td>

                                    </tr>
                                    @php
                                        $totalpagu += $Akun->Pagu;
                                        $totaldsa  += $Akun->Dsa;
                                        $totalsisa += $Akun->Sisa;
                                        @endphp
                                        @php
                                        $totalpaguSatker += $Akun->Pagu;
                                        $totaldsaSatker  += $Akun->Dsa;
                                        $totalsisaSatker += $Akun->Sisa;
                                        @endphp
                                    @endforeach

                                    <tr class="table-warning">
                                        <th></th>
                                        <th class="text-end"></th>
                                        <th class="text-start">JUMLAH SATKER</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($totalpaguSatker)}}</th>
                                        <th class="text-end">{{RP($totaldsaSatker)}}</th>
                                        <th class="text-center">{{Persen(divnum($totaldsaSatker,$totalpaguSatker)*100)}}%</th>
                                        <th class="text-end">{{RP($totalsisaSatker)}}</th>
                                    </tr>

                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th></th>
                                        <th class="text-end"></th>
                                        <th class="text-start">JUMLAH RAYA</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($totalpagu)}}</th>
                                        <th class="text-end">{{RP($totaldsa)}}</th>
                                        <th class="text-center">{{Persen(divnum($totaldsa,$totalpagu)*100)}}%</th>
                                        <th class="text-end">{{RP($totalsisa)}}</th>
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
