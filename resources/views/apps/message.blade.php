@extends('layouts.simple.master')
@section('title', 'Email Application')

@section('css')
{{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/simple-mde.css')}}"> --}}
@endsection

@section('style')

@endsection

@section('breadcrumb-title')
<h3>Daftar Pesan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Utility</li>
<li class="breadcrumb-item active">Message</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="email-wrap">
      <div class="row">

        <div class="col-xl-12 col-md-6">
          <div class="email-right-aside">
            <div class="card email-body">
              <div class="row">
                <div class="col-xl-4 col-md-12 box-md-12 pr-0">
                  <div class="pe-0 b-r-light"></div>
                  <div class="email-top">
                    <div class="row">
                      <div class="col">
                        <h5>Sent Items</h5>
                      </div>
                      <div class="col text-end">
                        <a href="#" class="openModal" what="compose"><i class="mt-3 text-primary" data-feather="plus-square"></i></a>
                      </div>
                    </div>
                  </div>
                  <div class="inbox ps ps--active-y">
                    @foreach ($data as $item)
                    <div class="media openmessage" id="{{$item->Id}}">
                        <div class="media-size-email"><img width="50px" class="me-3 rounded-circle" src="{{asset('assets/images/user/avatar.png')}}" alt=""></div>
                        <div class="media-body">
                          <h6 class="text-primary">Admin
                              <small class="text-warning float-end">
                              {{\Carbon\Carbon::parse($item->created_at)->isoFormat('D MMMM') }}
                            </small>
                        </h6>
                          <p>{{$item->Subject}}</p>
                        </div>
                      </div>
                    @endforeach

                    </div>
                </div>
                <div class="col-xl-8 col-md-12 box-md-12 pl-0">
                  <div class="email-right-aside">
                    <div class="email-body radius-left">
                      <div class="ps-0">
                        <div class="tab-content">
                          <div class="tab-pane fade" id="pills-darkhome" role="tabpanel" aria-labelledby="pills-darkhome-tab">
                            <div class="email-compose">
                              <div class="email-top compose-border">
                                <div class="row">
                                  <div class="col-sm-8 xl-50">
                                    <h4 class="mb-0">New Message</h4>
                                  </div>
                                  <div class="col-sm-4 btn-middle xl-50">
                                    <button class="btn btn-primary btn-block btn-mail text-center mb-0 mt-0 w-100" type="button" data-bs-original-title="" title=""><i class="fa fa-paper-plane me-2"></i> SEND</button>
                                  </div>
                                </div>
                              </div>
                              <div class="email-wrapper">
                                <form class="theme-form">
                                  <div class="mb-3">
                                    <label class="col-form-label pt-0" for="exampleInputEmail1">To</label>
                                    <input class="form-control" id="exampleInputEmail1" type="email" data-bs-original-title="" title="">
                                  </div>
                                  <div class="mb-3">
                                    <label for="exampleInputPassword1">Subject</label>
                                    <input class="form-control" id="exampleInputPassword1" type="text" data-bs-original-title="" title="">
                                  </div>
                                  <div>
                                    <label class="text-muted">Message</label>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <div class="tab-pane fade active show" id="pills-darkprofile" role="tabpanel" aria-labelledby="pills-darkprofile-tab">
                            <div class="email-content">

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/styles.js')}}"></script>
{{-- <script src="{{asset('assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script> --}}

@endsection
