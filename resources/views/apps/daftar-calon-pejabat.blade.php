@extends('layouts.simple.master')
@section('title', 'upload')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/dropzone.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Data Pegawai Bersertifikat</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">Daftar</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">
                    <form id="myform" action="{{route('satker/snipper/post/pejabat')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="status" value="calon">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Jenis Diklat</label>
                                <select style="font-size:14px" required class="select col-sm-12" name="jenis">
                                <option value="">Pilih</option>
                                @foreach($refdata_jabatan as $refdata_jabatan)
                                <option value="{{$refdata_jabatan->id_jabatan}}">{{$refdata_jabatan->jenis_diklat}}</option>
                                @endforeach
                                </select>
                             </div>
                            <div class="mb-3">
                                <label class="form-label">NIP Pegawai</label>
                                <input required type="text" style="font-size:14px" class="form-control onlynumber nip" name="nip">
                                <input class="what" type="hidden" name="what" value="InsertTalent">
                            </div>
                            <div class="append_profile">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Sertifikat</label>
                                <input required type="text" style="font-size:14px" class="form-control" name="sertifikat">
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Pilih File Sertifikat</label>
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
			<div class="card">
				<div class="card-header">
					<h5>Daftar Pegawai Bersertifikat</h5>
				</div>
                    <div class="table-responsive">
                        <table class="table table-sm loadSK">
                          <thead class="bg-primary">
                            <tr>
                              <th class="text-center">#</th>
                              <th class="text-start">JENIS</th>
                              <th class="text-start">NAMA</th>
                              <th class="text-center">...</th>
                            </tr>
                          </thead>
                          <tbody>
                    @foreach ($data as $item)
                        <td class="text-center">
                            {{$loop->iteration}}
                        </td>
                        <td class="text-start">{{$item->refJabatan->jenis_diklat}}</td>
                        <td class="text-start">
                            <div class="d-inline-block align-middle">
                                <img style="width:40px !important; height:40px !important;" class="img-40 m-r-15 rounded-circle align-top" src="{{ asset('storage/'.$item->profile->foto)}}" alt="">
                                <div class="d-inline-block"><span>{{$item->profile->nama ?? ''}}</span>
                                </div>
                              </div>
                            </td>
                            <td class="text-center">
                            <a target="_blank" href="{{route('download',['id' => Crypt::encrypt($item->certificate->path_file)])}}" class="text-success"><i class="icofont icofont-cloud-download fa-2x"></i></a>
                       <a onclick="confirmation_disabled(event)" href="{{route('satker/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=>'calon'])}}" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a></td>
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

