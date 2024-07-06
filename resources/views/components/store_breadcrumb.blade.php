<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            {{$page_title}}

        </h1>
    </div>
    <div class="col-12" style="text-align: right">
        @if(isset($button_one_name))
        <a class="{{$button_one_class}}" href="{{ $button_one_route }}">
           <i class="{{$button_one_icon}}"></i> {{$button_one_name}}
        </a>
        @endif
        @if(isset($button_two_name))
        <a class="{{$button_two_class}}" href="{{ $button_two_route }}">
           <i class="{{$button_two_icon}}"></i> {{$button_two_name}}
        </a>
        @endif
    </div>
</div>
