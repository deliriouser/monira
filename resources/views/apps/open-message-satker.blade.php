<div class="email-top">
    <div class="row">
      <div class="col-md-6 xl-100 col-sm-12">
        <div class="media"><img width="50px" class="me-3 rounded-circle" src="{{asset('assets/images/user/avatar.png')}}" alt="">
        <div class="media-body">
            <h6 class="text-primary">Admin
                <small class="text-success float-end">
                {{\Carbon\Carbon::parse($data->created_at)->isoFormat('D MMMM Y h:m:s') }}
              </small>
          </h6>
            <h6>{{$data->message->Subject}}</h6>
        </div>
        </div>
      </div>

    </div>
  </div>
  <div class="email-wrapper">
    {!!$data->message->Message!!}
    <br>
    {{-- <hr> --}}
    <div class="d-inline-block">
      <p class="text-muted">Files Attachment</p><a class="text-muted text-end right-download" href="#" data-bs-original-title="" title=""></a>
      <div class="clearfix"></div>
    </div>
    <div class="attachment mt-3">
      <ul class="list-inline">
          @foreach ($data->message->attachment as $file)
          <li class="list-inline-item text-primary"><a target="_blank" href="{{route('download',['id' => Crypt::encrypt($file->FileName)])}}"><i class="icofont icofont-cloud-download fa-2x"></i></a></li>
          @endforeach
      </ul>
    </div>
  </div>
