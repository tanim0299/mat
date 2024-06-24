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
    @lang('menu_label.menu_label')
    @endslot
    @slot('link_two_url')
    {{route('menu_label.index')}}
    @endslot


    <!-- Active Link -->
    @slot('active_link')
    @lang('menu_label.details')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('menu_label.details')
    @endslot




    @endcomponent

    <div class="card">
        <div class="card-body">
            <h4>
                @if(config('app.locale') == 'en')
                {{ $data['data']->label_name ?: $data['data']->label_name_bn }}
                @else
                {{ $data['data']->label_name_bn ?: $data['data']->label_name }}
                @endif
            </h4>
            @php
                $explode = explode(' ',$data['data']->created_at);
            @endphp
            <span>@lang('common.create_by') : </span> {{ \App\Helpers\AuthHelper::GetUserName($data['data']->create_by) }} | {{ \App\Traits\Date::getDateWithMonth('-',$explode[0]) }}, {{ date('h:i:s a', strtotime($explode[1])) }}
            <hr>
            @include('admin.layouts.histories')
            <hr>
            <h3>@lang('menu_label.this_menu')</h3>
            <div class="table-box col-lg-12 col-12">
                <div class="table-responsive">
                    <table class="table fs--1 mb-0 myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                            <th>@lang('menu.position')</th>
                            <th>@lang('menu.menu_type')</th>
                            <th>@lang('menu.parent')</th>
                            <th>@lang('menu.name')</th>
                            <th>@lang('menu.route')</th>
                            <th>@lang('menu.icon')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @if(isset($data['this_menu']))
                            @foreach ($data['this_menu'] as $v)
                            <tr class="@if($v->deleted_at != NULL) table-danger @endif">
                                <td>{{ $i++ }}</td>
                                <td>
                                  {{$v->position}}
                                </td>
                                <td>
                                    @if($v->type == 1)
                                    @lang('menu.parent')
                                    @elseif($v->type == 2)
                                    @lang('menu.module')
                                    @else
                                    @lang('menu.single')
                                    @endif
                                </td>
                                <td>
                                    @if(isset($v->parent_id))
                                    @if(config('app.locale') == 'en')
                                    {{ $v->parent->name ?: $v->parent->name_bn}}
                                    @else
                                    {{ $v->parent->name_bn ?: $v->parent->name}}
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    @if(config('app.locale') == 'en')
                                    {{ $v->name ?: $v->name_bn}}
                                    @else
                                    {{ $v->name_bn ?: $v->name}}
                                    @endif
                                </td>
                                <td>
                                    {{ $v->route.'.'.$v->slug }}
                                </td>
                                <td>
                                    <i class="{{ $v->icon }}"></i>
                                </td>
                                <td>
                                    <div class="checkbox-wrapper-51">
                                        <input onchange="return changeMenuStatus({{$v->id}})" id="cbx-51" type="checkbox" @if($v->status == 1) checked @endif>
                                        <label class="toggle" for="cbx-51">
                                          <span>
                                            <svg viewBox="0 0 10 10" height="10px" width="10px">
                                              <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                                            </svg>
                                          </span>
                                        </label>
                                      </div>
                                </td>
                                <td>
                                    @php
                                    $show_btn = '<a class="dropdown-item" href="'.route('menu.show',$v->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';

                                    $edit_btn = '<a class="dropdown-item" href="'.route('menu.edit',$v->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';

                                    $delete_btn = '<form id="" method="post" action="'.route('menu.destroy',$v->id).'">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                    <button onclick="return Sure()" type="post" class="dropdown-item text-danger"><i class="fa fa-trash"></i> '.__('common.destroy').'</button>
                                    </form>';

                                    $output = '<div class="dropdown font-sans-serif">
                                    <a class="btn btn-phoenix-default dropdown-toggle" id="dropdownMenuLink" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('common.action').'</a>
                                    <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuLink" style="">'.$show_btn.' '.$edit_btn.' '.$delete_btn.'
                                    </div>
                                </div>';
                                    echo $output;
                                    @endphp
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
        </div>
    </div>


@push('footer_script')
<script>
    $(".myTable").DataTable({
        order: [[0, 'desc']]
    });
</script>

<script>
    function changeMenuStatus(id)
    {
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
