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
    @lang('store.store')
    @endslot
    @slot('link_two_url')
    {{route('store.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('store.create_store')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('store.create_title')
    @endslot


    @if(Auth::user()->can('Manage Store view'))
    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('store.index')}}
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

            <form method="post" action="{{route('store.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('store.store_name')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('store_name') is-invalid @enderror" name="store_name" id="store_name"  value="{{ old('store_name') }}">
                        @error('store_name')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('store.store_name_bn')</label>
                        <input type="text" class="form-control form-control-sm @error('store_name_bn') is-invalid @enderror" name="store_name_bn" id="store_name_bn"  value="{{ old('store_name_bn') }}">
                        @error('store_name_bn')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('store.phone')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('phone') is-invalid @enderror" name="phone" id="phone"  value="{{ old('phone') }}" maxlength="11">
                        @error('phone')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('store.phone_2')</label>
                        <input type="text" class="form-control form-control-sm @error('phone_2') is-invalid @enderror" name="phone_2" id="phone_2"  value="{{ old('phone_2') }}">
                        @error('phone_2')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('store.email')</label>
                        <input type="text" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" id="email"  value="{{ old('email') }}">
                        @error('email')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('store.logo')</label>
                        <input type="file" class="form-control form-control-sm @error('logo') is-invalid @enderror" name="logo" id="logo"  value="{{ old('logo') }}">
                        @error('logo')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-6 col-12 mt-2">
                        <label>@lang('store.adress')</label>
                        <textarea name="adress" id="adress" cols="30" rows="5" class="form-control"></textarea>
                        @error('adress')
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
