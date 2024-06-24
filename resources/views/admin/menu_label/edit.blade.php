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
    @lang('menu_label.edit_label')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('menu_label.edit_title')
    @endslot


    @if(Auth::user()->can('Menu Label view'))
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

    @endif


    @endcomponent

    <div class="card">
        <div class="card-body">

            <form method="post" action="{{route('menu_label.update',$data['data']->id)}}">
                @csrf
                @method('PUT')
                @include('admin.menu_label.form')
            </form>
        </div>
    </div>



  @endsection
