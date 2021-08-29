@extends('layouts.simple.master')
@section('title', 'upload')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/dropzone.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>File SK Pejabat Perbendaharaan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">File SK</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">

		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">

                    <form id="myform" action="{{route('satker/snipper/post/pejabat')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="status" value="fileSK">
                        <div class="row">

                            <div class="mb-3">
                                <label class="form-label">Tahun Anggaran</label>
                                <select style="font-size:14px" required class="select col-sm-12" name="tahun">

                                <option value="">Pilih</option>
                                @php
                                $tahun_awal=$setyear;
                                $tahun_depan=DATE('Y')+1;
                                for($i=$tahun_awal; $i<=$tahun_depan; $i++) { @endphp
                                <option value="{{$i}}">{{$i}}</option>
                                @php } @endphp
                                </select>
                             </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis SK Pejabat</label>
                                <select style="font-size:14px" required class="select col-sm-12" name="jenis">
                                <option value="">Pilih</option>
                                <option value="SK KPA">SK KPA</option>
                                <option value="SK PPK dan P3SPM">SK PPK dan P3SPM</option>
                                <option value="SK BENDAHARA">SK BENDAHARA</option>
                                <option value="SK BMN">SK PENGELOLA BMN</option>
                                </select>
                            </div>

                                <div class="mb-1">
                                    <label class="form-label">Pilih File SK</label>
                                    <input name="file" class="form-control mb-4 col-sm-8 file" type="file" aria-label="file" required="">
                                </div>
                        </div>
                        <div class="btn-showcase text-start">
                            <button class="btn btn-primary submitPegawai" type="submit">Upload</button>
                            <input class="btn btn-light" type="reset" value="Reset">
                        </div>
                    </form>
				</div>
			</div>
		</div>
        <div class="col-sm-8">
			<div class="card loadSK">
				<div class="card-header">
					<h5>Daftar SK</h5>
				</div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                          <thead class="bg-primary">
                            <tr>
                              <th class="text-center">#</th>
                              <th class="text-center">TAHUN</th>
                              <th class="text-start">JENIS</th>
                              <th class="text-center">...</th>
                            </tr>
                          </thead>
                          <tbody>
                    {{-- @dd($revisi) --}}
                    @foreach ($data as $item)
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{($item->tahun)}}</td>
                        <td class="text-start">{{($item->jenis)}}</td>
                        <td class="text-center">
                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Download" target="_blank" href="{{route('download',['id' => Crypt::encrypt($item->path_berkas)])}}" class="text-success"><i class="icofont icofont-cloud-download fa-2x"></i></a>
                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" href="#" class="text-primary OpenModalSnipper" id="{{$item->id_berkas}}" what="editSK"><i class="icofont icofont-exchange fa-2x"></i></a>
                            <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data" onclick="confirmation_disabled(event)" href="{{route('satker/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->id_berkas),'what'=>'fileSK'])}}" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                        </td>

                    </tr>

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
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
@endsection

