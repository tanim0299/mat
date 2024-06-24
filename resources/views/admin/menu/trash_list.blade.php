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
    @lang('menu.menu')
    @endslot
    @slot('link_two_url')
    {{route('menu.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('menu.menu_trash_list')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('menu.trash_title')
    @endslot

    @if(Auth::user()->can('Menu view'))

    <!-- button two -->
    @slot('button_two_name')
    @lang('common.view')
    @endslot

    @slot('button_two_route')
    {{route('menu.index')}}
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
                            <th>@lang('menu.position')</th>
                            <th>@lang('menu.menu_type')</th>
                            <th>@lang('menu.label')</th>
                            <th>@lang('menu.parent')</th>
                            <th>@lang('menu.name')</th>
                            <th>@lang('menu.route')</th>
                            <th>@lang('menu.icon')</th>
                            <th>@lang('common.status')</th>
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
        ajax: "{{ route('menu.trash_list') }}",
        columns: [
            {data: 'sl', name: 'sl'},
            {data: 'position', name : 'position'},
            {data: 'menu_type', name : 'menu_type'},
            {data: 'label_name', name: 'label_name'},
            {data: 'parent', name : 'parent'},
            {data: 'name', name : 'name'},
            {data: 'route', name : 'route'},
            {data: 'icon', name : 'icon'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>

<script>
    function changeMenuStatus(id)
    {
        // alert(id);
        $.ajax({
            headers : {
                'X-CSRF-TOKEN' : '{{ csrf_token() }}'
            },

            url : '{{ route('menu.status') }}',

            type : 'POST',

            data : {id},

            success : function(res)
            {

            }
        })
    }
</script>

@endpush



  @endsection
