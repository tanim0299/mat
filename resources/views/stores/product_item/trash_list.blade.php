@extends('stores.layouts.master')
@section('body')
<!-- START PAGE CONTENT-->
<div class="page-heading">
   @component('components.store_breadcrumb')

    @slot('page_title')
    @lang('product_item.trash_title')
    @endslot




    @if(Auth::user()->can('Item Information view'))
    <!-- button one -->
    @slot('button_two_name')
    @lang('common.view')
    @endslot

    @slot('button_two_class')
    btn btn-info btn-sm
    @endslot

    @slot('button_two_icon')
    fa fa-eye
    @endslot

    @slot('button_two_route')
    {{route('product_item.index')}}
    @endslot

    @endif

   @endcomponent
</div>
<div class="page-content fade-in-up">

   <div class="card">
    <div class="card-header">
        <b>@lang('product_item.trash_title')</b>
    </div>
    <div class="card-body">
        <table class="table" id="example-table">
            <thead>
                <tr>
                    <th>@lang('common.sl')</th>
                    <th>@lang('product_item.item_name')</th>
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
                ajax: "{{ route('product_item.trash') }}",
                columns: [
                    {data: 'sl', name: 'sl'},
                    {data : 'item_name', name: 'item_name'},
                    {data : 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })
    </script>
    <script>
        function changeProductItemStatus(id)
        {
            // alert(id);
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },

                url : '{{ route('product_item.status') }}',

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
