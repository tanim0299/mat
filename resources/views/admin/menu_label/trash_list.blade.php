@extends('admin.layouts.master')
@section('body')
<div class="content">

    @component('components.breadcrumb')
    <!-- link 1 -->
    @slot('link_one')
    @lang('common.dashboard')
    @endslot
    @slot('link_one_url')
    {{route('admin.home')}}
    @endslot


    <!-- link 2 -->
    @slot('link_two')
    @lang('menu_label.menu_label')
    @endslot
    @slot('link_two_url')
    {{route('menu_label.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('menu_label.label_trash_list')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('menu_label.trash_title')
    @endslot


    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('menu_label.index')}}
    @endslot

    @slot('button_one_class')
    btn btn-sm btn-outline-primary
    @endslot

    @slot('button_one_icon')
    <i class="fa fa-eye"></i>
    @endslot




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
                            <th>@lang('menu_label.type')</th>
                            <th>@lang('menu_label.label_name')</th>
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
        ajax: "{{ route('menu_label.trash_list') }}",
        columns: [
            {data: 'sl', name: 'sl'},
            {data: 'type', name : 'type'},
            {data: 'label_name', name: 'label_name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>

<script>
    function changeMenuLabelStatus(id)
    {
        // alert(id);
        $.ajax({
            headers : {
                'X-CSRF-TOKEN' : '{{ csrf_token() }}'
            },

            url : '{{ route('menu_label.status') }}',

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
