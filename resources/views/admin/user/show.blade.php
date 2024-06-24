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
    @lang('user.user')
    @endslot
    @slot('link_two_url')
    {{route('user.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('user.details')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('user.details')
    @endslot




    @endcomponent

    <div class="card">
        <div class="card-body">
            <h4>
                {{ $data['data']->name }}
            </h4>
            @php
                $explode = explode(' ',$data['data']->created_at);
            @endphp

            @if(isset($data['data']->create_by))
            <span>@lang('common.create_by') : </span> {{ \App\Helpers\AuthHelper::GetUserName($data['data']->create_by) }} | {{ \App\Traits\Date::getDateWithMonth('-',$explode[0]) }}, {{ date('h:i:s a', strtotime($explode[1])) }}
            @endif
            <hr>
            @include('admin.layouts.histories')

        </div>
    </div>


@push('footer_script')
<script>
    $(".myTable").DataTable({
        order: [[0, 'desc']]
    });
</script>

@endpush



  @endsection
