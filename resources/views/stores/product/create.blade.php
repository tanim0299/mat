@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('product.add')
    @endslot


    @if(Auth::user()->can('Product Add view'))
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
    {{route('product.index')}}
    @endslot

    @endif

   @endcomponent
</div>
<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('product.product_add')</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-6">
                <label for="product_name">@lang('product.product_name')</label><span class="text-danger">*</span>
                <input type="text" class="form-control form-control-sm" id="product_name" name="product_name" value="{{ old('product_name') }}">
            </div>
        </div>
    </div>
   </div>


</div>
<!-- END PAGE CONTENT-->

@endsection
