<nav class="navbar navbar-light navbar-vertical navbar-vibrant navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
      <div class="navbar-vertical-content scrollbar">
        <ul class="navbar-nav flex-column" id="navbarVerticalNav">

            @php
            $label = App\Models\MenuLabel::where('type','cms')->where('status',1)->get();
            $current_route = Route::currentRouteName();
            $explodeRoute = explode('.',$current_route);
            if($explodeRoute[0] == 'store' && $explodeRoute[1] == 'index')
            {
                $currentMenuID = App\Models\Menu::where('route',$explodeRoute[0])->where('slug',$explodeRoute[1])->first();
            }
            else {
                $currentMenuID = App\Models\Menu::where('route',$explodeRoute[0])->first();
            }
            $countlabel = 0;
            $countparent = 0;
            @endphp
            {{-- {{$current_route}} --}}

        @if(isset($label))  <!-- label if -->
        @foreach ($label as $l) <!-- label for each -->

        @php
            $menu  = App\Models\Menu::where('status',1)->where('position','cms')->where('label_id',$l->id)->get();

            $single_m = App\Models\Menu::where('status',1)->where('type',3)->where('label_id',$l->id)->get();
            $parent_m = App\Models\Menu::where('status',1)->where('type',1)->where('label_id',$l->id)->get();
            foreach($single_m as $sm)
            {
                if($sm->slug == 'index')
                {
                    $slug = 'view';
                }
                else {

                    $slug = $sm->slug;
                }
                $this_permission = $sm->system_name.' '.$slug;
                $get_permission = DB::table('permissions')->where('name',$this_permission)->first();
                $count_permission = DB::table('role_has_permissions')->where('permission_id',$get_permission->id)->where('role_id',Auth::user()->roles()->pluck('id')->first())->count();
                $countlabel = $countlabel + $count_permission;
            }
            foreach ($parent_m as $pm)
            {
                $module_m = App\Models\Menu::where('status',1)->where('parent_id',$pm->id)->get();
                foreach ($module_m as $mm)
                {
                    if($mm->slug == 'index')
                    {
                        $slug = 'view';
                    }
                    else {

                        $slug = $mm->slug;
                    }
                    $this_permission = $mm->system_name.' '.$slug;
                    $get_permission = DB::table('permissions')->where('name',$this_permission)->first();
                    $count_permission = DB::table('role_has_permissions')->where('permission_id',$get_permission->id)->where('role_id',Auth::user()->roles()->pluck('id')->first())->count();
                    $countlabel = $countlabel + $count_permission;
                }
            }
        @endphp
        @if($countlabel > 0) <!--counting label -->
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

                @php
                    if($m->slug == 'index')
                    {
                        $slug = 'view';
                    }
                    else
                    {
                        $slug = $m->slug;
                    }
                @endphp

                @if(Auth::user()->can($m->system_name.' '.$slug))
                <a class="nav-link" href="@if(Route::has($m->route.'.'.$m->slug)){{route($m->route.'.'.$m->slug)}} @else # @endif" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="{{ $m->icon }}"></i></span><span class="nav-link-text">
                        @if(config('app.locale') == 'en')
                        {{ $m->name ?: $m->name_bn }}
                        @else
                        {{ $m->name_bn ?: $m->name }}
                        @endif
                    </span></div>
                </a>
                @endif
                @elseif($m->type == 1)

                @php
                    $module_menu = App\Models\Menu::where('parent_id',$m->id)->where('type',2)->get();
                    $system_name = str_replace(' ','',$m->name);
                @endphp

                <a class="nav-link dropdown-indicator @if($currentMenuID->parent_id == $m->id)  @else collapsed @endif" href="#{{$system_name}}" role="button" data-bs-toggle="collapse" aria-expanded="@if($currentMenuID->parent_id == $m->id) true @else false @endif" aria-controls="{{$system_name}}">
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
                <ul class="nav collapse @if($currentMenuID->parent_id == $m->id) show @endif parent" id="{{$system_name}}">
                    @if(isset($module_menu))
                    @foreach ($module_menu as $mm)
                    @php
                    if($mm->slug == 'index')
                    {
                        $slug = 'view';
                    }
                    else
                    {
                        $slug = $mm->slug;
                    }
                @endphp
                    @if(Auth::user()->can($mm->system_name.' '.$slug))
                    <li class="nav-item"><a class="nav-link @if($currentMenuID->id == $mm->id) active @endif" href="@if(Route::has($mm->route.'.'.$mm->slug)) {{ route($mm->route.'.'.$mm->slug) }} @else # @endif" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text">
                            @if(config('app.locale') == 'en')
                            {{ $mm->name ?: $mm->name_bn }}
                            @else
                            {{ $mm->name_bn ?: $mm->name }}
                            @endif
                        </span></div>
                    </a><!-- more inner pages-->
                    </li>
                    @endif
                    @endforeach
                    @endif
                </ul>
                @endif
                @endforeach <!-- menu for each -->
                @endif <!-- menu if -->
        </li>
        @endif <!--counting label -->
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
