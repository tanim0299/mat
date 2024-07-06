<?php
namespace App\Repositories;
use App\Interfaces\MenuInterface;
use App\Traits\ViewDirective;
use App\Models\MenuLabel;
use App\Models\Actions;
use App\Models\ActivityLog;
use App\Models\History;
use App\Models\Menu;
use Auth;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Artisan;

class MenuRepository implements MenuInterface{
    protected $path,$sl;
    use ViewDirective;
    public function __construct()
    {
        $this->path = 'admin.menu';
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = Menu::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('parent',function($row){
                if(isset($row->parent_id))
                {
                    if(config('app.locale') == 'en')
                    {
                        return $row->parent->name ?: $row->parent->name_bn;
                    }
                    else
                    {
                        return $row->parent->name_bn ?: $row->parent->name;
                    }
                }
            })
            ->addColumn('label_name',function($row){
                if(isset($row->label_id))
                {
                    if(config('app.locale') == 'en')
                    {
                        return $row->label->label_name ?: $row->label->label_name_bn;
                    }
                    else{
                        return $row->label->label_name_bn ?: $row->label->label_name;
                    }
                }
                else
                {
                    return '-';
                }
            })
            ->addColumn('name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->name ?: $row->name_bn;
                }
                else
                {
                    return $row->name_bn ?: $row->name;
                }
            })
            ->addColumn('type',function($row){
                if($row->type == 'cms')
                {
                    return 'CMS';
                }
                else
                {
                    return 'POS';
                }
            })
            ->addColumn('menu_type',function($row){
                if($row->type == 1)
                {
                    return __('menu.parent');
                }
                elseif($row->type == 2)
                {
                    return __('menu.module');
                }
                else
                {
                    return __('menu.single');
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Menu status'))
                {
                    if($row->status == 1)
                    {
                        $checked = 'checked';
                    }
                    else
                    {
                        $checked = 'false';
                    }
                    return '<div class="checkbox-wrapper-51">
                    <input onchange="return changeMenuStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
                    <label class="toggle" for="cbx-51">
                      <span>
                        <svg viewBox="0 0 10 10" height="10px" width="10px">
                          <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                        </svg>
                      </span>
                    </label>
                  </div>';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('action', function($row){
                if(Auth::user()->can('Menu show'))
                {
                    $show_btn = '<a class="dropdown-item" href="'.route('menu.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';
                }
                else
                {
                    $show_btn ='';
                }

                if(Auth::user()->can('Menu edit'))
                {
                    $edit_btn = '<a class="dropdown-item" href="'.route('menu.edit',$row->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Menu destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('menu.destroy',$row->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button onclick="return Sure()" type="post" class="dropdown-item text-danger"><i class="fa fa-trash"></i> '.__('common.destroy').'</button>
                    </form>';
                }
                else
                {
                    $delete_btn ='';
                }



                $output = '<div class="dropdown font-sans-serif">
                <a class="btn btn-phoenix-default dropdown-toggle" id="dropdownMenuLink" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('common.action').'</a>
                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuLink" style="">'.$show_btn.' '.$edit_btn.' '.$delete_btn.'
                </div>
              </div>';
                return $output;
            })
            ->rawColumns(['action','label_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'index');
    }

    public function create()
    {
        $data = [];
        $data['actions'] = Actions::get();
        return ViewDirective::view($this->path,'create',$data);
    }

    public function store($request)
    {

        // return $request->permissions;
        $data = array(
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'type' => $request->type,
            'status' => $request->status,
            'position' => $request->position,
            'order_by' => $request->order_by,
        );

        if($request->type == 1)
        {
            $data['label_id'] = $request->label_id;
            $data['icon'] = $request->icon;
        }

        if($request->type == 2)
        {
            $data['label_id'] = $request->label_id;
            $data['route'] = $request->route;
            $data['system_name'] = $request->system_name;
            $data['parent_id'] = $request->parent_id;
            $data['slug'] = $request->slug;
        }
        if($request->type == 3)
        {
            $data['label_id'] = $request->label_id;
            $data['route'] = $request->route;
            $data['system_name'] = $request->system_name;
            $data['slug'] = $request->slug;
            $data['icon'] = $request->icon;
        }

        // try {
            Menu::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Menu which name is '.$request->name,
                'description_bn' => 'একটি মেনু তৈরি করেছেন যার নাম '.$request->name,
            ]);

            if($request->type != 1)
            {
                for ($i=0; $i < count($request->permissions) ; $i++)
                {
                    Permission::create([
                        'name' => $request->system_name.' '.$request->permissions[$i],
                        'guard_name' => 'web',
                        'parent' => $request->system_name,
                    ]);
                }
            }
        try{
            toastr()->success(__('menu.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {
        $data['data'] = Menu::withTrashed()->where('id',$id)->first();
        $data['histories'] = History::where('tag','menu')->where('fk_id',$id)->orderBy('time','ASC')->get();
        $data['this_menu'] = Menu::withTrashed()->where('parent_id',$id)->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id)
    {

    }

    public function edit($id)
    {
        $data = [];
        $data['actions'] = Actions::get();
        $data['data'] = Menu::find($id);
        // return $data['data'];
        $data['labels'] = MenuLabel::where('type',$data['data']->position)->get();
        $data['parent'] = Menu::where('type',1)->where('label_id',$data['data']->label_id)->get();
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        // return 0;
        $menu = Menu::find($id);
        // return $menu;
        $data = array(
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'type' => $request->type,
            'status' => $request->status,
            'position' => $request->position,
            'order_by' => $request->order_by,
        );

        Permission::where('parent',$menu->system_name)->delete();
        if($request->type == 1)
        {
            $data['label_id'] = $request->label_id;
            $data['icon'] = $request->icon;
            $data['route'] = $request->route;
            $data['system_name'] = '';
        }

        if($request->type == 2)
        {
            $data['label_id'] = $request->label_id;
            $data['route'] = $request->route;
            // $data['system_name'] = $request->system_name;
            $data['parent_id'] = $request->parent_id;
            $data['slug'] = $request->slug;
            $data['icon'] = '';
        }
        if($request->type == 3)
        {
            $data['label_id'] = $request->label_id;
            $data['route'] = $request->route;
            // $data['system_name'] = $request->system_name;
            $data['parent_id'] = NULL;
            $data['icon'] = $request->icon;
            $data['slug'] = $request->slug;
        }

        // try {
            Menu::find($id)->update($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Menu which name is '.$request->name,
                'description_bn' => 'একটি মেনু সম্পাদন করেছেন যার নাম '.$request->name,
            ]);

            History::create([
                'tag' => 'menu',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);

            if($request->type != 1)
            {
                Artisan::call('cache:forget spatie.permission.cache');
                for ($i=0; $i < count($request->permissions) ; $i++)
                {
                    Permission::create([
                        'name' => $menu->system_name.' '.$request->permissions[$i],
                        'guard_name' => 'web',
                        'parent' => $menu->system_name,
                    ]);
                }
            }
        try{
            toastr()->success(__('menu.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Menu::find($id)->delete();
            $menu = Menu::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Destroy Menu which name is '.$menu->name,
                'description_bn' => 'একটি মেনু ডিলেট করেছেন যার নাম '.$menu->name,
            ]);

            History::create([
                'tag' => 'menu',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('menu.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = Menu::onlyTrashed()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('parent',function($row){
                if(isset($row->parent_id))
                {
                    if(config('app.locale') == 'en')
                    {
                        return $row->parent->name ?: $row->parent->name_bn;
                    }
                    else
                    {
                        return $row->parent->name_bn ?: $row->parent->name;
                    }
                }
            })
            ->addColumn('label_name',function($row){
                if(isset($row->label_id))
                {
                    if(config('app.locale') == 'en')
                    {
                        return $row->label->label_name ?: $row->label->label_name_bn;
                    }
                    else{
                        return $row->label->label_name_bn ?: $row->label->label_name;
                    }
                }
                else
                {
                    return '-';
                }
            })
            ->addColumn('name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->name ?: $row->name_bn;
                }
                else
                {
                    return $row->name_bn ?: $row->name;
                }
            })
            ->addColumn('type',function($row){
                if($row->type == 'cms')
                {
                    return 'CMS';
                }
                else
                {
                    return 'POS';
                }
            })
            ->addColumn('menu_type',function($row){
                if($row->type == 1)
                {
                    return __('menu.parent');
                }
                elseif($row->type == 2)
                {
                    return __('menu.module');
                }
                else
                {
                    return __('menu.single');
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Menu status'))
                {
                    if($row->status == 1)
                    {
                        $checked = 'checked';
                    }
                    else
                    {
                        $checked = 'false';
                    }
                    return '<div class="checkbox-wrapper-51">
                    <input onchange="return changeMenuStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
                    <label class="toggle" for="cbx-51">
                      <span>
                        <svg viewBox="0 0 10 10" height="10px" width="10px">
                          <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                        </svg>
                      </span>
                    </label>
                  </div>';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('action', function($row){
                if(Auth::user()->can('Menu restore'))
                {
                    $restore_btn = '<a class="dropdown-item" href="'.route('menu.restore',$row->id).'"><i class="fa fa-trash-arrow-up"></i> '.__('common.restore').'</a>';
                }
                else
                {
                    $restore_btn = '';
                }

                if(Auth::user()->can('Menu delete'))
                {
                    $delete_btn = '<a onclick="return Sure()" class="dropdown-item text-danger" href="'.route('menu.delete',$row->id).'"><i class="fa fa-trash"></i> '.__('common.delete').'</a>';
                }
                else
                {
                    $delete_btn = '';
                }


                $output = '<div class="dropdown font-sans-serif">
                <a class="btn btn-phoenix-default dropdown-toggle" id="dropdownMenuLink" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('common.action').'</a>
                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuLink" style="">'.$restore_btn.' '.$delete_btn.'
                </div>
              </div>';
                return $output;
            })
            ->rawColumns(['action','label_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            Menu::withTrashed()->where('id',$id)->restore();
            $menu = Menu::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Menu which name is '.$menu->name,
                'description_bn' => 'একটি মেনু পুনুরুদ্ধার করেছেন যার নাম '.$menu->name,
            ]);

            History::create([
                'tag' => 'menu',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('menu.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $menu = Menu::withTrashed()->where('id',$id)->first();
            Permission::where('parent',$menu->system_name)->delete();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Menu which name is '.$menu->name,
                'description_bn' => 'একটি মেনু সম্পূর্ণরুপে ডিলেট করেছেন যার নাম '.$menu->name,
            ]);
            History::where('tag','menu')->delete();
            Menu::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('menu.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function print(){

    }

    public function get_menu_labels($position)
    {
        $data = MenuLabel::where('type',$position)->where('status',1)->get();
        $output = '<select class="form-select form-select-sm select2 @error("label_id") is-invalid @enderror" name="label_id" id="label_id" onchange="getParentMenus();">';
        if(count($data) > 0)
        {
            foreach($data as $v)
            {
                if(config('app.locale') == 'en')
                {
                    $label_name = $v->label_name ?: $v->label_name_bn;
                }
                else
                {
                    $label_name = $v->label_name_bn ?: $v->label_name;
                }
                $output .= '<option value="'.$v->id.'">'.$label_name.'</option>';
            }
        }
        else
        {
            return '<span class="text-danger">'.__("menu.no_labels_found").'!</span>';
        }

        $output .= '</select>';

        return $output;
    }
    public function get_parent($label_id)
    {
        // return $label_id;
        $data = Menu::where('type',1)->where('label_id',$label_id)->get();
        $output = '<select class="form-select form-select-sm select2 @error("parent_id") is-invalid @enderror" name="parent_id" id="parent_id">';
        if(count($data) > 0)
        {
            foreach($data as $v)
            {
                if(config('app.locale') == 'en')
                {
                    $menu_name = $v->name ?: $v->name_bn;
                }
                else
                {
                    $menu_name = $v->name_bn ?: $v->name;
                }
                $output .= '<option value="'.$v->id.'">'.$menu_name.'</option>';
            }
        }
        else
        {
            return '<span class="text-danger">'.__("menu.no_parent_menu_found").'!</span>';
        }

        $output .= '</select>';

        return $output;
    }

    public function status($id)
    {
        try {
            $menu = Menu::withTrashed()->where('id',$id)->first();
            if($menu->status == 1)
            {
                Menu::withTrashed()->where('id',$id)->update([
                    'status' => 0,
                ]);
            }
            else
            {
                Menu::withTrashed()->where('id',$id)->update([
                    'status' => 1,
                ]);
            }
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'status',
                'description' => 'Change Status Menu which name is '.$menu->name,
                'description_bn' => 'একটি মেনু স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$menu->name,
            ]);

            History::create([
                'tag' => 'menu',
                'fk_id' => $id,
                'type' => 'status',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('menu.status_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
}
