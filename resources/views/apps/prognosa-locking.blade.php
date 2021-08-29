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
<li class="breadcrumb-item active">Gembok</li>
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				{{-- <div class="card-body"> --}}
                    <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Status Prognosa Satker</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="lock" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a onclick="confirmation_disabled(event)" href="{{route('admin/prognosa/status',['status'=>'1', 'id'=>Crypt::encrypt(1),'what'=>'eselon1'])}}">Kunci</a>
                                    <a onclick="confirmation_disabled(event)" href="{{route('admin/prognosa/status',['status'=>'0', 'id'=>Crypt::encrypt(0),'what'=>'eselon1'])}}">Buka</a>
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
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-end">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-end">SISA</th>
									<th class="text-end">AKSI</th>
								</tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    $TotalPrognosa  = 0;
                                    $PaguAwalSub    = 0;
                                    $PaguAkhirSub   = 0;
                                    $RealisasiSub   = 0;
                                    $PrognosaSub    = 0;
                                    $TotalSisa      = 0;
                                    $SisaSub        = 0;

                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-warning">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="8"><b>{{$item->NamaHeader}}</b></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kunci Data" onclick="confirmation_disabled(event)" href="{{route('admin/prognosa/status',['status'=>'1', 'id'=>Crypt::encrypt($item->KodeHeader),'what'=>'wilayah'])}}" class="text-danger static" href="#"><i class="icon-lock"></i></a>
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Buka Data" onclick="confirmation_disabled(event)" href="{{route('admin/prognosa/status',['status'=>'0', 'id'=>Crypt::encrypt($item->KodeHeader),'what'=>'wilayah'])}}" class="text-primary static" href="#"><i class="icon-unlock"></i></a>
                                        </div>

                                    </td>
                                </tr>

                                @foreach ($item->Data as $key => $value)

                                <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{$key}}</td>
                                        <td class="text-start">
                                            @if (isset($value->KodeSubHeader))
                                                {{$value->NamaSubHeader}}
                                            @endif
                                        </td>
                                        <td class="text-end">{{RP($value->PaguAkhir)}}</td>
                                        <td class="text-end">{{RP($value->Realisasi)}}</td>
                                        <td class="text-center">{{Persen($value->Persen)}}%</td>
                                        <td class="text-end">{{RP($value->Prognosa)}}</td>
                                        <td class="text-center">{{Persen($value->PersenPrognosa)}}%</td>
                                        <td class="text-end">{{RP($value->PaguAkhir-$value->Prognosa)}}</td>
                                        <td class="text-center">
                                            @if($value->IsLockPrognosa==1)
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Buka Data" onclick="confirmation_disabled(event)" href="{{route('admin/prognosa/status',['status'=>'0', 'id'=>Crypt::encrypt($value->KodeSubHeader),'what'=>'prognosa'])}}" class="text-danger static" href="#"><i class="icon-lock"></i></a>
                                            @else
                                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kunci Data" onclick="confirmation_disabled(event)" href="{{route('admin/prognosa/status',['status'=>'1', 'id'=>Crypt::encrypt($value->KodeSubHeader),'what'=>'prognosa'])}}" class="text-primary static" href="#"><i class="icon-unlock"></i></a>
                                            @endif
                                        </td>

                                </tr>
                                @endforeach
                                @endforeach

						</table>
					{{-- </div> --}}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
