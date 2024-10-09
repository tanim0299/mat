@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('product_item.edit_title')
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
    {{route('product_item.index')}}
    @endslot

    @endif

   @endcomponent
</div>
<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('product_item.edit_title')</b>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('product_item.update',$data['data']->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="item_name">@lang('product_item.item_name')</label><span class="text-danger">*</span>
                    <input type="text" class="form-control form-control-sm @error('item_name') is-invalid @enderror" id="item_name" name="item_name" value="{{ $data['data']->item_name }}">
                    @error('item_name')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="item_name_bn">@lang('product_item.item_name_bn')</label>
                    <input type="text" class="form-control form-control-sm" id="item_name_bn" name="item_name_bn" value="{{ $data['data']->item_name_bn }}">
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
