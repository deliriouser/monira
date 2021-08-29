@extends('layouts.simple.master')
@section('title', 'Snipper')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/tour.css')}}">

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
<h3>Pejabat Perbendaharaan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Snipper</li>
<li class="breadcrumb-item active">Daftar</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-12 project-list">
        <div class="card">
          <div class="row">
            <div class="col-md-6">
              <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-controls="top-home" aria-selected="true" data-bs-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>Menjabat</a></li>
                <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab" href="#top-profile" role="tab" aria-controls="top-profile" aria-selected="false" data-bs-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>Nonaktif</a></li>
              </ul>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-0 me-0"></div>
                <a data-intro="Tambah Pejabat Baru" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Data Pejabat" class="btn btn-success OpenModalSnipper" id="0" what="add" href="#" data-bs-original-title="" title="">
                    <i data-feather="plus-square"></i>
                </a>
                <div class="form-group mb-0 me-0 text-lg-center"></div>
                <a data-intro="Cetak Daftar" target="_blank" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak PDF" class="btn btn-primary" href="{{route('satker/load/pdf',['what'=>'snipper'])}}" data-bs-original-title="" title="">
                    <i data-feather="printer"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
<div class="tab-content" id="top-tabContent">
<div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
   <div class="row" id="loadPejabat">
    @foreach ($data as $item)

    @if($item->status==1)
    <div class="col-xl-4 box-col-4">
        <div class="card custom-card">
            @if(!empty($item->bnt))
            <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BNT</div>
            @endif
            @if(!empty($item->barjas))
            <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BARJAS</div>
            @endif
            @if($item->status==0)
            <div class="ribbon ribbon-bookmark ribbon-right ribbon-danger">Nonaktif</div>
            @endif
            <div class="card-header"><img class="img-fluid" src="{{asset('assets/images/user/'.$item->jabatan.'.jpg')}}" alt=""></div>
          <div class="card-profile"><img class="rounded-circle" src="{{ asset('storage/'.$item->profile->foto)}}" alt=""></div>
          <ul class="card-social">
          </ul>
          <div class="text-center profile-details">
            <h5>{{$item->refJabatan->keterangan ?? ''}}</h5>
            <h6 class="mb-0">{{$item->profile->nama}}</h6>
            <p class="mb-0" name="{{$item->profile->nip}}">{{$item->profile->pangkat}} ({{$item->profile->golongan}})</p>
            <p>NIP. {{$item->profile->nip}}</p>
          </div>
          <div class="card-footer row">
            <div class="col-6 col-sm-6">
              <h6>Lama Menjabat</h6>
                    @if(!empty($item->detiljabatan->tmt_jabatan) AND !empty($item->detiljabatan->tmt_awal))
                    {{\Carbon\Carbon::parse($item->detiljabatan->tmt_awal)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')}}
                    @elseif(!empty($item->detiljabatan->tmt_jabatan))
                    {{\Carbon\Carbon::parse($item->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')}}
                    @else
                    @endif
            </div>
            <div class="col-6 col-sm-6">
              <h6>Telepon</h6>
              {{phone($item->profile->telepon)}}
            </div>
          </div>

          <ul class="card-social">

            <li><a data-intro="Profil Pejabat" href="#{{$item->profile->nip}}" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-primary OpenModalSnipper" what="read" id="{{$item->pejabat_id}}">Detail</div></a></li>
            <li><a data-intro="Nonaktifkan Pejabat" onclick="confirmation_disabled(event)" href="{{route('satker/snipper/user',['status'=>'0', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=> 'user'])}}" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-danger">Nonaktif</div></a></li>
            <li><a data-intro="Edit Data Pejabat" href="#{{$item->profile->nip}}" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-success OpenModalSnipper" what="edit" id="{{$item->pejabat_id}}.{{$item->detiljabatan->detil_id ?? '0'}}.{{$item->refJabatan->id_jabatan}}">Update</div></a></li>
        </ul>



        </div>
      </div>
    @endif
   @endforeach
   </div>
</div>

<div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="top-profile-tab">
    <div class="row" id="loadPejabatInactive">
     @foreach ($data as $item)
     @if($item->status==0)
     <div class="col-xl-4 box-col-4">
         <div class="card custom-card bg-light-danger text-danger">
             @if(!empty($item->bnt))
             <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BNT</div>
             @endif
             @if(!empty($item->barjas))
             <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">BARJAS</div>
             @endif
             @if($item->status==0)

             <div class="ribbon ribbon-bookmark ribbon-right ribbon-danger">Nonaktif</div>
             @endif
             <div class="card-header"><img class="img-fluid" src="{{asset('assets/images/user/'.$item->jabatan.'.jpg')}}" alt=""></div>
           <div class="card-profile"><img class="rounded-circle" src="{{ asset('storage/'.$item->profile->foto)}}" alt=""></div>
           <ul class="card-social">
           </ul>
           <div class="text-center profile-details">
             <h5>{{$item->refJabatan->keterangan ?? ''}}</h5>
             <h6 class="mb-0">{{$item->profile->nama}}</h6>
             <p class="mb-0">{{$item->profile->pangkat}} ({{$item->profile->golongan}})</p>
             <p>NIP. {{$item->profile->nip}}</p>
           </div>
           <div class="card-footer row">
             <div class="col-6 col-sm-6">
               <h6>Pendidikan</h6>
               {{$item->profile->pendidikan_terakhir}}
             </div>
             <div class="col-6 col-sm-6">
               <h6>Telepon</h6>
               {{phone($item->profile->telepon)}}

             </div>
           </div>

           <ul class="card-social">
            <li><a data-intro="Profil Pejabat" href="#{{$item->profile->nip}}" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-primary OpenModalSnipper" what="read" id="{{$item->pejabat_id}}">Detail</div></a></li>
            <li><a data-intro="Aktifkan Pejabat" onclick="confirmation_disabled(event)" href="{{route('satker/snipper/user',['status'=>'1', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=> 'user'])}}" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-dark">Aktifkan</div></a></li>
            <li><a data-intro="Hapus Data" onclick="confirmation_disabled(event)" href="{{route('satker/snipper/user',['status'=>'3', 'id'=>Crypt::encrypt($item->pejabat_id),'what'=> 'deleteuser'])}}" data-bs-original-title="" title=""><div class="span badge rounded-pill pill-badge-danger">Hapus</div></a></li>
           </ul>

         </div>
       </div>
    @endif
    @endforeach
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
{{-- <script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script> --}}
@endsection
