@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/tour.css')}}">

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>{{$segment}} Per {{$unit ?? ''}}</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">File SK</li>
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
                            <h5>Daftar Pejabat Perbendaharaan Propinsi {{strtolower($data->WilayahName)}}</h5>
                        </div>

                        <div class="col-3 text-end dropup-basic">
                            <div class="export">
                                <a target="_blank" href="{{route(Auth::user()->ba.'/reportSnipper',['type'=>'pdf','unit'=>$data->KodeWilayah,'segment'=>'pejabat'])}}"><i data-feather="printer" class="exportbtn text-primary"></i></a>

                            </div>
                        </div>

                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm loadrpd" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-center">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-start">PEJABAT</th>
									<th class="text-center">AKSI</th>
								</tr>
							</thead>
							<tbody>
                                <tr class="table-warning">
									<td class="text-center"><b>{{$data->KodeWilayah}}</b></td>
									<td class="text-start" colspan="4"><b>{{$data->WilayahName}}</b></td>
                                </tr>

                                @foreach ($data->satker as $satker)

                                <tr class="table-info">
                                        <th class="text-center">{{$loop->iteration}}</th>
                                        <th class="text-center">{{$satker->KodeSatker}}</th>
                                        <th class="text-start" colspan="3">
                                           {{$satker->NamaSatuanKerja}}
                                        </th>

                                </tr>
                                @foreach ($satker->pejabat as $pejabat)
                                <tr valign="middle">
                                    <td class="text-center"></td>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-start">{{$pejabat->refjabatan->keterangan}}</td>
                                    <td class="text-start">
                                        <div class="d-inline-block align-middle">
                                            <img style="width:40px !important; height:40px !important;" class="img-fluid img-40 rounded-circle mb-3 m-r-15" src="{{ asset('storage/'.$pejabat->profile->foto)}}" alt="img">
                                            <div class="d-inline-block"><span>{{$pejabat->profile->nama}}</span>
                                                <span style="font-size:16px;" class="text-success">
                                                @if(!empty($pejabat->bnt))
                                                    <i class="fa fa-check-circle"></i>
                                                    @endif
                                                    @if(!empty($pejabat->barjas))
                                                    <i class="fa fa-check-circle"></i>
                                                @endif
                                                </span>
                                              <p class="font-roboto">{{phone($pejabat->profile->telepon)}}</p>



                                            </div>
                                          </div>

                                        </td>
                                    <td class="text-center">
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Data" href="#" class="text-primary OpenModalAdminSnipper static" id="{{$pejabat->pejabat_id}}" what="read"><i class="fa fa-question-circle fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" href="#" class="text-success OpenModalAdminSnipper static" id="{{$pejabat->pejabat_id}}.{{$pejabat->detiljabatan->detil_id ?? '0'}}.{{$pejabat->refJabatan->id_jabatan}}.{{$satker->KodeSatker}}" what="edit"><i class="fa fa-info-circle fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Nonaktif Data" onclick="confirmation_disabled(event)" href="{{route('admin/snipper/action',['status'=>'0', 'id'=>Crypt::encrypt($pejabat->pejabat_id),'what'=>'inactive'])}}" data-bs-original-title="" class="text-warning static"><i class="fa fa-arrow-circle-down fa-2x"></i></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data" onclick="confirmation_disabled(event)" href="{{route('admin/snipper/action',['status'=>'0', 'id'=>Crypt::encrypt($pejabat->pejabat_id),'what'=>'pejabat'])}}" data-bs-original-title="" class="text-danger static"><i class="fa fa-times-circle fa-2x"></i></i></a>
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
<script src="{{asset('assets/js/tour/intro.js')}}"></script>
<script src="{{asset('assets/js/tour/intro-init.js')}}"></script>

<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>

@endsection
