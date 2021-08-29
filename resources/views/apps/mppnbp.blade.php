@extends('layouts.simple.master')
@section('title', 'Snipper')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/tour.css')}}">

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
<h3>Maksimum Pencairan PNBP</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">MP PNBP</li>
<li class="breadcrumb-item active">Daftar</li>
@endsection

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Daftar RPD dan MP PNBP</h5>
                        </div>
                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <a data-intro="Cetak Data" href="#"><i data-feather="printer" class="exportbtn text-primary"></i></a>
                                <div class="export-content">
                                    <a target="_blank" href="{{route('satker/load/pdf',['what'=>'rpd'])}}">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadrpd" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">BULAN</th>
									<th class="text-center">SE MP</th>
									<th class="text-center">RPD MP</th>
									<th class="text-center">ALOKASI MP</th>
									<th class="text-center">DAYA SERAP MP</th>
									<th class="text-center">...</th>
								</tr>
							</thead>
                            <tbody>
                                @php
                                    $total_rpd = 0;
                                    $total_mp  = 0;
                                    $total_dsa = 0;
                                    $bulan_depan = Date('n')+1;
                                @endphp
                                @foreach ($data as $item)
                                @if($item->id<=$bulan_depan)
                                @php
                                $total_rpd += $item->rpd->jumlah ?? '0';
                                $total_mp  += $item->mp->Amount ?? '0';
                                $total_dsa += $item->dsa->Amount ?? '0';
                                @endphp

                                <tr>
                                    <td valign="middle" class="text-center">{{$loop->iteration}}</td>
                                    <td valign="middle" class="text-start">{{$item->BulanName}}</td>
                                    <td valign="middle" class="text-center">
                                        @if(count($item->semp)>0)
                                        @foreach ($item->semp as $filemp)
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Download SE MP Tahap {{$filemp->Tahap}}" target="_blank" href="{{route('download',['id' => Crypt::encrypt($filemp->path_file)])}}" class="text-success" style="font-size:20px;"><i class="fa fa-cloud-download"></i></a>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td valign="middle" class="text-end">{{RP($item->rpd->jumlah ?? '0')}}</td>
                                    <td valign="middle" class="text-end">{{RP($item->mp->Amount ?? '0')}}</td>
                                    <td valign="middle" class="text-end">{{RP($item->dsa->Amount ?? '0')}}</td>
                                    <td valign="middle" class="text-center">
                                    @if($alokasiMP<$paguPNBP)
                                        @if((DATE ('d')>='15' AND DATE ('d')<='26') OR $bulan_depan==$item->id)
                                        @if($item->id==$bulan_depan)
                                        <a data-intro="Input RPD" href="#" class="text-primary OpenModalMp" id="{{$item->id}}.{{$paguPNBP-$total_rpd}}" what="create" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                        @endif
                                        @if(!empty($item->rpd->jumlah))
                                        <a data-intro="Lihat Daftar RPD" href="#" class="text-success OpenModalMp"  id="{{$item->id}}" what="read" style="font-size:20px;"><i class="fa fa-search"></i></a>
                                        @endif

                                        @else
                                            @if(!empty($item->rpd->jumlah))
                                            <a data-intro="Lihat Daftar RPD" href="#" class="text-success OpenModalMp"  id="{{$item->id}}" what="read" style="font-size:20px;"><i class="fa fa-search"></i></a>
                                            @endif
                                        @endif
                                    @else
                                        @if(!empty($item->rpd->jumlah))
                                        <a data-intro="Lihat Daftar RPD" href="#" class="text-success OpenModalMp"  id="{{$item->id}}" what="read" style="font-size:20px;"><i class="fa fa-search"></i></i></a>
                                        @endif
                                    @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tr class="table-primary">
                                <th></th>
                                <th class="text-start"></th>
                                <th class="text-start">JUMLAH</th>
									<th class="text-end">{{RP($total_rpd)}}</th>
									<th class="text-end">{{RP($total_mp)}}</th>
									<th class="text-end">{{RP($total_dsa)}}</th>
                                    <th></th>
								</tr>
                                <tr class="table-danger">
                                    <th class="text-start"></th>
                                    <th></th>
									<th class="text-start">JUMLAH PAGU PNBP</th>
									<th class="text-end">{{RP($paguPNBP)}}</th>
									<th class="text-end"></th>
									<th class="text-end"></th>
                                    <th></th>
								</tr>
                                <tr class="table-danger">
                                    <th class="text-start"></th>
                                    <th></th>
									<th class="text-start">SISA RPD</th>
									<th class="text-end">{{RP($paguPNBP-$total_rpd)}}</th>
									<th class="text-end"></th>
									<th class="text-end"></th>
                                    <th></th>
								</tr>

						</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/tour/intro.js')}}"></script>
<script src="{{asset('assets/js/tour/intro-init.js')}}"></script>

@endsection
