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
                            <h5>Daftar File SK Pejabat</h5>
                        </div>
                        <div class="col-3 text-end">
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
									<th class="text-center">AKSI</th>
									<th class="text-center">TANGGAL UPLOAD</th>
								</tr>
							</thead>
							<tbody>
                                @foreach ($data as $item)
                                <tr class="table-warning">
									<td class="text-center"><b>{{$item->KodeWilayah}}</b></td>
									<td class="text-start" colspan="4"><b>{{$item->WilayahName}}</b></td>
                                </tr>

                                @foreach ($item->satker as $satker)

                                <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{$satker->KodeSatker}}</td>
                                        <td class="text-start" colspan="3">
                                           {{$satker->NamaSatuanKerja}}
                                        </td>

                                </tr>
                                @foreach ($satker->files as $file)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-start">{{$file->jenis}}</td>
                                    <td class="text-center">
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Download" target="_blank" href="{{route('download',['id' => Crypt::encrypt($file->path_berkas)])}}" class="text-success"><i class="icofont icofont-cloud-download fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" href="#" class="text-primary OpenModalAdminSnipper static" id="{{$file->id_berkas}}" what="editSK"><i class="icofont icofont-exchange fa-2x"></i></a>
                                        <a data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data" onclick="confirmation_disabled(event)" href="{{route('admin/snipper/action',['status'=>'0', 'id'=>Crypt::encrypt($file->id_berkas),'what'=>'fileSK'])}}" data-bs-original-title="" class="text-danger static"><i class="icofont icofont-error fa-2x"></i></a>
                                    </td>
                                    <td class="text-center">
                                        {{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i:s') }}
                                    </td>

                                 </tr>

                                @endforeach
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
