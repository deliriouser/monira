@extends('layouts.simple.master')
@section('title', 'upload')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/dropzone.css')}}">
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
					<h5>Daftar {{$what}}</h5>
				</div>
				<div class="card-body">
                    {{-- @dd($revisi) --}}
                    @foreach ($revisi as $item)
                    <div class="default-according style-1 mb-2" id="accordionoc">
                        <div class="card">
                          <div class="card-header bg-secondary">
                            <h5 class="mb-0">
                              <button class="btn btn-link text-white" data-bs-toggle="collapse" data-bs-target="#collapseicon{{$item->revisi}}" aria-expanded="true" aria-controls="collapse11"><i class="icofont icofont-database"></i>
                              @if($item->revisi==0) Pagu Awal @else Revisi {{$item->revisi}} @endif
                              </button>
                            </h5>
                          </div>
                          <div class="collapse @if ($loop->first) show @endif" id="collapseicon{{$item->revisi}}" aria-labelledby="collapseicon" data-bs-parent="#accordionoc">
                            <div class="card-body">
                                Pagu Dipa : {{RP($item->data_revisi->jumlah)}}
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
					<h5>Upload File {{$what}}</h5>
				</div>
				<div class="card-body">

                    <form id="myform" action="{{ route('save/upload') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Data</label>
                                    <select class="select col-sm-4" name="revisi">
                                        <option value="revisi">Revisi</option>
                                        @if(!empty($revisi))
                                        @foreach ($revisi as $item)
                                        @if($item->revisi>0)
                                        <option value="{{$item->revisi}}">Update Revisi {{$item->revisi}}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                        <option value="0">Dipa Awal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Upload</label>
                                    <input name="file" class="form-control mb-4 col-sm-8" type="file" aria-label="file" required="">
                                </div>
                            </div>
                        </div>

                        <div class="btn-showcase text-start">
                            <button class="btn btn-primary" type="submit">Upload</button>
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
@endsection

