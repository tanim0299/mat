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
    @lang('store.trash_title')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('store.trash_title')
    @endslot


    @if(Auth::user()->can('Manage Store view'))
    <!-- button two -->
    @slot('button_two_name')
    @lang('common.view')
    @endslot

    @slot('button_two_route')
    {{route('store.index')}}
    @endslot

    @slot('button_two_class')
    btn btn-sm btn-info
    @endslot

    @slot('button_two_icon')
    <i class="fa fa-eye"></i>
    @endslot

    @endif


    @endcomponent

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table myTable  fs--1 mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('store.name')</th>
                            <th>@lang('store.phone')</th>
                            <th>@lang('store.email')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('store.logo')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


@push('footer_script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Datatables Responsive
    $(".myTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('store.trash_list') }}",
        columns: [
            {data: 'sl', name: 'sl'},
            {data : 'name', name: 'name'},
            {data : 'phone', name: 'phone'},
            {data : 'email', name: 'email'},
            {data : 'status', name: 'status'},
            { data: 'logos', name: 'logos'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>


@endpush



  @endsection
