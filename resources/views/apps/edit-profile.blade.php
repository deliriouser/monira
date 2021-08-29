@extends('layouts.simple.master')
@section('title', 'Edit Profile')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Edit Profile</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item active">Edit Profile</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="edit-profile">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header">
                        <div class="row">
                            <div class="col-9">
                                <h5>Profile User</h5>
                            </div>
                            <div class="col-3 text-end">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="profile-title">
									<div class="media">
                                        @if(empty(Auth::user()->photo_url))
										<img class="img-100 rounded-circle" alt="" src="{{ asset('assets/images/user/logo.png')}}">
                                        @else
                                        @endif

									</div>
								</div>
                            </div>
                        <div class="col-md-10">
						<form id="myform" action="{{route('save/data/profile')}}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="input-group">
                                        <span class="input-group-text"><i class="icofont icofont-business-man"></i></span>
                                        <input name="name" class="form-control" type="text" value="{{ (Auth::user()->name) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">User Level</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-layers"></i></span>
                                            <input disabled class="form-control" type="text" value="{{ strtoupper(level(Auth::user()->level_id)) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Unit</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-street-view"></i></span>
                                        <input disabled class="form-control" type="text" value="{{ strtoupper((Auth::user()->satker->NamaSatuanKerja ?? '')) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-gear"></i></span>
                                        <input  name="password" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                        <input name="email" id="email" class="form-control" type="text" value="{{ (Auth::user()->profile->email ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-ui-cell-phone"></i></span>
                                        <input  id="only-number" name="phone" class="form-control" type="text" value="{{ (Auth::user()->profile->phone ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="5" placeholder="Enter Your Address">{{Auth::user()->profile->address ?? ''}}</textarea>
                                    </div>
                                </div>

                            </div>

							<div class="form-footer">
								<button id="submitform" type="submit" class="btn btn-primary btn-block" >Save</button>
							</div>
						 </form>
                        </div>
					</div>
				</div>
                </div>
			</div>
			{{-- <div class="col-xl-8">
				<form class="card ">
					<div class="card-header">
						<h4 class="card-title mb-0">User Profile</h4>
						<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
					</div>
					<div class="card-body col-md-12">
						<div class="row">
							<div class="col-md-5">
								<div class="mb-3">
									<label class="form-label">Company</label>
									<input class="form-control" type="text" placeholder="Company">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Username</label>
									<input class="form-control" type="text" placeholder="Username">
								</div>
							</div>
							<div class="col-sm-6 col-md-4">
								<div class="mb-3">
									<label class="form-label">Email address</label>
									<input class="form-control" type="email" placeholder="Email">
								</div>
							</div>
							<div class="col-sm-6 col-md-6">
								<div class="mb-3">
									<label class="form-label">First Name</label>
									<input class="form-control" type="text" placeholder="Company">
								</div>
							</div>
							<div class="col-sm-6 col-md-6">
								<div class="mb-3">
									<label class="form-label">Last Name</label>
									<input class="form-control" type="text" placeholder="Last Name">
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Address</label>
									<input class="form-control" type="text" placeholder="Home Address">
								</div>
							</div>
							<div class="col-sm-6 col-md-4">
								<div class="mb-3">
									<label class="form-label">City</label>
									<input class="form-control" type="text" placeholder="City">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Postal Code</label>
									<input class="form-control" type="number" placeholder="ZIP Code">
								</div>
							</div>
							<div class="col-md-5">
								<div class="mb-3">
									<label class="form-label">Country</label>
									<select class="form-control btn-square">
										<option value="0">--Select--</option>
										<option value="1">Germany</option>
										<option value="2">Canada</option>
										<option value="3">Usa</option>
										<option value="4">Aus</option>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div>
									<label class="form-label">About Me</label>
									<textarea class="form-control" rows="5" placeholder="Enter About your description"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-end">
						<button class="btn btn-primary" type="submit">Update Profile</button>
					</div>
				</form>
			</div> --}}

		</div>
	</div>
</div>
@endsection

@section('script')

@endsection
