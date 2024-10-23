@extends('stores.layouts.master')
@section('body')
@component('components.store_breadcrumb')

    @slot('page_title')
    @lang('brand.details')
    @endslot

   @endcomponent
<div class="content mt-2">

    <div class="card">
        <div class="card-body">
            <h4>
                @if(config('app.locale') == 'en')
                {{ $data['data']->color_name ?: $data['data']->color_name_bn }}
                @else
                {{ $data['data']->color_name_bn ?: $data['data']->color_name }}
                @endif
            </h4>
            @php
                $explode = explode(' ',$data['data']->created_at);
            @endphp
            <span>@lang('common.create_by') : </span> {{ \App\Helpers\AuthHelper::GetUserName($data['data']->create_by) }} | {{ \App\Traits\Date::getDateWithMonth('-',$explode[0]) }}, {{ date('h:i:s a', strtotime($explode[1])) }}
            <hr>
            @include('admin.layouts.histories')

        </div>
    </div>


@push('footer_scripts')
<script>
    $('.myTable').DataTable({
        order: [[0, 'desc']]
    });
</script>

@endpush



  @endsection
