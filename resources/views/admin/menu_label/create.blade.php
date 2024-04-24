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
    @lang('menu_label.create_label')
    @endslot

    <!-- Page Title -->
    @slot('page_title')
    @lang('menu_label.create_title')
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
            
            <form method="post" action="{{route('menu_label.store')}}">
                @csrf
                @include('admin.menu_label.form')
            </form>
        </div>
    </div>



  @endsection
