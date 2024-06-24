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
    @lang('user.trash_list')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('user.trash_list')
    @endslot

    @if(Auth::user()->can('Users view'))
    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('user.index')}}
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
                            <th>@lang('user.image')</th>
                            <th>@lang('user.role')</th>
                            <th>@lang('user.name')</th>
                            <th>@lang('user.email')</th>
                            <th>@lang('user.phone')</th>
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
        ajax: "{{ route('user.trash_list') }}",
        columns: [
            {data: 'sl', name: 'sl'},
            {data : 'profile', name: 'profile'},
            {data : 'role', name: 'role'},
            {data : 'name', name: 'name'},
            {data : 'email', name: 'email'},
            {data : 'phone', name: 'phone'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>


@endpush



  @endsection
