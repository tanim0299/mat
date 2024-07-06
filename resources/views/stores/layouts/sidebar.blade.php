@php
$label = App\Models\MenuLabel::where('type','pos')->where('status',1)->get();
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
<ul class="side-menu metismenu">
    @if(isset($label))
    @foreach ($label as $l)

    @php
        $menu = App\Models\Menu::where('label_id',$l->id)->where('position','pos')->orderBy('order_by','ASC')->get();
        $single_m = App\Models\Menu::where('status',1)->where('type',3)->where('label_id',$l->id)->get();
            $parent_m = App\Models\Menu::where('status',1)->where('type',1)->where('label_id',$l->id)->get();
            foreach($single_m as $sm)
            {
                if($sm->slug == 'index')
                {
                    $slug = 'view';
                }
                elseif($sm->slug == 'dashboard') {
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
    {{-- <span class="text-light">{{ $countlabel }}</span> --}}

    @if($countlabel > 0) <!--counting label -->

    <li class="heading">
        @if(config('app.locale') == 'en')
        {{ $l->label_name ?: $l->label_name_bn }}
        @else
        {{ $l->label_name_bn ?: $l->label_name }}
        @endif
    </li>

    @if(isset($menu))
    @foreach ($menu as $m)
    @if($m->type == '3')

    @php
        $slug = '';
        if($m->slug == 'index')
        {
            $slug = 'view';
        }
        else {
            $slug = 'index';
        }
    @endphp

    @if(Auth::user()->can($m->system_name.' '.$slug))
    <li>
        <a class="@if($currentMenuID->id == $m->id) active @endif" href="@if(Route::has($m->route.'.'.$m->slug)){{route($m->route.'.'.$m->slug)}}@endif"><i class="sidebar-item-icon {{$m->icon}}"></i>
            <span class="nav-label">
                @if(config('app.locale') == 'en')
                {{ $m->name ?: $m->name_bn }}
                @else
                {{ $m->name_bn ?: $m->name }}
                @endif
            </span>
        </a>
    </li>
    @endif
    @elseif($m->type == '1')
    <li class="@if($currentMenuID->parent_id == $m->id) active @endif">
        <a href="javascript:;"><i class="sidebar-item-icon {{$m->icon}}"></i>
            <span class="nav-label">
                @if(config('app.locale') == 'en')
                {{ $m->name ?: $m->name_bn }}
                @else
                {{ $m->name_bn ?: $m->name }}
                @endif
            </span><i class="fa fa-angle-left arrow"></i></a>
        <ul class="nav-2-level collapse">
            @php
                $module = App\Models\Menu::where('parent_id',$m->id)->orderBy('order_by','ASC')->get();
            @endphp
            @if(isset($module))
            @foreach ($module as $mod)
            @php
            $slugs = '';
            if($mod->slug == 'index')
            {
                $slugs = 'view';
            }
            else {
                $slugs = 'index';
            }
            @endphp
            @if(Auth::user()->can($mod->system_name.' '.$slugs))
            <li>
                <a href="@if(Route::has($mod->route.'.'.$mod->slug)){{route($mod->route.'.'.$mod->slug)}}@endif" class="@if($currentMenuID->id == $mod->id) active @endif">
                    @if(config('app.locale') == 'en')
                    {{ $mod->name ?: $mod->name_bn }}
                    @else
                    {{ $mod->name_bn ?: $mod->name }}
                    @endif
                </a>
            </li>
            @endif
            @endforeach
            @endif
        </ul>
    </li>
    @endif
    @endforeach
    @endif

    @endif

    @endforeach
    @endif





</ul>
