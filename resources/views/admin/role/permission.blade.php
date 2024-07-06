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
    @lang('role.role')
    @endslot
    @slot('link_two_url')
    {{route('role.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('role.permission')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('role.permission')
    @endslot


    <!-- button one -->
    @slot('button_one_name')
    @lang('common.view')
    @endslot

    @slot('button_one_route')
    {{route('role.index')}}
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

            <div>
                <label class="form-check">
                    <input class="form-check-input select_all" type="checkbox" value="" name="" id="select_all" onclick="return selectAll()">
                    <span class="form-check-label">
                        @lang('common.select_all')
                    </span>
                </label>
            </div>

            <form method="post" action="{{route('role.permission_store',$data['role']->id)}}">
                @csrf
                <h3>@lang('common.cms')</h3>
                <hr>
                @if(isset($data['menu']))
                @foreach ($data['menu'] as $m)
                @if($m->position == 'cms')
                @php
                    $permissions = DB::table('permissions')->where('parent',$m->system_name)->get();
                @endphp

                <div class="p-2" style="border:1px solid rgb(238, 238, 238);margin-top : 10px;">
                    <div class="row">
                        @if(isset($permissions))
                        @foreach ($permissions as $p)
                        @if($m->position == 'cms')
                        @php
                            $check = DB::table('role_has_permissions')->where('role_id',$data['role']->id)->where('permission_id',$p->id)->count();
                        @endphp

                        <div class="col-lg-3 col-md-6 col-12">
                            <input value="{{ $p->name }}" class="permission" type="checkbox" name="permission[]" id="permission{{ $p->id }}" @if($check == 1) checked @endif>
                            <label for="permission{{ $p->id }}">{{ $p->name }}</label>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
                @endif

                <hr>

                <h3>@lang('common.pos')</h3>
                <hr>
                @if(isset($data['menu']))
                @foreach ($data['menu'] as $m)
                @if($m->position == 'pos')
                @php
                    $permissions = DB::table('permissions')->where('parent',$m->system_name)->get();
                @endphp

                <div class="p-2" style="border:1px solid rgb(238, 238, 238);margin-top : 10px;">
                    <div class="row">
                        @if(isset($permissions))
                        @foreach ($permissions as $p)
                        @php
                            $check = DB::table('role_has_permissions')->where('role_id',$data['role']->id)->where('permission_id',$p->id)->count();
                        @endphp
                        <div class="col-lg-3 col-md-6 col-12">
                            <input value="{{ $p->name }}" class="permission" type="checkbox" name="permission[]" id="permission{{ $p->id }}" @if($check == 1) checked @endif>
                            <label for="permission{{ $p->id }}">{{ $p->name }}</label>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
                @endif
                <div class="text-right mt-2" style="text-align: right;">
                    <button class="btn btn-sm btn-success"><i class="fa fa-save"></i> @lang('common.submit')</button>
                </div>

            </form>
        </div>
    </div>


    @push('footer_script')
    <script>

        function selectAll()
        {
            if($('#select_all').is(':checked'))
            {
                $('input.permission').prop('checked',true);
            }
            else
            {
                $('input.permission').prop('checked',false);
            }
        }

        function checkSelectAll()
        {
            var a = $("input[type='checkbox'].permission");
            if(a.length == a.filter(":checked").length){
                $('#select_all').prop('checked',true);
            }
            else{
                $('#select_all').prop('checked',false);
            }
        }

        checkSelectAll();

    </script>
    @endpush

@endsection
