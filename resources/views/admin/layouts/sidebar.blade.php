<nav class="navbar navbar-light navbar-vertical navbar-vibrant navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
      <div class="navbar-vertical-content scrollbar">
        <ul class="navbar-nav flex-column" id="navbarVerticalNav">

            @php
            $label = App\Models\MenuLabel::where('type','cms')->where('status',1)->get();

            @endphp

        @if(isset($label))  <!-- label if -->
        @foreach ($label as $l) <!-- label for each -->

        @php
            $menu  = App\Models\Menu::where('status',1)->where('position','cms')->where('label_id',$l->id)->get();
        @endphp
        <li class="nav-item">
                <!-- label-->
                <p class="navbar-vertical-label">
                    @if(config('app.locale') == 'en')
                    {{ $l->label_name ?: $l->label_name_bn }}
                    @else
                    {{ $l->label_name_bn ?: $l->label_name }}
                    @endif
                </p>
                @if(isset($menu)) <!-- menu if -->
                @foreach ($menu as $m) <!-- menu for each -->
                @if($m->type == 3)
                <a class="nav-link" href="@if(Route::has($m->route.'.'.$m->slug)){{route($m->route.'.'.$m->slug)}} @else # @endif" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="{{ $m->icon }}"></i></span><span class="nav-link-text">
                        @if(config('app.locale') == 'en')
                        {{ $m->name ?: $m->name_bn }}
                        @else
                        {{ $m->name_bn ?: $m->name }}
                        @endif
                    </span></div>
                </a>
                @elseif($m->type == 1)

                @php
                    $module_menu = App\Models\Menu::where('parent_id',$m->id)->where('type',2)->get();
                    $system_name = str_replace(' ','',$m->name);

                @endphp

                <a class="nav-link dropdown-indicator collapsed" href="#{{$system_name}}" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{$system_name}}">
                    <div class="d-flex align-items-center">
                        <div class="dropdown-indicator-icon d-flex flex-center"><span class="fas fa-caret-right fs-0"></span></div><span class="nav-link-icon"> <i class="{{ $m->icon }}"></i> </span><span class="nav-link-text">
                            @if(config('app.locale') == 'en')
                            {{ $m->name ?: $m->name_bn }}
                            @else
                            {{ $m->name_bn ?: $m->name }}
                            @endif
                        </span>
                    </div>
                </a>
                <ul class="nav collapse parent" id="{{$system_name}}">
                    @if(isset($module_menu))
                    @foreach ($module_menu as $mm)
                    <li class="nav-item"><a class="nav-link" href="@if(Route::has($mm->route.'.'.$mm->slug)) {{ route($mm->route.'.'.$mm->slug) }} @else # @endif" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text">
                            @if(config('app.locale') == 'en')
                            {{ $mm->name ?: $mm->name_bn }}
                            @else
                            {{ $mm->name_bn ?: $mm->name }}
                            @endif
                        </span></div>
                    </a><!-- more inner pages-->
                    </li>
                    @endforeach
                    @endif
                </ul>
                @endif
                @endforeach <!-- menu for each -->
                @endif <!-- menu if -->
        </li>
            @endforeach <!-- label for each -->
          @endif <!-- label if -->
        </ul>
      </div>
      <div class="navbar-vertical-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link class="btn btn-link border-0 fw-semi-bold d-flex ps-0" :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                <span class="navbar-vertical-footer-icon" data-feather="log-out"></span><span>Sign out</span>
            </x-dropdown-link>

        </form>

    </div>
    </div>
  </nav>
