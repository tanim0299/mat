@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('category.add')
    @endslot


    @if(Auth::user()->can('Category view'))
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
    {{route('category.index')}}
    @endslot

    @endif

   @endcomponent
</div>
<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('category.add')</b>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('category.store') }}">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="item_name">@lang('category.select_item')</label><span class="text-danger">*</span>
                    <select class="form-control form-control-sm js-example-basic-single" name="item_id" id="item_id">
                        <option value="">@lang('common.select_one')</option>
                        @if(isset($data['item']))
                        @foreach ($data['item'] as $i)
                        <option value="{{ $i->id }}">
                            @if(config('app.locale') == 'en')
                            {{ $i->item_name ?: $i->item_name_bn }}
                            @elseif(config('app.loale') == 'bn')
                            {{ $i->item_name_bn ?: $i->item_name }}
                            @endif
                        </option>
                        @endforeach
                        @endif
                    </select>
                    @error('item_id')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="category_name">@lang('category.category_name')</label><span class="text-danger">*</span>
                    <input type="text" class="form-control form-control-sm @error('category_name') is-invalid @enderror" id="category_name" name="category_name" value="{{ old('category_name') }}">
                    @error('category_name')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-6 col-6">
                    <label for="category_name_bn">@lang('category.category_name_bn')</label>
                    <input type="text" class="form-control form-control-sm" id="category_name_bn" name="category_name_bn" value="{{ old('category_name_bn') }}">
                </div>
                <div class="col-12 text-right mt-2">
                    <button class="btn btn-info btn-sm"><i class="fa fa-save"></i> @lang('common.submit')</button>
                </div>
            </div>
        </form>
    </div>
   </div>


</div>
<!-- END PAGE CONTENT-->

@push('footer_scripts')
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
@endpush

@endsection
