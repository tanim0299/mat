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


    @if(Auth::user()->can('Menu view'))
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

    @endif


    @endcomponent

    <div class="card">
        <div class="card-body">

            <form method="post" action="{{route('menu.store')}}">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.position')</label><span class="text-danger">*</span>
                        <select class="form-select form-select-sm @error('position') is-invalid @enderror" name="position" id="position" onchange="return getMenulabels()">
                            <option @if(old('position') == 'cms') selected @endif value="cms">CMS</option>
                            <option @if(old('position') == 'pos') selected @endif value="pos">POS</option>
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
                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" id="name"  value="{{ old('name') }}">
                        @error('name')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.name_bn')</label>
                        <input type="text" class="form-control form-control-sm @error('name_bn') is-invalid @enderror" name="name_bn" id="name_bn"  value="{{ old('name_bn') }}">
                        @error('name_bn')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.system_name')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('system_name') is-invalid @enderror" name="system_name" id="system_name"  value="{{ old('system_name') }}">
                        @error('system_name')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.route')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('route') is-invalid @enderror" name="route" id="route"  value="{{ old('route') }}">
                        @error('route')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.slug')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('slug') is-invalid @enderror" name="slug" id="slug"  value="{{ old('slug') }}">
                        @error('slug')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.icon')</label>
                        <input type="text" class="form-control form-control-sm @error('icon') is-invalid @enderror" name="icon" id="icon"  value="{{ old('icon') }}">
                        @error('icon')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('menu.order_by')</label>
                        <input type="text" class="form-control form-control-sm @error('order_by') is-invalid @enderror" name="order_by" id="order_by"  value="{{ old('order_by') }}">
                        @error('order_by')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('common.status')</label><span class="text-danger">*</span><br>

                        <div class="form-check form-check-inline">
                            <input @if(old('status') == 1) checked @endif class="form-check-input" id="active" type="radio" name="status" value="1">
                            <label class="form-check-label" for="active">@lang('common.active')</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input @if(old('status') == 0) checked @endif class="form-check-input" id="inactive" type="radio" name="status" value="0">
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
                            <input @if(old('type') == 1) checked @endif class="form-check-input" id="parent" type="radio" name="type" value="1" onchange="getChecked()">
                            <label class="form-check-label" for="parent">@lang('menu.parent')</label>
                        </div>
                        <div class="form-check">
                            <input @if(old('type') == 2) checked @endif class="form-check-input" id="module" type="radio" name="type" value="2" onchange="getChecked()">
                            <label class="form-check-label" for="module">@lang('menu.module')</label>
                        </div>
                        <div class="form-check">
                            <input @if(old('type') == 3) checked @endif class="form-check-input" id="single" type="radio" name="type" value="3" onchange="getChecked()">
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
                    <div class="form-check form-check-inline">
                        <input class="form-check-input permissions" id="{{$v->id}}" type="checkbox" value="{{$v->name}}" name="permissions[]">
                        <label class="form-check-label" for="{{$v->id}}">{{$v->name}}</label>
                    </div>
                    @endforeach
                    @endif

                </div>

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
        getMenulabels();
        getChecked();
        setTimeout(() => {
            getParentMenus();
        }, 1500);
        $('.select2').select2();
        $('.select3').select2();
    });
</script>

@endpush

  @endsection
