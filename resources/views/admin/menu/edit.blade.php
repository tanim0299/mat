@extends('admin.layouts.master')
@section('body')
<div class="content">

    @component('components.breadcrumb')
    <!-- link 1 -->
    @slot('link_one')
    @lang('common.dashboard')
    @endslot
    @slot('link_one_url')
    {{route('admin.home')}}
    @endslot


    <!-- link 2 -->
    @slot('link_two')
    @lang('menu.menu')
    @endslot
    @slot('link_two_url')
    {{route('menu.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('menu.create_menu')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('menu.create_title')
    @endslot


    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('menu.index')}}
    @endslot

    @slot('button_one_class')
    btn btn-sm btn-outline-primary
    @endslot

    @slot('button_one_icon')
    <i class="fa fa-eye"></i>
    @endslot


    @endcomponent

    <div class="card">
        <div class="card-body">

            <form method="post" action="{{route('menu.update',$data['data']->id)}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.position')</label><span class="text-danger">*</span>
                        <select class="form-select form-select-sm @error('position') is-invalid @enderror" name="position" id="position" onchange="return getMenulabels()">
                            <option @if($data['data']->position == 'cms') selected @endif value="cms">CMS</option>
                            <option @if($data['data']->position == 'pos') selected @endif value="pos">POS</option>
                        </select>
                        @error('position')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.label')</label><span class="text-danger">*</span>
                        <div class="showlabels">
                            <select class="form-select form-select-sm select2 @error('label_id') is-invalid @enderror" name="label_id" id="label_id" onchange="getParentMenus();">
                                <option value="">@lang('common.select_one')</option>
                                @if(isset($data['labels']))
                                @foreach ($data['labels'] as $v)
                                <option @if($v->id == $data['data']->label_id) selected @endif value="{{ $v->id }}">
                                    @if(config('app.locale') == 'en')
                                    {{ $v->label_name ?: $v->label_name_bn }}
                                    @else
                                    {{ $v->label_name_bn ?: $v->label_name }}
                                    @endif
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @error('label_id')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.parent')</label><span class="text-danger">*</span>
                        <div class="showparent">
                            <select class="form-select form-select-sm select2 @error('parent_id') is-invalid @enderror" name="parent_id" id="parent_id">
                                <option value="">@lang('common.select_one')</option>
                                @if(isset($data['parent']))
                                @foreach ($data['parent'] as $v)
                                <option @if($v->id == $data['data']->parent_id) selected @endif value="{{ $v->id }}">
                                @if(config('app.locale') == 'en')
                                {{ $v->name ?: $v->name_bn }}
                                @else
                                {{ $v->name_bn ?: $v->name }}
                                @endif
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @error('parent_id')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.name')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" id="name"  value="{{ $data['data']->name }}">
                        @error('name')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.name_bn')</label>
                        <input type="text" class="form-control form-control-sm @error('name_bn') is-invalid @enderror" name="name_bn" id="name_bn"  value="{{ $data['data']->name_bn }}">
                        @error('name_bn')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.system_name')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('system_name') is-invalid @enderror" name="system_name" id="system_name"  value="{{ old('system_name') }}">
                        @error('system_name')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div> --}}
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.route')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('route') is-invalid @enderror" name="route" id="route"  value="{{ $data['data']->route }}">
                        @error('route')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.slug')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('slug') is-invalid @enderror" name="slug" id="slug"  value="{{ $data['data']->slug }}">
                        @error('slug')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.icon')</label>
                        <input type="text" class="form-control form-control-sm @error('icon') is-invalid @enderror" name="icon" id="icon"  value="{{ $data['data']->icon }}">
                        @error('icon')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('common.status')</label><span class="text-danger">*</span><br>

                        <div class="form-check form-check-inline">
                            <input @if($data['data']->status == 1) checked @endif class="form-check-input" id="active" type="radio" name="status" value="1">
                            <label class="form-check-label" for="active">@lang('common.active')</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input @if($data['data']->status == 0) checked @endif class="form-check-input" id="inactive" type="radio" name="status" value="0">
                            <label class="form-check-label" for="inactive">@lang('common.inactive')</label>
                        </div>

                        @error('status')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.type')</label><span class="text-danger">*</span><br>

                        <div class="form-check">
                            <input @if($data['data']->type == 1) checked @endif class="form-check-input" id="parent" type="radio" name="type" value="1" onchange="getChecked()">
                            <label class="form-check-label" for="parent">@lang('menu.parent')</label>
                        </div>
                        <div class="form-check">
                            <input @if($data['data']->type == 2) checked @endif class="form-check-input" id="module" type="radio" name="type" value="2" onchange="getChecked()">
                            <label class="form-check-label" for="module">@lang('menu.module')</label>
                        </div>
                        <div class="form-check">
                            <input @if($data['data']->type == 3) checked @endif class="form-check-input" id="single" type="radio" name="type" value="3" onchange="getChecked()">
                            <label class="form-check-label" for="single">@lang('menu.single')</label>
                        </div>
                        @error('status')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="permission-box p-2" style="border:1px solid lightgray;">
                    @if(isset($data['actions']))
                    @foreach ($data['actions'] as $v)

                    @php
                    $permission_name = $data['data']->system_name.' '.$v->name;
                    $check = DB::table('permissions')->where('name',$permission_name)->first();
                    if(isset($check))
                    {
                        $permission = explode(' ',$check->name);
                        $slug = $permission[count($permission) - 1];
                    }
                    else {
                        $slug = '';
                    }
                    @endphp

                    <div class="form-check form-check-inline">
                        <input class="form-check-input permissions" id="{{$v->id}}" type="checkbox" value="{{$v->name}}" name="permissions[]" @if($slug == $v->name) checked @endif>
                        <label class="form-check-label" for="{{$v->id}}">{{$v->name}}</label>
                    </div>
                    @endforeach
                    @endif

                </div>
                <input type="hidden" name="system_name" value="{{ $data['data']->system_name }}">
                <div class="text-right mt-2" style="text-align: right;">
                    <button class="btn btn-sm btn-success"><i class="fa fa-save"></i> @lang('common.submit')</button>
                </div>

            </form>
        </div>
    </div>

@push('footer_script')
<script>

    function getChecked()
    {
        let type = $("input[type='radio'][name=type]:checked").val();
        if(type != undefined)
        {
            if(type != 1)
            {
                $('.permissions').prop('disabled',false);
                $('.permissions').prop('checked',true);
            }
            else
            {
                $('.permissions').prop('checked',false);
                $('.permissions').prop('disabled',true);
            }
        }
    }


    function getMenulabels()
    {
        let position = $('#position').val();
        // alert(position);
        if(position != '')
        {
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },

                url : '{{ route('menu.get_labels') }}',

                type : 'GET',

                data : {position},

                beforeSend : function(res)
                {
                    $('.showlabels').html('loading.....');
                },

                success : function(res)
                {
                    $('.showlabels').html(res);
                    $('.select2').select2();
                }
            })
        }
    }


    function getParentMenus()
    {
        let label_id = $('#label_id').val();
        if(label_id != '')
        {
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },

                url : '{{ route('menu.get_parent') }}',

                type : 'GET',

                data : {label_id},

                beforeSend : function(res)
                {
                    $('.showparent').html('loading.....');
                },

                success : function(res)
                {
                    $('.showparent').html(res);
                    $('.select2').select2();
                }
            })
        }
    }

    $(document).ready(function(){
        // getMenulabels();
        // getChecked();
        // setTimeout(() => {
        //     getParentMenus();
        // }, 1500);
        $('.select2').select2();
        $('.select3').select2();
    });
</script>

@endpush

  @endsection
