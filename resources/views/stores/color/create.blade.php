@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('color.add')
    @endslot


    @if(Auth::user()->can('Color view'))
    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_class')
    btn btn-outline-info btn-sm
    @endslot

    @slot('button_one_icon')
    fa fa-eye
    @endslot

    @slot('button_one_route')
    {{route('color.index')}}
    @endslot

    @endif

   @endcomponent
</div>
<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('color.add')</b>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('color.store') }}">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="color_name">@lang('color.color_name')</label><span class="text-danger">*</span>
                    <input type="text" class="form-control form-control-sm @error('color_name') is-invalid @enderror" id="color_name" name="color_name" value="{{ old('color_name') }}">
                    @error('color_name')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="color_name_bn">@lang('color.color_name_bn')</label>
                    <input type="text" class="form-control form-control-sm" id="color_name_bn" name="color_name_bn" value="{{ old('color_name_bn') }}">
                </div>
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="color_code">@lang('color.color_code')</label>
                    <input type="color" class="form-control form-control-sm" id="color_code" name="color_code" value="{{ old('color_code') }}">
                    @error('color_code')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12 text-right">
                    <button class="btn btn-info btn-sm"><i class="fa fa-save"></i> @lang('common.submit')</button>
                </div>
            </div>
        </form>
    </div>
   </div>


</div>
<!-- END PAGE CONTENT-->

@endsection
