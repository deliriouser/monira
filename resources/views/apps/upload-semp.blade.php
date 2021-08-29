@extends('layouts.simple.master')
@section('title', 'upload')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Upload</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Upload</li>
<li class="breadcrumb-item active">{{$what}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">

        <div class="col-sm-4">
			<div class="card loadData">
				<div class="card-header">
					<h5>Daftar SE MP</h5>
				</div>
				<div class="card-body">
                    {{-- @dd($revisi) --}}
                    @foreach ($data as $item)
                    <div class="default-according style-1 mb-2" id="accordionoc">
                        <div class="card">
                          <div class="card-header bg-secondary">
                            <h5 class="mb-0">
                              <button class="btn btn-link text-white" data-bs-toggle="collapse" data-bs-target="#collapseicon{{$item->Bulan}}" aria-expanded="true" aria-controls="collapse11"><i class="icofont icofont-database"></i>
                              {{nameofmonth($item->Bulan)}}
                              </button>
                            </h5>
                          </div>
                          <div class="collapse @if ($loop->first) show @endif" id="collapseicon{{$item->Bulan}}" aria-labelledby="collapseicon" data-bs-parent="#accordionoc">
                            <div class="card-body text-center">
                                SE MP Tahap {{$item->Tahap}} <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Download" target="_blank" href="{{route('download',['id' => Crypt::encrypt($item->path_file)])}}" class="text-success" style="font-size:15px;"><i class="fa fa-cloud-download"></i></a>
                            </div>
                          </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header">
					<h5>Upload File SE MP</h5>
				</div>
				<div class="card-body">

                    <form id="myform" action="{{ route('save/upload') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Bulan</label>
                                    <select required class="select col-sm-4" name="bulan">
                                        <option value="">Pilih</option>
                                        @foreach ($bulan as $item)
                                        @if($item->id<=DATE('n'))
                                        <option value="{{$item->id}}">{{$item->BulanName}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <div class="col-md-6 mb-6">
                                <label class="form-label">Tahap</label>
                                <select required class="select col-sm-4" name="tahap">
                                    <option value="">Pilih</option>
                                    @foreach ($bulan as $item)
                                    @if($item->id<=DATE('n'))
                                    <option value="{{$item->id}}">{{$item->id}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Upload</label>
                                    <input name="file" class="form-control mb-4 col-sm-8 pdf" type="file" aria-label="file" required="">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="what" value="{{$what}}">
                        <div class="btn-showcase text-start">
                            <button class="btn btn-primary submit" type="submit">Upload</button>
                            <input class="btn btn-light" type="reset" value="Reset">
                        </div>
                    </form>
				</div>
			</div>
		</div>

	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endsection

