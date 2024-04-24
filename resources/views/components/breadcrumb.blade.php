<nav class="mb-2" aria-label="breadcrumb">
<ol class="breadcrumb mb-0">
    @if(isset($link_one))
    <li class="breadcrumb-item">
        <a href="{{ $link_one_url }}">
            {{ $link_one }}
        </a>
    </li>
    @endif

    @if(isset($link_two))
    <li class="breadcrumb-item">
        <a href="{{$link_two_url}}">
            {{ $link_two }}
        </a>
    </li>
    @endif

    @if(isset($active_link))
    <li class="breadcrumb-item active">
        {{ $active_link }}
    </li>
    @endif
</ol>
</nav>

<div class="row g-3 flex-between-end mb-5">
    <div class="col-auto">
    @if(isset($page_title))
    <h2 class="mb-2">{{$page_title ?: ''}}</h2>
    @endif
    </div>
    <div class="col-auto">
        @if(isset($button_one_name))
        <a href="{{ $button_one_route }}" class="{{$button_one_class}} mb-2 mb-sm-0" type="submit">
           {{ $button_one_icon }} {{$button_one_name}}
        </a>
        @endif
        @if(isset($button_two_name))
        <a href="{{ $button_two_route }}" class="{{$button_two_class}} mb-2 mb-sm-0" type="submit">
           {{ $button_two_icon }} {{$button_two_name}}
        </a>
        @endif
    </div>
</div>
