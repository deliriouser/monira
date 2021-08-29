@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{asset('assets/images/login/left-bg.png')}}" alt="looginpage"></div>
      <div class="col-xl-7 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo text-center" href="index.html"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo-text-md.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  <form id="myform" class="theme-form" action="{{ route('postlogin') }}" method="POST">
                    {{ csrf_field() }}
                     <h4>Sign in to account</h4>
                     <p>Enter your username & password to login</p>
                     <div class="form-group">
                        <label class="col-form-label">Username</label>
                        <input name="username" class="form-control" type="text" required="">
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="password" required="">
                     </div>
                     <div class="form-group mb-0">
                        <div class="checkbox p-0">
                           <input id="checkbox1" type="checkbox" name="remember">
                           <label class="text-muted" for="checkbox1">Remember</label>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
@endsection

