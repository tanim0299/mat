@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('brand.edit')
    @endslot


    @if(Auth::user()->can('Brand view'))
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
    {{route('brand.index')}}
    @endslot

    @endif

   @endcomponent
</div>
<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('brand.edit')</b>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('brand.update',$data['data']->id) }}">
            @csrf
            @method("PUT")
            <div class="row">
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="brand_name">@lang('brand.brand_name')</label><span class="text-danger">*</span>
                    <input type="text" class="form-control form-control-sm @error('brand_name') is-invalid @enderror" id="brand_name" name="brand_name" value="{{ $data['data']->brand_name }}">
                    @error('brand_name')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="brand_name_bn">@lang('brand.brand_name_bn')</label>
                    <input type="text" class="form-control form-control-sm" id="brand_name_bn" name="brand_name_bn" value="{{ $data['data']->brand_name_bn }}">
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
