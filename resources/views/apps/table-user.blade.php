@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>{{$segment}} Per {{$unit ?? ''}}</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">User</li>
<li class="breadcrumb-item">Daftar</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0">Daftar User Monira
                      </h5>

                      <div class="card-header-right-icon">
                        <i data-feather="users"></i>
                      </div>

                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm">
							<thead class="bg-primary">
								<tr>
									<th class="text-start">PROPINSI</th>
									<th class="text-start">SATKER</th>
									<th class="text-start">USER LOGIN</th>
									<th class="text-start">NAMA</th>
									<th class="text-start">RESET</th>
								</tr>
							</thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                <tr class="table-danger">
                                    <th class="text-start" colspan="5">{{$item->NamaWilayah}}</th>
                                </tr>
                                @foreach($item->Satker as $satker)
                                <tr class="table-warning">
                                    <th class="text-center">{{$satker->KodeSatker}}</th>
                                    <th class="text-start" colspan="4">{{$satker->NamaSatker}}</th>
                                </tr>
                                @foreach($satker->User as $user)
                                <tr>
                                    <td class="text-center">
                                        @if(Cache::has('is_online' . $user->Id))
                                        <i class="fa fa-circle me-3 font-success"> </i>
                                        @else
                                        <i class="fa fa-circle me-3 font-danger"> </i>
                                        @endif
                                    </td>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-start">{{$user->UserLogin}}</td>
                                    <td class="text-start">{{$user->NamaUser}}<br>

                                        {{$user->LastSeen ? \Carbon\Carbon::parse($user->LastSeen)->diffForHumans(): ''}}
                                    </td>
                                    <td class="text-center">
                                        <a onclick="confirmation(event)" href="{{route(Auth::user()->ba.'/resetpassword',['id'=> $user->Id,'kdsatker'=> $user->KodeSatker])}}" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Password" href="#" class="text-primary" style="font-size:20px;"><i data-feather="grid"></i></a>
                                    </td>
                                </tr>
                                @endforeach

                                @endforeach
                                @endforeach
                            </tbody>

						</table>
					</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
@endsection
