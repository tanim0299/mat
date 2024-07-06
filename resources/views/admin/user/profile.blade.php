@extends('admin.layouts.master')
@section('body')
<div class="content">

    @component('components.breadcrumb')
    <!-- link 1 -->
    @slot('link_one')
    @lang('common.dashboard')
    @endslot
    @slot('link_one_url')
    {{route('admin.view')}}
    @endslot


    <!-- link 2 -->
    @slot('link_two')
    @lang('user.user')
    @endslot
    @slot('link_two_url')
    {{route('user.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('user.profile')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('user.profile')
    @endslot




    @endcomponent
    <div class="row">
        <div class="col-lg-3 col-md-3 col-3">
            <div class="card">
                <div class="card-body">
                    <div class="profile text-center">
                        @if(isset($data['data']->image))
                        <img src="{{ asset('UserImages') }}/{{ $data['data']->image }}" class="profile img-fluid">
                        @else
                        <img src="https://cdn-icons-png.flaticon.com/512/147/147131.png" class="profile img-fluid">
                        @endif
                        <hr>
                        @if(Auth::user()->id == $data['data']->id)
                        <button class="btn btn-sm btn-primary me-1 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-user"></i> @lang('user.change_profile')
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0 mt-2">
                    @include('admin.user.profile_sidebar')
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('user.personal_info')</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('user.update',Auth::user()->id) }}">
                        @method("PUT")
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12 mt-2">
                                <label>@lang('user.name')</label><span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" id="name"  value="{{ $data['data']->name }}">
                                @error('name')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 mt-2">
                                <label>@lang('user.email')</label><span class="text-danger">*</span>
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" id="email"  value="{{ $data['data']->email }}">
                                @error('email')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 mt-2">
                                <label>@lang('user.phone')</label><span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm @error('phone') is-invalid @enderror" name="phone" id="phone"  value="{{ $data['data']->phone }}" maxlength="14">
                                @error('phone')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">@lang('user.change_profile')</h5><button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
            </div>
            <div class="modal-body">

                <ul class="nav nav-underline" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation"><a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#tab-file-upload" role="tab" aria-controls="tab-file-upload" aria-selected="true"><i class="fa fa-file"></i> @lang('user.file_upload')</a></li>
                    <li class="nav-item" onclick="webcamSet()" role="presentation"><a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#tab-webcam" role="tab" aria-controls="tab-webcam" aria-selected="false" tabindex="-1"><i class="fa fa-camera"></i> @lang('user.webcam')</a></li>
                  </ul>
                  <div class="tab-content mt-3" id="myTabContent">

                    <div class="tab-pane fade show active" id="tab-file-upload" role="tabpanel" aria-labelledby="home-tab">
                        <form method="post" action="{{ route('user.profile_update',$data['data']->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="profileFile" class="form-control form-control-sm">
                            <input type="hidden" name="type" value="file">
                            <img id="blah" src="#" alt="" style="height: 80px;" />
                            <br>
                            <input type="submit" class="btn btn-info btn-sm" value="@lang('common.submit')">
                        </form>
                    </div>


                    <div class="tab-pane fade" id="tab-webcam" role="tabpanel" aria-labelledby="profile-tab">
                        <form method="post" action="{{ route('user.profile_update',$data['data']->id) }}" enctype="multipart/form-data">
                            @csrf


                        <div class="row">
                            <div class="col-6">
                                <div id="my_camera"></div>
                            </div>
                            <div class="col-6">
                                <div id="results">Your captured image will appear here...</div>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="file" class="image-tag">
                        <input type="hidden" name="type" value="webcam">
                        <input type=button class="btn btn-info btn-sm" value="@lang('user.click')" onClick="take_snapshot()">

                        <input type="submit" class="btn btn-info btn-sm" value="@lang('common.submit')">
                    </form>

                    </div>
                  </div>

            </div>
            {{-- <div class="modal-footer"><button class="btn btn-primary" type="button">Okay</button><button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button></div> --}}
          </div>
        </div>
      </div>



@push('footer_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

<script>
    profileFile.onchange = evt => {
  const [file] = profileFile.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  }
}
</script>


<script>
    function webcamSet()
    {
        Webcam.set({
            width: 200,
            height: 150,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach( '#my_camera' );

    }
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }
</script>

@endpush



  @endsection
