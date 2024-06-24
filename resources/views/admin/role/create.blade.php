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
    @lang('role.role')
    @endslot
    @slot('link_two_url')
    {{route('role.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('role.create_role')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('role.create_title')
    @endslot


    @if(Auth::user()->can('Role view'))
    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('role.index')}}
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

            <form method="post" action="{{route('role.store')}}">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('role.name')</label><span class="text-danger">*</span>
                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" id="name"  value="{{ old('name') }}">
                        @error('name')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        <label>@lang('role.name_bn')</label>
                        <input type="text" class="form-control form-control-sm @error('name_bn') is-invalid @enderror" name="name_bn" id="name_bn"  value="{{ old('name_bn') }}">
                        @error('name_bn')
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
