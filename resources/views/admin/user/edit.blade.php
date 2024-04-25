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
    @lang('user.user')
    @endslot
    @slot('link_two_url')
    {{route('user.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('user.edit_user')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('user.edit_title')
    @endslot


    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('user.index')}}
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

            <form method="post" action="{{route('user.update',$data['data']->id)}}">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('user.role')</label><span class="text-danger">*</span>
                        <select class="form-select form-select-sm" name="role_id" id="role_id">
                            <option value="">@lang('common.select_one')</option>
                            @if(isset($data['role']))
                            @foreach ($data['role'] as $v)
                            <option @if($data['data']->role_id == $v->id) selected @endif value="{{ $v->id }}">
                            @if(config('app.locale') == 'en')
                            {{ $v->name ?: $v->name_bn }}
                            @else
                            {{ $v->name_bn ?: $v->name }}
                            @endif
                            </option>
                            @endforeach
                            @endif
                        </select>
                        @error('role_id')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
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
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('user.password')</label>
                        <input type="text" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" id="password"  value="{{ old('password') }}" maxlength="14">
                        @error('password')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="text-right mt-2" style="text-align: right;">
                    <button class="btn btn-sm btn-success"><i class="fa fa-save"></i> @lang('common.submit')</button>
                </div>

            </form>
        </div>
    </div>
@endsection
