@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('color.index_title')
    @endslot


    @if(Auth::user()->can('Color create'))
    <!-- button one -->
    @slot('button_one_name')
    @lang('common.create')
    @endslot

    @slot('button_one_class')
    btn btn-outline-dark btn-sm
    @endslot

    @slot('button_one_icon')
    fa fa-plus
    @endslot

    @slot('button_one_route')
    {{route('color.create')}}
    @endslot

    @endif


    @if(Auth::user()->can('Color trash'))
    <!-- button one -->
    @slot('button_two_name')
    @lang('common.trash_list')
    @endslot

    @slot('button_two_class')
    btn btn-danger btn-sm
    @endslot

    @slot('button_two_icon')
    fa fa-eye
    @endslot

    @slot('button_two_route')
    {{route('color.trash_list')}}
    @endslot

    @endif

   @endcomponent
</div>

<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('color.index_title')</b>
    </div>
    <div class="card-body">
        <table class="table" id="example-table">
            <thead>
                <tr>
                    <th>@lang('common.sl')</th>
                    <th>@lang('color.color_name')</th>
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
<!-- END PAGE CONTENT-->

@push('footer_scripts')
    <!-- PAGE LEVEL SCRIPTS-->
    <script type="text/javascript">
        $(function() {
            $('#example-table').DataTable({
                // pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('color.index') }}",
                columns: [
                    {data: 'sl', name: 'sl'},
                    {data : 'color_name', name: 'color_name'},
                    {data : 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })
    </script>

<script>
    function changeColorStatus(id)
    {
        // alert(id);
        $.ajax({
            headers : {
                'X-CSRF-TOKEN' : '{{ csrf_token() }}'
            },

            url : '{{ route('color.status') }}',

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
